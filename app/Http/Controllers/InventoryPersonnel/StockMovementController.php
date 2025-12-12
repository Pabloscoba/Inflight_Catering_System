<?php

namespace App\Http\Controllers\InventoryPersonnel;

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

        return view('inventory-personnel.stock-movements.index', compact('movements', 'products'));
    }

    // Export stock movements to PDF
    public function exportPDF(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])
            ->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('search')) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        $movements = $query->get();
        $products = Product::all();
        
        $data = [
            'movements' => $movements,
            'filters' => [
                'type' => $request->type,
                'product_id' => $request->product_id,
                'search' => $request->search,
            ],
            'products' => $products,
            'generated_at' => now()->format('F d, Y h:i A'),
            'generated_by' => auth()->user()->name,
        ];

        return view('inventory-personnel.stock-movements.pdf', $data);
    }

    // Show incoming stock form
    public function incomingForm()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory-personnel.stock-movements.incoming', compact('products'));
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

        // Create stock movement record with pending status
        $movement = StockMovement::create([
            'type' => 'incoming',
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'movement_date' => $request->movement_date,
            'status' => 'pending', // Needs supervisor approval
        ]);

        // DO NOT update product stock yet - wait for approval
        
        // Notify Inventory Supervisor
        $supervisors = User::role('Inventory Supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new StockMovementCreatedNotification($movement));
        }

        return redirect()->route('inventory-personnel.stock-movements.index')
            ->with('success', 'Incoming stock recorded and pending approval.');
    }

    // Show transfer to catering form
    public function transferToCateringForm()
    {
        $products = Product::where('quantity_in_stock', '>', 0)->orderBy('name')->get();
        return view('inventory-personnel.stock-movements.transfer-to-catering', compact('products'));
    }

    // Store transfer to catering
    public function storeTransferToCatering(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if enough stock available in main inventory
        if ($product->quantity_in_stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock in main inventory. Available: ' . $product->quantity_in_stock
            ])->withInput();
        }

        // Create stock movement record - transfer to catering
        StockMovement::create([
            'type' => 'transfer_to_catering',
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'movement_date' => $request->movement_date,
            'status' => 'pending', // Needs supervisor approval
        ]);

        // DO NOT transfer stock yet - wait for supervisor approval

        return redirect()->route('inventory-personnel.stock-movements.index')
            ->with('success', 'Transfer to catering recorded and pending supervisor approval.');
    }

    // Show issue stock form
    public function issueForm()
    {
        $products = Product::where('quantity_in_stock', '>', 0)->orderBy('name')->get();
        return view('inventory-personnel.stock-movements.issue', compact('products'));
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

        // Create stock movement record with pending status
        StockMovement::create([
            'type' => 'issued',
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
            'movement_date' => $request->movement_date,
            'status' => 'pending', // Needs supervisor approval
        ]);

        // DO NOT decrease product stock yet - wait for approval

        return redirect()->route('inventory-personnel.stock-movements.index')
            ->with('success', 'Stock issue recorded and pending approval.');
    }

    // Show returns form
    public function returnsForm()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory-personnel.stock-movements.returns', compact('products'));
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

        // Create stock movement record with pending status
        StockMovement::create([
            'type' => 'returned',
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes . ' (Condition: ' . $request->condition . ')',
            'user_id' => auth()->id(),
            'movement_date' => $request->movement_date,
            'status' => 'pending', // Needs supervisor approval
        ]);

        // DO NOT update stock yet - wait for approval
        // Stock will only be updated when supervisor approves

        return redirect()->route('inventory-personnel.stock-movements.index')
            ->with('success', 'Returns recorded and pending approval.');
    }
}
