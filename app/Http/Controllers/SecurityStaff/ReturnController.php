<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProductReturnAuthenticatedNotification;

class ReturnController extends Controller
{
    /**
     * Display returns pending authentication
     */
    public function index()
    {
        // Pending authentication
        $pendingReturns = ProductReturn::with([
            'request.flight',
            'product',
            'returnedBy',
            'receivedBy'
        ])
            ->where('status', 'pending_security')
            ->latest('received_at')
            ->get();
        
        // Recently authenticated
        $authenticatedReturns = ProductReturn::with([
            'request.flight',
            'product',
            'returnedBy',
            'verifiedBy'
        ])
            ->where('verified_by', auth()->id())
            ->where('status', 'authenticated')
            ->latest('verified_at')
            ->take(20)
            ->get();
        
        return view('security-staff.returns.index', compact('pendingReturns', 'authenticatedReturns'));
    }

    /**
     * Authenticate return and adjust stock
     */
    public function authenticate(Request $request, ProductReturn $productReturn)
    {
        if ($productReturn->status !== 'pending_security') {
            return back()->with('error', 'This return has already been processed.');
        }

        $request->validate([
            'verified_quantity' => 'required|integer|min:0',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        $verifiedQuantity = $request->verified_quantity;
        
        // Cannot verify more than was returned
        if ($verifiedQuantity > $productReturn->quantity_returned) {
            return back()->with('error', 'Verified quantity cannot exceed returned quantity.');
        }

        DB::transaction(function () use ($productReturn, $verifiedQuantity, $request) {
            // Update product return status
            $productReturn->update([
                'status' => 'authenticated',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'notes' => $request->verification_notes,
                'quantity_returned' => $verifiedQuantity, // Update with verified quantity
            ]);

            // Only adjust stock for items in good condition
            if ($productReturn->condition === 'good' && $verifiedQuantity > 0) {
                // Create stock movement (returned type)
                StockMovement::create([
                    'product_id' => $productReturn->product_id,
                    'type' => 'returned',
                    'quantity' => $verifiedQuantity,
                    'reference_number' => "RETURN-{$productReturn->id}",
                    'notes' => "Returned from Request #{$productReturn->request_id} - Verified by Security",
                    'user_id' => auth()->id(),
                    'movement_date' => now()->toDateString(),
                ]);

                // Increase main inventory stock
                $product = $productReturn->product;
                $product->increment('quantity_in_stock', $verifiedQuantity);

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($product)
                    ->withProperties([
                        'return_id' => $productReturn->id,
                        'quantity' => $verifiedQuantity,
                        'new_stock' => $product->fresh()->quantity_in_stock
                    ])
                    ->log("Stock increased by {$verifiedQuantity} from authenticated return");
            }
            
            // Notify Cabin Crew who initiated the return
            if ($productReturn->returnedBy) {
                $productReturn->returnedBy->notify(new ProductReturnAuthenticatedNotification($productReturn));
            }
        });

        activity()
            ->causedBy(auth()->user())
            ->performedOn($productReturn)
            ->withProperties(['verified_quantity' => $verifiedQuantity])
            ->log('Security authenticated product return and adjusted stock');

        return back()->with('success', 'Return authenticated successfully. Stock has been adjusted.');
    }

    /**
     * Reject a return
     */
    public function reject(Request $request, ProductReturn $productReturn)
    {
        if ($productReturn->status !== 'pending_security') {
            return back()->with('error', 'This return has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $productReturn->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => "REJECTED: " . $request->rejection_reason,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($productReturn)
            ->withProperties(['reason' => $request->rejection_reason])
            ->log('Security rejected product return');

        return back()->with('success', 'Return has been rejected.');
    }

    /**
     * Bulk authenticate returns
     */
    public function bulkAuthenticate(Request $request)
    {
        $request->validate([
            'return_ids' => 'required|array|min:1',
            'return_ids.*' => 'exists:product_returns,id',
        ]);

        $authenticated = 0;

        DB::transaction(function () use ($request, &$authenticated) {
            foreach ($request->return_ids as $returnId) {
                $productReturn = ProductReturn::find($returnId);
                
                if ($productReturn && $productReturn->status === 'pending_security') {
                    $verifiedQuantity = $productReturn->quantity_returned;

                    $productReturn->update([
                        'status' => 'authenticated',
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                    ]);

                    // Adjust stock if condition is good
                    if ($productReturn->condition === 'good' && $verifiedQuantity > 0) {
                        StockMovement::create([
                            'product_id' => $productReturn->product_id,
                            'type' => 'returned',
                            'quantity' => $verifiedQuantity,
                            'reference_number' => "RETURN-{$productReturn->id}",
                            'notes' => "Bulk return authentication - Request #{$productReturn->request_id}",
                            'user_id' => auth()->id(),
                            'movement_date' => now()->toDateString(),
                        ]);

                        $productReturn->product->increment('quantity_in_stock', $verifiedQuantity);
                    }

                    $authenticated++;
                }
            }
        });

        activity()
            ->causedBy(auth()->user())
            ->log("Security authenticated {$authenticated} return(s) in bulk");

        return back()->with('success', "Successfully authenticated {$authenticated} return(s).");
    }
}
