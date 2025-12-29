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
     * Approve catering request (NEW WORKFLOW - approve and send to Inventory Personnel)
     */
    public function approveRequest(\App\Models\Request $request)
    {
        if ($request->status !== 'catering_approved') {
            return back()->with('error', 'This request is not awaiting Inventory Supervisor approval.');
        }

        DB::transaction(function () use ($request) {
            $request->update([
                'status' => 'supervisor_approved',
                'approved_by' => auth()->id(),
                'approved_date' => now(),
            ]);
            
            // Notify Inventory Personnel
            $inventoryPersonnel = \App\Models\User::role('Inventory Personnel')->get();
            foreach ($inventoryPersonnel as $personnel) {
                $personnel->notify(new \App\Notifications\RequestApprovedNotification($request));
            }
        });

        return back()->with('success', 'Request approved and forwarded to Inventory Personnel for issuing.');
    }

    /**
     * Reject catering request
     */
    public function rejectRequest(Request $httpRequest, \App\Models\Request $request)
    {
        if ($request->status !== 'catering_approved') {
            return back()->with('error', 'This request cannot be rejected at this stage.');
        }

        $httpRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $request->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_date' => now(),
            'rejection_reason' => $httpRequest->rejection_reason,
        ]);

        // Notify requester
        $request->requester->notify(new \App\Notifications\RequestRejectedNotification($request));

        return back()->with('success', 'Request has been rejected.');
    }

    /**
     * Show pending catering requests for approval
     */
    public function pendingRequests()
    {
        $requests = \App\Models\Request::with(['flight', 'requester', 'items.product'])
            ->where('status', 'catering_approved')
            ->latest()
            ->paginate(20);

        return view('inventory-supervisor.approvals.requests', compact('requests'));
    }

    /**
     * Show specific request details
     */
    public function showRequest(\App\Models\Request $request)
    {
        $request->load(['flight', 'requester', 'approver', 'items.product']);
        return view('inventory-supervisor.approvals.request-show', compact('request'));
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
