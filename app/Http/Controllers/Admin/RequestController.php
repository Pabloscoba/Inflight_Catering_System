<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\Flight;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // Display all requests
    public function index(Request $request)
    {
        $query = RequestModel::with(['flight', 'requester', 'items'])
            ->orderBy('requested_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by flight
        if ($request->filled('flight_id')) {
            $query->where('flight_id', $request->flight_id);
        }

        // Search by requester name
        if ($request->filled('search')) {
            $query->whereHas('requester', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $requests = $query->paginate(20);
        $flights = Flight::orderBy('departure_time', 'desc')->get();

        return view('admin.requests.index', compact('requests', 'flights'));
    }

    // Show create request form
    public function create()
    {
        $flights = Flight::where('status', 'scheduled')->orderBy('departure_time', 'asc')->get();
        $products = Product::where('is_active', true)->where('quantity_in_stock', '>', 0)->orderBy('name')->get();

        return view('admin.requests.create', compact('flights', 'products'));
    }

    // Store new request
    public function store(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $newRequest = DB::transaction(function () use ($request) {
            // Create request
            $newRequest = RequestModel::create([
                'flight_id' => $request->flight_id,
                'requester_id' => auth()->id(),
                'status' => 'pending',
                'notes' => $request->notes,
                'requested_date' => now(),
            ]);

            // Add items
            foreach ($request->items as $item) {
                RequestItem::create([
                    'request_id' => $newRequest->id,
                    'product_id' => $item['product_id'],
                    'quantity_requested' => $item['quantity'],
                    'is_scheduled' => isset($item['is_scheduled']) && $item['is_scheduled'] == '1',
                    'scheduled_at' => (isset($item['is_scheduled']) && $item['is_scheduled'] == '1') ? now() : null,
                ]);
            }

            return $newRequest;
        });

        activity()
            ->causedBy(auth()->user())
            ->performedOn($newRequest)
            ->log('Created new request for Flight #' . $newRequest->flight->flight_number);

        return redirect()->route('admin.requests.pending')
            ->with('success', 'Request created successfully! Your request is now pending approval.');
    }

    // Show request details
    public function show(RequestModel $request)
    {
        $request->load(['flight', 'requester', 'approver', 'items.product']);
        return view('admin.requests.show', compact('request'));
    }

    // Show pending requests for approval
    public function pending()
    {
        // Show requests that are awaiting supervisor approval or general pending
        $requests = RequestModel::with(['flight', 'requester', 'items'])
            ->whereIn('status', ['pending', 'pending_supervisor'])
            ->orderBy('requested_date', 'asc')
            ->paginate(20);

        return view('admin.requests.pending', compact('requests'));
    }

    // Show approved requests
    public function approved()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items'])
            ->approved()
            ->orderBy('approved_date', 'desc')
            ->paginate(20);

        return view('admin.requests.approved', compact('requests'));
    }

    // Show rejected requests
    public function rejected()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items'])
            ->rejected()
            ->orderBy('approved_date', 'desc')
            ->paginate(20);

        return view('admin.requests.rejected', compact('requests'));
    }

    // Show approve form
    public function approveForm(RequestModel $request)
    {
        $request->load(['flight', 'requester', 'items.product']);
        return view('admin.requests.approve', compact('request'));
    }

    // Process approval
    public function approve(Request $httpRequest, RequestModel $request)
    {
        $httpRequest->validate([
            'items' => 'required|array',
            'items.*.quantity_approved' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($httpRequest, $request) {
            // If approver is Inventory Supervisor, mark as supervisor_approved and do NOT issue stock yet
            if (auth()->user()->hasRole('Inventory Supervisor')) {
                $request->update([
                    'status' => 'supervisor_approved',
                    'approved_by' => auth()->id(),
                    'approved_date' => now(),
                ]);

                // Update requested item approved quantities
                foreach ($httpRequest->items as $itemId => $data) {
                    $requestItem = RequestItem::findOrFail($itemId);
                    $requestItem->update([
                        'quantity_approved' => $data['quantity_approved'],
                    ]);
                }
            } else {
                // Default behavior: approve and issue stock (inventory personnel/admin)
                $request->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_date' => now(),
                ]);

                // Update items and issue stock
                foreach ($httpRequest->items as $itemId => $data) {
                    $requestItem = RequestItem::findOrFail($itemId);
                    $requestItem->update([
                        'quantity_approved' => $data['quantity_approved'],
                    ]);

                    // Create stock movement (issue)
                    if ($data['quantity_approved'] > 0) {
                        StockMovement::create([
                            'type' => 'issued',
                            'product_id' => $requestItem->product_id,
                            'quantity' => $data['quantity_approved'],
                            'reference_number' => 'REQ-' . $request->id . ' / ' . $request->flight->flight_number,
                            'notes' => 'Auto-issued from approved request',
                            'user_id' => auth()->id(),
                            'movement_date' => now(),
                        ]);

                        // Decrease stock
                        $product = Product::find($requestItem->product_id);
                        if ($product) {
                            $product->decrement('quantity_in_stock', $data['quantity_approved']);
                        }
                    }
                }
            }
        });

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request approved and stock issued successfully.');
    }

    /**
     * Forward a supervisor-approved request to Security (performed by Inventory Personnel)
     */
    public function forwardToSecurity(RequestModel $request)
    {
        if ($request->status !== 'supervisor_approved') {
            return back()->with('error', 'Request must be supervisor approved before forwarding.');
        }

        // Just change status - Security will authenticate and issue stock
        $request->update([
            'status' => 'sent_to_security',
        ]);

        return back()->with('success', 'Request forwarded to Security for authentication.');
    }

    // Reject request
    public function reject(Request $httpRequest, RequestModel $request)
    {
        $httpRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $request->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_date' => now(),
            'rejection_reason' => $httpRequest->rejection_reason,
        ]);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request rejected.');
    }

    // Delete request (only if pending)
    public function destroy(RequestModel $request)
    {
        if (!$request->isPending()) {
            return back()->withErrors([
                'error' => 'Only pending requests can be deleted.'
            ]);
        }

        $request->delete();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request deleted successfully.');
    }
}
