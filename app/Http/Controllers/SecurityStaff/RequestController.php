<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Notifications\RequestAuthenticatedNotification;
use App\Notifications\RequestLoadedNotification;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Security authenticates/dispatches request (different handling for meal vs product)
     */
    public function authenticateRequest(RequestModel $request)
    {
        // Check if awaiting security action
        $validStatuses = ['sent_to_security', 'catering_approved']; // catering_approved = meal requests
        if (!in_array($request->status, $validStatuses)) {
            return back()->with('error', 'This request is not awaiting security action.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            if ($request->request_type === 'meal') {
                // MEAL REQUEST: Dispatch to Ramp (NO stock issuance)
                $request->update([
                    'status' => 'security_dispatched',
                    'security_dispatched_by' => auth()->id(),
                    'security_dispatched_at' => now(),
                ]);
            } else {
                // PRODUCT REQUEST: Authenticate, issue stock, create catering stock
                foreach ($request->items as $item) {
                    $qty = $item->quantity_approved ?? $item->quantity_requested;
                    if ($qty <= 0) continue;

                    // Issue from main inventory
                    \App\Models\StockMovement::create([
                        'type' => 'issued',
                        'product_id' => $item->product_id,
                        'quantity' => $qty,
                        'reference_number' => 'REQ-' . $request->id . ' / ' . $request->flight->flight_number,
                        'notes' => 'Issued after security authentication',
                        'user_id' => auth()->id(),
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'movement_date' => now(),
                    ]);

                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('quantity_in_stock', $qty);
                    }

                    // Create catering stock (approved and available)
                    \App\Models\CateringStock::create([
                        'product_id' => $item->product_id,
                        'quantity_received' => $qty,
                        'quantity_available' => $qty,
                        'reference_number' => 'REQ-' . $request->id,
                        'notes' => 'Authenticated by Security, approved by Catering Incharge',
                        'received_by' => auth()->id(),
                        'catering_incharge_id' => $request->catering_approved_by,
                        'status' => 'approved',
                        'received_date' => now(),
                        'approved_date' => now(),
                    ]);
                }

                // Mark as approved and ready for Catering Staff
                $request->update([
                    'status' => 'catering_approved',
                    'security_approved_by' => auth()->id(),
                    'security_approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'approved_date' => now(),
                ]);
            }
        });

        $message = ($request->request_type === 'meal') 
            ? 'Meal request dispatched to Ramp Agent.' 
            : 'Product request authenticated, stock issued and now available to Catering Staff.';

        return redirect()->route('security-staff.dashboard')->with('success', $message);
    }

    // List requests awaiting security authentication/dispatch
    public function index()
    {
        // Show TWO types:
        // 1. Product requests (sent_to_security) - Need authentication & stock issuance
        // 2. Meal requests (catering_approved) - Need dispatch only
        $requests = RequestModel::with(['flight','requester','items.product'])
            ->where(function($query) {
                $query->where('status', 'sent_to_security')
                      ->orWhere(function($q) {
                          $q->where('request_type', 'meal')
                            ->where('status', 'catering_approved');
                      });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('security-staff.requests.awaiting-authentication', compact('requests'));
    }

    // View request details for security review
    public function show(RequestModel $request)
    {
        $request->load(['flight', 'requester', 'items.product.category', 'approver']);
        
        return view('security-staff.requests.show', compact('request'));
    }
}
