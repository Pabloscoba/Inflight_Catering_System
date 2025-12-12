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
     * Display pending requests (meal requests OR supervisor approved product requests)
     */
    public function pendingRequests()
    {
        // Show TWO types of requests:
        // 1. Meal requests (request_type = 'meal', status = 'pending') - Direct from Catering Staff
        // 2. Product requests (status = 'supervisor_approved') - From Inventory Supervisor
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where(function($q) {
                    // Meal requests - direct from Catering Staff
                    $q->where('request_type', 'meal')
                      ->where('status', 'pending');
                })->orWhere(function($q) {
                    // Product requests - from Inventory Supervisor
                    $q->where('status', 'supervisor_approved');
                });
            })
            ->latest()
            ->paginate(20);

        return view('catering-incharge.requests.pending', compact('requests'));
    }

    /**
     * Approve request (handles both meal and product requests)
     */
    public function approveRequest(RequestModel $requestModel)
    {
        // Check if request is awaiting Catering Incharge approval
        $validStatuses = ['supervisor_approved', 'pending']; // pending = meal requests
        if (!in_array($requestModel->status, $validStatuses)) {
            return back()->with('error', 'This request is not awaiting Catering Incharge approval.');
        }

        // Different handling based on request type
        DB::transaction(function () use ($requestModel) {
            if ($requestModel->request_type === 'meal') {
                // MEAL REQUEST: Goes to Security for dispatch (no catering stock creation)
                $requestModel->update([
                    'status' => 'catering_approved',
                    'catering_approved_by' => auth()->id(),
                    'catering_approved_at' => now(),
                ]);
            } else {
                // PRODUCT REQUEST: Forward to Security for authentication & stock issuance
                $requestModel->update([
                    'status' => 'sent_to_security',
                    'catering_approved_by' => auth()->id(),
                    'catering_approved_at' => now(),
                ]);
            }
            
            // Notify requester
            $requestModel->requester->notify(new RequestApprovedNotification($requestModel));
            
            // Notify Security Staff
            $securityStaff = \App\Models\User::role('Security Staff')->get();
            foreach ($securityStaff as $staff) {
                $staff->notify(new RequestApprovedNotification($requestModel));
            }
        });

        $message = ($requestModel->request_type === 'meal') 
            ? 'Meal request approved. Ready for Security dispatch.' 
            : 'Product request approved and forwarded to Security Staff for authentication.';

        return back()->with('success', $message);
    }

    /**
     * Reject catering staff request
     */
    public function rejectRequest(Request $request, RequestModel $requestModel)
    {
        if ($requestModel->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $requestModel->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_date' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        // Notify requester
        $requestModel->requester->notify(new RequestRejectedNotification($requestModel));

        return back()->with('success', 'Request has been rejected.');
    }

    /**
     * View all approved requests (approved by Catering Incharge)
     */
    public function approvedRequests()
    {
        $requests = RequestModel::with(['flight', 'requester', 'approver', 'items.product.category'])
            ->where('status', 'catering_approved')
            ->latest('approved_date')
            ->paginate(20);

        return view('catering-incharge.requests.approved', compact('requests'));
    }
}
