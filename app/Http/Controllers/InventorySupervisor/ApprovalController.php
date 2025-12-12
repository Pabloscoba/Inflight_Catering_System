<?php

namespace App\Http\Controllers\InventorySupervisor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProductApprovedNotification;
use App\Notifications\StockMovementApprovedNotification;

class ApprovalController extends Controller
{
    /**
     * Approve a product
     */
    public function approveProduct(Product $product)
    {
        $product->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        // Notify the creator (Inventory Personnel)
        if ($product->createdBy) {
            $product->createdBy->notify(new ProductApprovedNotification($product));
        }

        return back()->with('success', 'Product approved successfully!');
    }

    /**
     * Reject a product
     */
    public function rejectProduct(Product $product)
    {
        $product->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Product rejected.');
    }

    /**
     * Approve a stock movement
     */
    public function approveMovement(StockMovement $movement)
    {
        DB::transaction(function () use ($movement) {
            // Update movement status
            $movement->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Now update the product stock based on movement type
            $product = $movement->product;

            if ($movement->type === 'incoming') {
                // Add stock
                $product->increment('quantity_in_stock', $movement->quantity);
            } elseif ($movement->type === 'issued') {
                // Remove stock
                $product->decrement('quantity_in_stock', $movement->quantity);
            } elseif ($movement->type === 'returned') {
                // Add stock only if condition is good (check notes)
                if (str_contains($movement->notes, 'Condition: good')) {
                    $product->increment('quantity_in_stock', $movement->quantity);
                }
            } elseif ($movement->type === 'transfer_to_catering') {
                // Transfer from main inventory to catering mini stock
                $product->decrement('quantity_in_stock', $movement->quantity);
                $product->increment('catering_stock', $movement->quantity);
            }
            
            // Notify the creator (Inventory Personnel)
            if ($movement->user) {
                $movement->user->notify(new StockMovementApprovedNotification($movement));
            }
        });

        return back()->with('success', 'Stock movement approved and inventory updated!');
    }

    /**
     * Reject a stock movement
     */
    public function rejectMovement(StockMovement $movement)
    {
        $movement->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Do NOT update stock when rejecting

        return back()->with('success', 'Stock movement rejected.');
    }

    /**
     * Show pending products for approval
     */
    public function pendingProducts()
    {
        // Get products with pending status OR products without approved_by (legacy products)
        $products = Product::with(['category', 'approvedBy'])
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhereNull('approved_by');
            })
            ->latest()
            ->paginate(20);

        return view('inventory-supervisor.approvals.products', compact('products'));
    }

    /**
     * Show pending stock movements for approval
     */
    public function pendingMovements()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('inventory-supervisor.approvals.movements', compact('movements'));
    }
}
