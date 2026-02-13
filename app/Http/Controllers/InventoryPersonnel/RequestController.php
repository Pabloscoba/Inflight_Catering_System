<?php

namespace App\Http\Controllers\InventoryPersonnel;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\RequestPendingSupervisorNotification;

class RequestController extends Controller
{
    /**
     * Show pending requests awaiting Inventory Personnel to issue items (NEW WORKFLOW)
     */
    public function pendingRequests()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'supervisor_approved')
            ->latest()
            ->paginate(20);

        return view('inventory-personnel.requests.pending', compact('requests'));
    }

    /**
     * Issue items to catering staff (CORRECTED WORKFLOW - send to Catering Staff)
     */
    public function issueItems(RequestModel $request)
    {
        if ($request->status !== 'supervisor_approved') {
            return back()->with('error', 'This request is not ready for issuing.');
        }

        \DB::transaction(function () use ($request) {
            // Create stock movements for each item (but don't decrease main stock yet)
            foreach ($request->items as $item) {
                \App\Models\StockMovement::create([
                    'type' => 'issued',
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity_requested,
                    'reference_number' => 'REQ-' . $request->id . ' / ' . $request->flight->flight_number,
                    'notes' => 'Issued to Catering Staff for Request #' . $request->id,
                    'user_id' => auth()->id(),
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'movement_date' => now(),
                ]);
                
                // NOTE: Main stock (quantity_in_stock) will be decreased when Catering Staff
                // confirms receipt with actual received quantities
            }
            
            // Update request status - items issued, send to Catering Staff
            $request->update([
                'status' => 'items_issued',
                'dispatched_by' => auth()->id(),
                'dispatched_at' => now(),
            ]);
            
            // Notify Catering Staff (requester)
            $request->requester->notify(new \App\Notifications\RequestApprovedNotification($request));
        });

        return back()->with('success', 'Items issued successfully and sent to Catering Staff.');
    }

    /**
     * Show supervisor-approved requests ready for issuing (NEW WORKFLOW)
     */
    public function supervisorApproved()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'supervisor_approved')
            ->latest()
            ->paginate(20);

        return view('inventory-personnel.requests.supervisor-approved', compact('requests'));
    }

    // Show requests that were issued
    public function issuedRequests()
    {
        $requests = RequestModel::with(['flight','requester','items.product'])
            ->whereIn('status', ['items_issued', 'catering_received', 'security_authenticated', 'ramp_dispatched', 'loaded'])
            ->orderBy('dispatched_at', 'desc')
            ->paginate(20);

        return view('inventory-personnel.requests.issued', compact('requests'));
    }

    /**
     * Edit a pending request (Inventory Personnel adjustment)
     */
    public function edit(RequestModel $requestModel)
    {
        if ($requestModel->status !== 'forwarded' && $requestModel->status !== 'pending_inventory') {
            return back()->with('error', 'Only pending/forwarded requests can be edited.');
        }
        $requestModel->load(['items.product.category']);
        return view('inventory-personnel.requests.edit', compact('requestModel'));
    }

    /**
     * Update a pending request (Inventory Personnel adjustment)
     */
    public function update(Request $request, RequestModel $requestModel)
    {
        if ($requestModel->status !== 'forwarded' && $requestModel->status !== 'pending_inventory') {
            return back()->with('error', 'Only pending/forwarded requests can be updated.');
        }
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        foreach ($validated['items'] as $itemId => $itemData) {
            $item = $requestModel->items->where('id', $itemId)->first();
            if ($item) {
                $item->update(['quantity_requested' => $itemData['quantity']]);
            }
        }
        return redirect()->route('inventory-personnel.requests.edit', $requestModel)->with('success', 'Request updated successfully!');
    }
}
