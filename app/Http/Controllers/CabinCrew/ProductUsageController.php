<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\AdditionalProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductUsageController extends Controller
{
    /**
     * Display usage tracking index
     */
    public function index()
    {
        $requests = RequestModel::with(['flight', 'items.product'])
            ->whereIn('status', ['loaded', 'delivered', 'served'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('cabin-crew.usage.index', compact('requests'));
    }
    
    /**
     * Display returns management index
     */
    public function returnsIndex()
    {
        $requests = RequestModel::with(['flight', 'items.product'])
            ->where('status', 'loaded')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('cabin-crew.returns.index', compact('requests'));
    }
    
    // View detailed product list for a request
    public function viewProducts(RequestModel $request)
    {
        // Verify request is loaded (ready for cabin crew) - NEW WORKFLOW
        if (!in_array($request->status, ['loaded', 'delivered', 'served'])) {
            return redirect()->route('cabin-crew.dashboard')
                ->with('error', 'This request is not available for product management. Current status: ' . $request->status);
        }

        $request->load(['flight', 'items.product', 'requester']);
        
        return view('cabin-crew.products.view', compact('request'));
    }

    // Mark product quantity as used
    public function markAsUsed(Request $request, RequestItem $item)
    {
        $request->validate([
            'quantity_used' => 'required|integer|min:0',
            'usage_notes' => 'nullable|string|max:500',
        ]);

        $quantityUsed = $request->quantity_used;
        $currentUsed = $item->quantity_used ?? 0;
        $totalUsed = $currentUsed + $quantityUsed;
        
        // Validate not exceeding approved quantity
        if ($totalUsed > ($item->quantity_approved ?? $item->quantity_requested)) {
            return back()->with('error', 'Cannot use more than approved quantity.');
        }

        // Calculate remaining
        $quantityRemaining = ($item->quantity_approved ?? $item->quantity_requested) - $totalUsed - ($item->quantity_defect ?? 0);

        $item->update([
            'quantity_used' => $totalUsed,
            'quantity_remaining' => $quantityRemaining,
            'usage_notes' => $request->usage_notes,
        ]);

        return back()->with('success', 'Product usage recorded successfully!');
    }

    // Record defect products
    public function recordDefect(Request $request, RequestItem $item)
    {
        $request->validate([
            'quantity_defect' => 'required|integer|min:1',
            'defect_notes' => 'required|string|max:500',
        ]);

        $quantityDefect = $request->quantity_defect;
        $currentDefect = $item->quantity_defect ?? 0;
        $totalDefect = $currentDefect + $quantityDefect;
        
        // Validate not exceeding approved quantity
        $maxQuantity = ($item->quantity_approved ?? $item->quantity_requested);
        if ($totalDefect + ($item->quantity_used ?? 0) > $maxQuantity) {
            return back()->with('error', 'Total defect and used cannot exceed approved quantity.');
        }

        // Calculate remaining
        $quantityRemaining = $maxQuantity - ($item->quantity_used ?? 0) - $totalDefect;

        $item->update([
            'quantity_defect' => $totalDefect,
            'quantity_remaining' => $quantityRemaining,
            'defect_notes' => $request->defect_notes,
        ]);

        return back()->with('success', 'Defect product recorded successfully!');
    }

    // Request additional products
    public function requestAdditional(RequestModel $requestModel)
    {
        $requestModel->load('flight');
        
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('name')->get();
        
        return view('cabin-crew.products.request-additional', compact('requestModel', 'products'));
    }

    public function storeAdditionalRequest(Request $request, RequestModel $requestModel)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'meal_type' => 'nullable|in:breakfast,lunch,dinner,snack,VIP_meal,special_meal,non_meal',
            'quantity_requested' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        AdditionalProductRequest::create([
            'original_request_id' => $requestModel->id,
            'requested_by' => auth()->id(),
            'product_id' => $request->product_id,
            'meal_type' => $request->meal_type,
            'quantity_requested' => $request->quantity_requested,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('cabin-crew.products.view', $requestModel)
            ->with('success', 'Additional product request submitted successfully!');
    }

    // Generate usage report
    public function generateReport(RequestModel $request)
    {
        $request->load(['flight', 'items.product', 'requester']);
        
        // Calculate totals
        $totalUsed = $request->items->sum('quantity_used');
        $totalDefect = $request->items->sum('quantity_defect');
        $totalRemaining = $request->items->sum('quantity_remaining');
        $totalApproved = $request->items->sum(function($item) {
            return $item->quantity_approved ?? $item->quantity_requested;
        });
        
        return view('cabin-crew.products.report', compact(
            'request',
            'totalUsed',
            'totalDefect',
            'totalRemaining',
            'totalApproved'
        ));
    }
}
