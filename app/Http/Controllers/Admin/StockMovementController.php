<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    // Display stock movement history
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Search by reference number
        if ($request->filled('search')) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        $movements = $query->paginate(20);
        $products = Product::orderBy('name')->get();

        return view('admin.stock-movements.index', compact('movements', 'products'));
    }

    // Show incoming stock form
    public function incomingForm()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.stock-movements.incoming', compact('products'));
    }

    // Store incoming stock
    public function storeIncoming(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            // Create stock movement record
            StockMovement::create([
                'type' => 'incoming',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
                'movement_date' => $request->movement_date,
            ]);

            // Update product stock
            $product = Product::findOrFail($request->product_id);
            $product->increment('quantity_in_stock', $request->quantity);
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Incoming stock recorded successfully.');
    }

    // Show issue stock form
    public function issueForm()
    {
        $products = Product::where('quantity_in_stock', '>', 0)->orderBy('name')->get();
        return view('admin.stock-movements.issue', compact('products'));
    }

    // Store issued stock
    public function storeIssue(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if enough stock available
        if ($product->quantity_in_stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock. Available: ' . $product->quantity_in_stock
            ])->withInput();
        }

        DB::transaction(function () use ($request, $product) {
            // Create stock movement record
            StockMovement::create([
                'type' => 'issued',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
                'movement_date' => $request->movement_date,
            ]);

            // Decrease product stock
            $product->decrement('quantity_in_stock', $request->quantity);
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock issued successfully.');
    }

    // Show returns form
    public function returnsForm()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.stock-movements.returns', compact('products'));
    }

    // Store returned stock
    public function storeReturns(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
            'condition' => 'required|in:good,damaged',
        ]);

        DB::transaction(function () use ($request) {
            // Create stock movement record
            StockMovement::create([
                'type' => 'returned',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes . ' (Condition: ' . $request->condition . ')',
                'user_id' => auth()->id(),
                'movement_date' => $request->movement_date,
            ]);

            // Only increase stock if items are in good condition
            if ($request->condition === 'good') {
                $product = Product::findOrFail($request->product_id);
                $product->increment('quantity_in_stock', $request->quantity);
            }
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Returns recorded successfully.');
    }
}
