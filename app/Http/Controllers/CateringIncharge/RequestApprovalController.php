<?php

namespace App\Http\Controllers\CateringIncharge;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\CateringStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestApprovalController extends Controller
{
    /**
     * Display pending requests (NEW WORKFLOW - first approval)
     */
    public function pendingRequests()
    {
        // Show requests pending first Catering Incharge approval
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'pending_catering_incharge')
            ->latest()
            ->paginate(20);

        return view('catering-incharge.requests.pending', compact('requests'));
    }

    /**
     * Show individual request details
     */
    public function showRequest(RequestModel $requestModel)
    {
        $requestModel->load(['flight', 'requester', 'approver', 'items.product']);
        return view('catering-incharge.requests.show', compact('requestModel'));
    }

    /**
     * Approve request (NEW WORKFLOW - first approval, send to Inventory Supervisor)
     */
    public function approveRequest(RequestModel $requestModel)
    {
        // Check if request is awaiting first Catering Incharge approval
        if ($requestModel->status !== 'pending_catering_incharge') {
            return back()->with('error', 'This request is not awaiting Catering Incharge approval.');
        }

        DB::transaction(function () use ($requestModel) {
            // Approve and send to Inventory Supervisor
            $requestModel->update([
                'status' => 'catering_approved',
                'catering_approved_by' => auth()->id(),
                'catering_approved_at' => now(),
            ]);
            
            // Notify Inventory Supervisor
            $inventorySupervisor = \App\Models\User::role('Inventory Supervisor')->first();
            if ($inventorySupervisor) {
                $inventorySupervisor->notify(new \App\Notifications\RequestApprovedNotification($requestModel));
            }
        });

        return back()->with('success', 'Request approved and forwarded to Inventory Supervisor.');
    }

    /**
     * Display requests awaiting final approval (CORRECTED WORKFLOW)
     * After Catering Staff receives items from Inventory
     */
    public function pendingFinalApproval()
    {
        // Show requests where catering staff received items and sent for final approval
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'pending_final_approval')
            ->latest()
            ->paginate(20);

        return view('catering-incharge.requests.pending-final', compact('requests'));
    }

    /**
     * Final approval (CORRECTED WORKFLOW - approve and send to Security)
     */
    public function giveFinalApproval(RequestModel $requestModel)
    {
        // Check if request is awaiting final approval
        if ($requestModel->status !== 'pending_final_approval') {
            return back()->with('error', 'This request is not awaiting final approval.');
        }

        DB::transaction(function () use ($requestModel) {
            // Give final approval and send to Security
            $requestModel->update([
                'status' => 'catering_final_approved',
            ]);
            
            // Notify Security Staff
            $securityStaff = \App\Models\User::role('Security Staff')->get();
            foreach ($securityStaff as $staff) {
                $staff->notify(new \App\Notifications\RequestApprovedNotification($requestModel));
            }
        });

        return back()->with('success', 'Final approval given. Request forwarded to Security for authentication.');
    }
    
    /**
     * Reject catering staff request
     */
    public function rejectRequest(Request $request, RequestModel $requestModel)
    {
        if ($requestModel->status !== 'pending_catering_incharge') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $requestModel->update([
            'status' => 'rejected',
            'catering_approved_by' => auth()->id(),
            'catering_approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Notify requester
        $requestModel->requester->notify(new \App\Notifications\RequestRejectedNotification($requestModel));

        return back()->with('success', 'Request has been rejected.');
    }

    /**
     * View all approved requests (approved by Catering Incharge)
     */
    public function approvedRequests()
    {
        $requests = RequestModel::with(['flight', 'requester', 'approver', 'items.product.category'])
            ->whereIn('status', ['catering_approved', 'catering_received', 'security_authenticated', 'ramp_dispatched', 'loaded', 'delivered'])
            ->latest('catering_approved_at')
            ->paginate(20);

        return view('catering-incharge.requests.approved', compact('requests'));
    }
}
