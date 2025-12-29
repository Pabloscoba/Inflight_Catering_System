<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\Product;
use App\Models\Flight;
use App\Models\User;
use App\Notifications\NewRequestNotification;
use App\Notifications\RequestPendingInventoryNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $filter = request()->query('filter');

        $query = RequestModel::with(['flight','items.product'])
            ->where('requester_id', $userId);

        if ($filter === 'pending') {
            $query->whereIn('status', ['pending_inventory', 'pending_supervisor', 'supervisor_approved', 'sent_to_security', 'security_approved']);
        } elseif ($filter === 'approved') {
            // catering_approved means final approved and ready for collection
            $query->where('status', 'catering_approved');
        } elseif ($filter === 'rejected') {
            $query->where('status', 'rejected');
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('catering-staff.requests.index', compact('requests', 'filter'));
    }

    public function create()
    {
        // Show all approved and active products from MAIN STOCK (inventory)
        // Catering Staff requests from main inventory, not catering stock
        $products = Product::where('status', 'approved')
            ->where('is_active', true)
            ->orderBy('quantity_in_stock', 'desc') // Show in-stock items first
            ->orderBy('name')
            ->get();
        return view('catering-staff.requests.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'flight_datetime' => 'required|date|after_or_equal:now',
            'requested_date' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.meal_type' => 'nullable|in:breakfast,lunch,dinner,snack,VIP_meal,special_meal,non_meal',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        // Validate product availability from MAIN STOCK (inventory)
        // Catering Staff requests from main inventory, not catering stock
        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->quantity_in_stock <= 0) {
                return back()->withErrors([
                    'items' => "Product '{$product->name}' is out of stock in main inventory. Please select products that are available."
                ])->withInput();
            }
            if ($product->quantity_in_stock < $item['quantity']) {
                return back()->withErrors([
                    'items' => "Insufficient stock for '{$product->name}'. Available in inventory: {$product->quantity_in_stock}, Requested: {$item['quantity']}"
                ])->withInput();
            }
        }
        DB::transaction(function () use ($data) {
            // Update flight departure time with user-selected datetime
            $flight = Flight::find($data['flight_id']);
            $flight->departure_time = $data['flight_datetime'];
            $flight->arrival_time = \Carbon\Carbon::parse($data['flight_datetime'])->addHours(2); // Estimate 2 hours
            $flight->save();
            
            // Auto-detect request type based on items
            $requestType = 'product'; // default
            $hasMeals = false;
            $hasProducts = false;
            
            foreach ($data['items'] as $it) {
                $product = Product::find($it['product_id']);
                if ($product && $product->meal_type) {
                    $hasMeals = true;
                } else {
                    $hasProducts = true;
                }
            }
            
            // Determine request type
            if ($hasMeals && !$hasProducts) {
                $requestType = 'meal';
            } elseif ($hasMeals && $hasProducts) {
                $requestType = 'mixed';
            }
            
            // All requests start at pending_catering_incharge (NEW WORKFLOW)
            $initialStatus = 'pending_catering_incharge';
            
            $req = RequestModel::create([
                'flight_id' => $data['flight_id'],
                'requester_id' => auth()->id(),
                'requested_date' => $data['requested_date'],
                'notes' => $data['notes'] ?? null,
                'request_type' => $requestType,
                'status' => $initialStatus,
            ]);

            foreach ($data['items'] as $it) {
                RequestItem::create([
                    'request_id' => $req->id,
                    'product_id' => $it['product_id'],
                    'meal_type' => $it['meal_type'] ?? null,
                    'quantity_requested' => $it['quantity'],
                ]);
            }
            
            // Notify Catering Incharge (NEW WORKFLOW - all requests go to Catering Incharge first)
            $cateringIncharge = User::role('Catering Incharge')->first();
            if ($cateringIncharge) {
                $cateringIncharge->notify(new NewRequestNotification($req));
            }
        });

        return redirect()->route('catering-staff.requests.index')->with('success', 'Request created successfully.');
    }

    public function show(RequestModel $requestModel)
    {
        // Ensure the user owns the request
        if ($requestModel->requester_id !== auth()->id()) {
            abort(403);
        }

        return view('catering-staff.requests.show', compact('requestModel'));
    }

    /**
     * Show requests with items ready to receive from Inventory
     */
    public function itemsToReceive()
    {
        $requests = RequestModel::with(['flight', 'items.product'])
            ->where('requester_id', auth()->id())
            ->where('status', 'items_issued')
            ->latest()
            ->paginate(20);

        return view('catering-staff.requests.items-to-receive', compact('requests'));
    }

    /**
     * Receive items from Inventory and send to ramp (CORRECTED WORKFLOW)
     * This action sends to Catering Incharge for final approval before ramp
     */
    public function receiveAndSendToRamp(Request $request, RequestModel $requestModel)
    {
        if ($requestModel->status !== 'items_issued') {
            return back()->with('error', 'Items have not been issued yet.');
        }

        // Validate received quantities
        $request->validate([
            'received_quantities' => 'required|array',
            'received_quantities.*' => 'required|integer|min:0',
            'receipt_notes' => 'nullable|string|max:1000'
        ]);

        // Update each item with the actually received quantity
        foreach ($request->received_quantities as $itemId => $receivedQty) {
            $item = $requestModel->items()->find($itemId);
            if ($item) {
                $issuedQty = $item->quantity_approved ?? $item->quantity_requested;
                
                // Validate that received quantity doesn't exceed issued quantity
                if ($receivedQty > $issuedQty) {
                    return back()->with('error', "Received quantity for {$item->product->name} cannot exceed issued quantity ({$issuedQty}).");
                }
                
                // Update the item with actually received quantity
                $item->update([
                    'quantity_received' => $receivedQty
                ]);

                // Create stock movement for received items and update stocks
                if ($receivedQty > 0) {
                    \App\Models\StockMovement::create([
                        'type' => 'issued',
                        'product_id' => $item->product_id,
                        'quantity' => $receivedQty,
                        'reference_number' => 'REQ-' . $requestModel->id . ' / ' . $requestModel->flight->flight_number,
                        'notes' => 'Received by Catering Staff for Request #' . $requestModel->id,
                        'user_id' => auth()->id(),
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'movement_date' => now(),
                    ]);

                    // Update stocks: decrease main stock and increase catering stock
                    $product = $item->product;
                    $product->decrement('quantity_in_stock', $receivedQty);
                    $product->increment('catering_stock', $receivedQty);
                }
            }
        }

        // Catering Staff receives items and sends to Catering Incharge for final approval
        $requestModel->update([
            'status' => 'pending_final_approval',
            'received_by' => auth()->id(),
            'received_date' => now(),
            'receipt_notes' => $request->receipt_notes,
        ]);

        // Notify Catering Incharge for final approval
        $cateringIncharge = \App\Models\User::role('Catering Incharge')->first();
        if ($cateringIncharge) {
            $cateringIncharge->notify(new \App\Notifications\RequestApprovedNotification($requestModel));
        }

        return redirect()->route('catering-staff.requests.index')
            ->with('success', 'Items received successfully and sent to Catering Incharge for final approval.');
    }

    // Mark request as received by Catering Staff after Catering Incharge approves
    public function markReceived(RequestModel $requestModel)
    {
        if ($requestModel->status !== 'approved') {
            return back()->with('error', 'This request is not approved yet.');
        }

        $requestModel->update([
            'status' => 'received',
            'received_by' => auth()->id(),
            'received_date' => now(),
        ]);

        return back()->with('success', 'Request marked as received.');
    }

    // Record used items (reduces catering stock)
    public function recordUsage(Request $request, RequestModel $requestModel)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $it) {
                // Deduct from CateringStock (FIFO)
                $remaining = $it['quantity'];
                $stocks = \App\Models\CateringStock::where('product_id', $it['product_id'])
                    ->where('status', 'approved')
                    ->where('quantity_available', '>', 0)
                    ->orderBy('approved_date', 'asc')
                    ->get();

                foreach ($stocks as $stock) {
                    if ($remaining <= 0) break;
                    $deduct = min($remaining, $stock->quantity_available);
                    $stock->decrement('quantity_available', $deduct);
                    $remaining -= $deduct;
                }
            }
        });

        return back()->with('success', 'Usage recorded and catering stock updated.');
    }

    // Return unused items back to catering stock
    public function returnItems(Request $request, RequestModel $requestModel)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $it) {
                // Add back to a new CateringStock record representing returned items
                \App\Models\CateringStock::create([
                    'product_id' => $it['product_id'],
                    'quantity_received' => 0,
                    'quantity_available' => $it['quantity'],
                    'reference_number' => 'RETURN-' . strtoupper(uniqid()),
                    'notes' => 'Returned by Catering Staff',
                    'received_by' => auth()->id(),
                    'received_date' => now(),
                    'status' => 'approved',
                    'catering_incharge_id' => null,
                ]);
            }
        });

        return back()->with('success', 'Returned items recorded back into catering stock.');
    }

    /**
     * Send approved request to Ramp Dispatcher for dispatch
     */
    public function sendToRamp(RequestModel $requestModel)
    {
        if ($requestModel->status !== 'catering_approved') {
            return back()->with('error', 'Only approved requests can be sent to Ramp Dispatcher.');
        }

        if ($requestModel->requester_id !== auth()->id()) {
            return back()->with('error', 'You can only send your own requests.');
        }

        $requestModel->update([
            'status' => 'ready_for_dispatch',
            'sent_to_ramp_at' => now(),
        ]);

        return back()->with('success', 'Request sent to Ramp Dispatcher for dispatch to aircraft.');
    }
}
