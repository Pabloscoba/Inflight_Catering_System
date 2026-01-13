<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Show comprehensive served form with usage tracking
     */
    public function showServedForm(RequestModel $request)
    {
        $validStatuses = ['loaded', 'flight_received', 'delivered'];
        
        if (!in_array($request->status, $validStatuses)) {
            return redirect()->route('cabin-crew.dashboard')
                ->with('error', 'This request is not ready to be marked as served. Current status: ' . $request->status);
        }
        
        $request->load(['flight', 'items.product', 'requester']);
        
        return view('cabin-crew.served-form', compact('request'));
    }
    
    /**
     * Process served form with actual usage data
     */
    public function submitServed(Request $httpRequest, RequestModel $request)
    {
        $validStatuses = ['loaded', 'flight_received', 'delivered'];
        
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'This request cannot be marked as served. Current status: ' . $request->status);
        }
        
        // Validate usage data
        $validated = $httpRequest->validate([
            'items' => 'required|array',
            'items.*.quantity_used' => 'required|integer|min:0',
            'items.*.usage_notes' => 'nullable|string|max:500',
            'general_notes' => 'nullable|string|max:1000',
        ]);
        
        // Update each item with usage data
        foreach ($validated['items'] as $itemId => $itemData) {
            $item = $request->items()->find($itemId);
            
            if ($item) {
                $quantityUsed = $itemData['quantity_used'];
                $approvedQty = $item->quantity_approved ?? $item->quantity_requested;
                
                // Validate quantity doesn't exceed approved
                if ($quantityUsed > $approvedQty) {
                    return back()->with('error', 'Quantity used cannot exceed approved quantity for ' . $item->product->name);
                }
                
                // Calculate remaining
                $quantityRemaining = $approvedQty - $quantityUsed;
                
                $item->update([
                    'quantity_used' => ($item->quantity_used ?? 0) + $quantityUsed,
                    'quantity_remaining' => $quantityRemaining,
                    'usage_notes' => $itemData['usage_notes'] ?? null,
                ]);
            }
        }
        
        // Check if there are any items remaining
        $totalRemaining = $request->items()->sum('quantity_remaining');
        
        // Update request status based on remaining items
        $newStatus = $totalRemaining > 0 ? 'loaded' : 'served'; // Keep as loaded if items remaining
        
        $request->update([
            'status' => $newStatus,
            'served_by' => auth()->id(),
            'served_at' => now(),
            'notes' => $request->notes ? $request->notes . "\n\n[Service Notes - " . now()->format('Y-m-d H:i') . "] " . $validated['general_notes'] : $validated['general_notes'],
        ]);
        
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($request)
            ->log('Marked request #' . $request->id . ' as served to customers with usage tracking');
        
        return redirect()->route('cabin-crew.dashboard')
            ->with('success', 'Request #' . $request->id . ' has been successfully marked as served to customers! Usage data recorded.');
    }
    
    /**
     * Quick mark as delivered (legacy/simple method)
     */
    public function markDelivered(RequestModel $request)
    {
        // Accept both meal and product requests
        $validStatuses = ['loaded'];
        
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'This request cannot be marked as delivered/served. Current status: ' . $request->status);
        }
        
        // Different completion based on request type
        if ($request->request_type === 'meal') {
            // Meal requests: in_service -> served (complete meal service)
            $request->update([
                'status' => 'served',
                'served_by' => auth()->id(),
                'served_at' => now(),
            ]);
            
            return redirect()->route('cabin-crew.dashboard')
                ->with('success', 'Meal request #' . $request->id . ' has been successfully served to passengers!');
        } else {
            // Product requests: delivered (original flow)
            $request->update([
                'status' => 'delivered',
                'delivered_by' => auth()->id(),
                'delivered_at' => now(),
            ]);
            
            return redirect()->route('cabin-crew.dashboard')
                ->with('success', 'Request #' . $request->id . ' has been successfully marked as delivered!');
        }
    }
    
    public function delivered()
    {
        // Show both delivered products and served meals
        $deliveredRequests = RequestModel::with(['flight', 'requester', 'items.product'])
            ->where(function($query) {
                $query->where('status', 'delivered')
                      ->orWhere('status', 'served'); // meal requests
            })
            ->orderByRaw("COALESCE(served_at, delivered_at) DESC")
            ->paginate(20);
        
        return view('cabin-crew.delivered', compact('deliveredRequests'));
    }
}
