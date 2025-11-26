<?php

namespace App\Http\Controllers\CateringStaff;

use App\Http\Controllers\Controller;
use App\Models\AdditionalProductRequest;
use Illuminate\Http\Request;

class AdditionalRequestController extends Controller
{
    public function index()
    {
        $pendingRequests = AdditionalProductRequest::with(['originalRequest.flight', 'requester', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        $approvedRequests = AdditionalProductRequest::with(['originalRequest.flight', 'requester', 'product', 'approver'])
            ->whereIn('status', ['approved', 'delivered'])
            ->latest()
            ->limit(20)
            ->get();
        
        return view('catering-staff.additional-requests.index', compact('pendingRequests', 'approvedRequests'));
    }
    
    public function approve(Request $request, AdditionalProductRequest $additionalRequest)
    {
        $request->validate([
            'quantity_approved' => 'required|integer|min:1',
        ]);
        
        $additionalRequest->update([
            'quantity_approved' => $request->quantity_approved,
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return back()->with('success', 'Additional product request approved successfully!');
    }
    
    public function reject(AdditionalProductRequest $additionalRequest)
    {
        $additionalRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return back()->with('success', 'Additional product request rejected.');
    }
    
    public function markDelivered(AdditionalProductRequest $additionalRequest)
    {
        if ($additionalRequest->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as delivered.');
        }
        
        $additionalRequest->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        
        return back()->with('success', 'Additional product request marked as delivered!');
    }
}
