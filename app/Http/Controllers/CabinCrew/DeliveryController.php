<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function markDelivered(RequestModel $request)
    {
        // Verify that the request is in 'loaded' status
        if ($request->status !== 'loaded') {
            return back()->with('error', 'This request cannot be marked as delivered. Current status: ' . $request->status);
        }
        
        // Update request status to delivered
        $request->update([
            'status' => 'delivered',
            'delivered_by' => auth()->id(),
            'delivered_at' => now(),
        ]);
        
        return redirect()->route('cabin-crew.dashboard')
            ->with('success', 'Request #' . $request->id . ' has been successfully marked as delivered!');
    }
    
    public function delivered()
    {
        $deliveredRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where('status', 'delivered')
            ->orderBy('delivered_at', 'desc')
            ->paginate(20);
        
        return view('cabin-crew.delivered', compact('deliveredRequests'));
    }
}
