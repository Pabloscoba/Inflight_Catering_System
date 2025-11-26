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
     * Display pending requests (security authenticated, awaiting Catering Incharge approval)
     */
    public function pendingRequests()
    {
        // Show requests authenticated by Security, awaiting Catering Incharge approval
        $requests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'security_approved')
            ->latest()
            ->paginate(20);

        return view('catering-incharge.requests.pending', compact('requests'));
    }

    /**
     * Approve request and create catering stock
     */
    public function approveRequest(RequestModel $requestModel)
    {
        if ($requestModel->status !== 'security_approved') {
            return back()->with('error', 'This request is not awaiting Catering Incharge approval.');
        }

        // Approve and create catering stock records
        DB::transaction(function () use ($requestModel) {
            foreach ($requestModel->items as $item) {
                $qty = $item->quantity_approved ?? $item->quantity;
                if ($qty <= 0) continue;

                CateringStock::create([
                    'product_id' => $item->product_id,
                    'quantity_received' => $qty,
                    'quantity_available' => $qty,
                    'reference_number' => 'REQ-' . $requestModel->id,
                    'notes' => 'Approved by Catering Incharge for Catering Staff',
                    'received_by' => null,
                    'catering_incharge_id' => auth()->id(),
                    'status' => 'approved',
                    'received_date' => now(),
                    'approved_date' => now(),
                ]);
            }

            $requestModel->update([
                'status' => 'catering_approved',
                'approved_by' => auth()->id(),
                'approved_date' => now(),
            ]);
        });

        return back()->with('success', 'Request approved. Ready for Catering Staff collection.');
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

        return back()->with('success', 'Request rejected.');
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
