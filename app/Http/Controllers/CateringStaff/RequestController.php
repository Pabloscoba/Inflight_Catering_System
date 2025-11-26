<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\Product;
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
        $products = Product::where('status', 'approved')->get();
        return view('catering-staff.requests.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'requested_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            $req = RequestModel::create([
                'flight_id' => $data['flight_id'],
                'requester_id' => auth()->id(),
                'requested_date' => $data['requested_date'],
                'notes' => $data['notes'] ?? null,
                // New workflow: initial state pending inventory personnel review
                'status' => 'pending_inventory',
            ]);

            foreach ($data['items'] as $it) {
                RequestItem::create([
                    'request_id' => $req->id,
                    'product_id' => $it['product_id'],
                    'quantity_requested' => $it['quantity'],
                ]);
            }
        });

        return redirect()->route('catering-staff.requests.index')->with('success', 'Request submitted successfully. Awaiting Inventory Personnel review.');
    }

    public function show(RequestModel $requestModel)
    {
        // Ensure the user owns the request
        if ($requestModel->requester_id !== auth()->id()) {
            abort(403);
        }

        return view('catering-staff.requests.show', compact('requestModel'));
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
