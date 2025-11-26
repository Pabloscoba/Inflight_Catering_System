<?php

namespace App\Http\Controllers\InventoryPersonnel;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Show pending requests awaiting Inventory Personnel review (from Catering Staff)
     */
    public function pendingRequests()
    {
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'pending_inventory')
            ->latest()
            ->paginate(20);

        return view('inventory-personnel.requests.pending', compact('requests'));
    }

    /**
     * Forward request to Supervisor for approval
     */
    public function forwardToSupervisor(RequestModel $request)
    {
        if ($request->status !== 'pending_inventory') {
            return back()->with('error', 'Only pending inventory requests can be forwarded.');
        }

        $request->update([
            'status' => 'pending_supervisor',
        ]);

        return back()->with('success', 'Request forwarded to Inventory Supervisor for approval.');
    }

    // Show requests that were approved by supervisor and awaiting forwarding
    public function supervisorApproved()
    {
        $requests = RequestModel::with(['flight','requester','items.product'])
            ->where('status', 'supervisor_approved')
            ->orderBy('requested_date', 'asc')
            ->paginate(20);

        return view('inventory-personnel.requests.supervisor-approved', compact('requests'));
    }
}
