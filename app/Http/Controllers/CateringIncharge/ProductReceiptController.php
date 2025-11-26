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
     * View all approved catering stock
     */
    public function stockOverview()
    {
        $stocks = CateringStock::with(['product.category'])
            ->where('status', 'approved')
            ->where('quantity_available', '>', 0)
            ->orderBy('product_id')
            ->paginate(50);

        // Group by product and sum quantities
        $stockSummary = CateringStock::with(['product.category'])
            ->where('status', 'approved')
            ->select('product_id', DB::raw('SUM(quantity_available) as total_available'), DB::raw('SUM(quantity_received) as total_received'))
            ->groupBy('product_id')
            ->having('total_available', '>', 0)
            ->get();

        return view('catering-incharge.receipts.stock-overview', compact('stocks', 'stockSummary'));
    }
}
