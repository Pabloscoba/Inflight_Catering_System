<?php

namespace App\Http\Controllers\SecurityStaff;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Security authenticates the forwarded request and issues stock
     */
    public function authenticateRequest(RequestModel $request)
    {
        if ($request->status !== 'sent_to_security') {
            return back()->with('error', 'This request is not awaiting security authentication.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Issue stock from main inventory after security approval
            foreach ($request->items as $item) {
                $qty = $item->quantity_approved ?? $item->quantity_requested;
                if ($qty <= 0) continue;

                \App\Models\StockMovement::create([
                    'type' => 'issued',
                    'product_id' => $item->product_id,
                    'quantity' => $qty,
                    'reference_number' => 'REQ-' . $request->id . ' / ' . $request->flight->flight_number,
                    'notes' => 'Issued after security authentication',
                    'user_id' => auth()->id(),
                    'movement_date' => now(),
                ]);

                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->decrement('quantity_in_stock', $qty);
                }
            }

            // Forward to Catering Incharge
            $request->update([
                'status' => 'security_approved',
                'security_approved_by' => auth()->id(),
                'security_approved_at' => now(),
            ]);
        });

        return redirect()->route('security-staff.dashboard')->with('success', 'Request authenticated and stock issued. Forwarded to Catering Incharge.');
    }

    // List requests awaiting security authentication
    public function index()
    {
        $requests = RequestModel::with(['flight','requester','items.product'])
            ->where('status', 'sent_to_security')
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
