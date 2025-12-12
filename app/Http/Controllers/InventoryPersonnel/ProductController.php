<?php

namespace App\Http\Controllers\InventoryPersonnel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ProductCreatedNotification;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category'); // Show all products with status

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('quantity_in_stock', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereColumn('quantity_in_stock', '<=', 'reorder_level')
                          ->where('quantity_in_stock', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('quantity_in_stock', '<=', 0);
                    break;
            }
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('inventory-personnel.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        return view('inventory-personnel.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'in:TZS,USD,EUR,GBP,KES,UGX'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['status'] = 'pending'; // All new products need approval

        $product = Product::create($validated);
        
        // Notify Inventory Supervisor
        $supervisors = User::role('Inventory Supervisor')->get();
        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new ProductCreatedNotification($product));
        }

        return redirect()->route('inventory-personnel.products.index')->with('success', 'Product created successfully and pending approval');
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('inventory-personnel.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'in:TZS,USD,EUR,GBP,KES,UGX'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('inventory-personnel.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory-personnel.products.index')->with('success', 'Product deleted successfully');
    }
}
