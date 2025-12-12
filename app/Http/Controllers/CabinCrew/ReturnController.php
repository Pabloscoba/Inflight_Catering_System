<?php

namespace App\Http\Controllers\CabinCrew;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\ProductReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display returns management page
     */
    public function index()
    {
        // Show requests that have been delivered/served (can return items)
        $requests = RequestModel::with(['flight', 'items.product'])
            ->whereIn('status', ['loaded', 'flight_received', 'delivered', 'served'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Show active returns (not yet verified)
        $activeReturns = ProductReturn::with(['request.flight', 'product', 'returnedBy'])
            ->where('returned_by', auth()->id())
            ->whereIn('status', ['pending_ramp', 'received_by_ramp', 'pending_security'])
            ->latest()
            ->get();
        
        // Show completed returns
        $completedReturns = ProductReturn::with(['request.flight', 'product', 'verifiedBy'])
            ->where('returned_by', auth()->id())
            ->where('status', 'authenticated')
            ->latest()
            ->take(10)
            ->get();
        
        return view('cabin-crew.returns.index', compact('requests', 'activeReturns', 'completedReturns'));
    }

    /**
     * Show form to create return for a specific request
     */
    public function create(RequestModel $request)
    {
        // Verify request has been delivered
        if (!in_array($request->status, ['loaded', 'flight_received', 'delivered', 'served'])) {
            return redirect()->route('cabin-crew.returns.index')
                ->with('error', 'This request is not eligible for returns.');
        }

        $request->load(['flight', 'items.product']);
        
        // Prepare products data for JavaScript
        $products = $request->items->map(function($item) {
            $approved = $item->quantity_approved ?? $item->quantity_requested;
            $used = $item->quantity_used ?? 0;
            return [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'max_return' => max(0, $approved - $used)
            ];
        });
        
        return view('cabin-crew.returns.create', compact('request', 'products'));
    }

    /**
     * Submit return items
     */
    public function store(Request $httpRequest, RequestModel $requestModel)
    {
        $httpRequest->validate([
            'returns' => 'required|array|min:1',
            'returns.*.product_id' => 'required|exists:products,id',
            'returns.*.quantity_returned' => 'required|integer|min:1',
            'returns.*.condition' => 'required|in:good,damaged,expired',
            'returns.*.reason' => 'nullable|string|max:500',
        ]);

        $returnsCreated = 0;

        foreach ($httpRequest->returns as $returnData) {
            // Verify product was in the original request
            $requestItem = $requestModel->items()
                ->where('product_id', $returnData['product_id'])
                ->first();
            
            if (!$requestItem) {
                continue; // Skip invalid items
            }

            // Verify quantity doesn't exceed what was approved
            $maxQuantity = $requestItem->quantity_approved ?? $requestItem->quantity_requested;
            $quantityUsed = $requestItem->quantity_used ?? 0;
            $maxReturn = $maxQuantity - $quantityUsed;

            if ($returnData['quantity_returned'] > $maxReturn) {
                continue; // Skip excessive quantities
            }

            ProductReturn::create([
                'request_id' => $requestModel->id,
                'product_id' => $returnData['product_id'],
                'quantity_returned' => $returnData['quantity_returned'],
                'condition' => $returnData['condition'],
                'reason' => $returnData['reason'] ?? null,
                'status' => 'pending_ramp',
                'returned_by' => auth()->id(),
                'returned_at' => now(),
            ]);

            $returnsCreated++;
        }

        if ($returnsCreated === 0) {
            return back()->with('error', 'No valid items were returned.');
        }
        
        // Notify Ramp Dispatcher
        $rampDispatchers = User::role('Ramp Dispatcher')->get();
        foreach ($rampDispatchers as $dispatcher) {
            // Get last created return for notification
            $lastReturn = ProductReturn::where('returned_by', auth()->id())
                ->where('request_id', $requestModel->id)
                ->latest()
                ->first();
            if ($lastReturn) {
                $dispatcher->notify(new ProductReturnInitiatedNotification($lastReturn));
            }
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($requestModel)
            ->withProperties(['returns_count' => $returnsCreated])
            ->log('Cabin Crew initiated return of ' . $returnsCreated . ' item(s)');

        return redirect()->route('cabin-crew.returns.index')
            ->with('success', "Successfully initiated return of {$returnsCreated} item(s). Forwarded to Ramp Dispatcher.");
    }

    /**
     * View return details
     */
    public function show(ProductReturn $return)
    {
        // Verify ownership
        if ($return->returned_by !== auth()->id()) {
            abort(403, 'Unauthorized access to this return.');
        }

        $return->load(['request.flight', 'product', 'returnedBy', 'receivedBy', 'verifiedBy']);
        
        return view('cabin-crew.returns.show', compact('return'));
    }
}
