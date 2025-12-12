<?php

namespace App\Http\Controllers\RampDispatcher;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display returns received from Cabin Crew
     */
    public function index()
    {
        // Pending returns from Cabin Crew
        $pendingReturns = ProductReturn::with(['request.flight', 'product', 'returnedBy'])
            ->where('status', 'pending_ramp')
            ->latest('returned_at')
            ->get();
        
        // Returns forwarded to Security
        $forwardedReturns = ProductReturn::with(['request.flight', 'product', 'returnedBy', 'verifiedBy'])
            ->where('received_by', auth()->id())
            ->whereIn('status', ['pending_security', 'authenticated'])
            ->latest('received_at')
            ->take(20)
            ->get();
        
        return view('ramp-dispatcher.returns.index', compact('pendingReturns', 'forwardedReturns'));
    }

    /**
     * Receive return and forward to Security
     */
    public function receive(ProductReturn $return)
    {
        if ($return->status !== 'pending_ramp') {
            return back()->with('error', 'This return has already been processed.');
        }

        $return->update([
            'status' => 'pending_security',
            'received_by' => auth()->id(),
            'received_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($return)
            ->log('Ramp Dispatcher received return and forwarded to Security');

        return back()->with('success', 'Return received and forwarded to Security for authentication.');
    }

    /**
     * Bulk receive multiple returns
     */
    public function bulkReceive(Request $request)
    {
        $request->validate([
            'return_ids' => 'required|array|min:1',
            'return_ids.*' => 'exists:product_returns,id',
        ]);

        $received = 0;

        foreach ($request->return_ids as $returnId) {
            $productReturn = ProductReturn::find($returnId);
            
            if ($productReturn && $productReturn->status === 'pending_ramp') {
                $productReturn->update([
                    'status' => 'pending_security',
                    'received_by' => auth()->id(),
                    'received_at' => now(),
                ]);
                $received++;
            }
        }

        activity()
            ->causedBy(auth()->user())
            ->log("Ramp Dispatcher received {$received} return(s) and forwarded to Security");

        return back()->with('success', "Successfully received {$received} return(s) and forwarded to Security.");
    }
}
