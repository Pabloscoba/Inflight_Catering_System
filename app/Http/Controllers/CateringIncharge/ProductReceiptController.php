<?php

namespace App\Http\Controllers\CateringIncharge;

use App\Http\Controllers\Controller;
use App\Models\CateringStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReceiptController extends Controller
{
    /**
     * Display pending product receipts from inventory
     */
    public function pendingReceipts()
    {
        $receipts = CateringStock::with(['product.category', 'receivedBy'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('catering-incharge.receipts.pending', compact('receipts'));
    }

    /**
     * Approve product receipt - makes it available to catering staff
     */
    public function approveReceipt(CateringStock $receipt)
    {
        if ($receipt->status !== 'pending') {
            return back()->with('error', 'This receipt has already been processed.');
        }

        DB::transaction(function () use ($receipt) {
            $receipt->update([
                'status' => 'approved',
                'catering_incharge_id' => auth()->id(),
                'approved_date' => now(),
                'quantity_available' => $receipt->quantity_received, // Make all received quantity available
            ]);
        });

        return back()->with('success', 'Product receipt approved! Stock is now available to Catering Staff.');
    }

    /**
     * Reject product receipt
     */
    public function rejectReceipt(Request $request, CateringStock $receipt)
    {
        if ($receipt->status !== 'pending') {
            return back()->with('error', 'This receipt has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $receipt->update([
            'status' => 'rejected',
            'catering_incharge_id' => auth()->id(),
            'approved_date' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Product receipt rejected.');
    }

    /**
     * View all approved catering stock - REAL-TIME from Product table
     */
    public function stockOverview()
    {
        // Real-time catering stock from Product table
        $stocks = Product::with(['category'])
            ->where('is_active', true)
            ->where('catering_stock', '>', 0)
            ->orderBy('name')
            ->paginate(50);

        // Stock summary with low stock indicators
        $stockSummary = Product::with(['category'])
            ->where('is_active', true)
            ->where('catering_stock', '>', 0)
            ->select('id', 'name', 'category_id', 'catering_stock', 'catering_reorder_level', 'unit_of_measure')
            ->orderBy('catering_stock', 'desc')
            ->get();

        // Low stock count
        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('catering_stock', '<=', 'catering_reorder_level')
            ->where('catering_stock', '>', 0)
            ->count();

        // Out of stock count
        $outOfStockCount = Product::where('is_active', true)
            ->where('catering_stock', '=', 0)
            ->count();

        return view('catering-incharge.receipts.stock-overview', compact('stocks', 'stockSummary', 'lowStockCount', 'outOfStockCount'));
    }
}
