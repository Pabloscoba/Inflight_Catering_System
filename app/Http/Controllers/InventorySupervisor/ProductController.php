<?php

namespace App\Http\Controllers\InventorySupervisor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display pending products awaiting approval
     */
    public function index()
    {
        $products = Product::with(['category', 'approvedBy'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('inventory-supervisor.products.index', compact('products'));
    }

    /**
     * Display all products with filters and trends
     */
    public function all(Request $request)
    {
        // Base query
        $query = Product::with(['category']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', 'approved')->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('status', 'approved')->where('is_active', false);
            } elseif ($request->status == 'pending') {
                $query->where('status', 'pending');
            }
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock_level')) {
            if ($request->stock_level == 'out') {
                $query->where('quantity_in_stock', 0);
            } elseif ($request->stock_level == 'low') {
                $query->whereColumn('quantity_in_stock', '<=', 'reorder_level')
                     ->where('quantity_in_stock', '>', 0);
            } elseif ($request->stock_level == 'good') {
                $query->whereColumn('quantity_in_stock', '>', 'reorder_level');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Get products with issues count
        $products = $query->withCount([
            'stockMovements as issues_count' => function($q) {
                $q->where('type', 'issued')
                  ->where('created_at', '>=', now()->subDays(30));
            }
        ])->paginate(20);

        // Calculate trends (compare last 30 days vs previous 30 days)
        foreach ($products as $product) {
            $last30 = StockMovement::where('product_id', $product->id)
                ->where('type', 'issued')
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
            
            $previous30 = StockMovement::where('product_id', $product->id)
                ->where('type', 'issued')
                ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
                ->count();
            
            if ($previous30 > 0) {
                $product->trend = round((($last30 - $previous30) / $previous30) * 100);
            } else {
                $product->trend = $last30 > 0 ? 100 : 0;
            }
        }

        // Statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'approved')->where('is_active', true)->count();
        $lowStockCount = Product::where('status', 'approved')
            ->whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->where('quantity_in_stock', '>', 0)
            ->count();
        $outOfStockCount = Product::where('status', 'approved')
            ->where('quantity_in_stock', 0)
            ->count();
        
        // Calculate total stock value (sum of quantity * unit_price for all products)
        $totalStockValue = Product::where('status', 'approved')
            ->selectRaw('SUM(quantity_in_stock * unit_price) as total_value')
            ->value('total_value') ?? 0;
        
        // Issues in last 30 days
        $issuesLast30Days = StockMovement::where('type', 'issued')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $categories = Category::all();

        return view('inventory-supervisor.products.all', compact(
            'products',
            'categories',
            'totalProducts',
            'activeProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalStockValue',
            'issuesLast30Days'
        ));
    }

    /**
     * Toggle product active/inactive status
     */
    public function toggleActive(Product $product)
    {
        if ($product->status !== 'approved') {
            return back()->with('error', 'Only approved products can be activated/deactivated');
        }

        $product->update([
            'is_active' => !$product->is_active
        ]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Product '{$product->name}' has been {$status} successfully");
    }

    /**
     * Approve a product
     */
    public function approve(Product $product)
    {
        if ($product->status !== 'pending') {
            return back()->with('error', 'This product has already been processed');
        }

        $product->update([
            'status' => 'approved',
            'is_active' => true, // Automatically activate when approved
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Product approved and activated successfully');
    }

    /**
     * Reject a product
     */
    public function reject(Request $request, Product $product)
    {
        if ($product->status !== 'pending') {
            return back()->with('error', 'This product has already been processed');
        }

        $product->update([
            'status' => 'rejected',
            'is_active' => false,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Product rejected successfully');
    }
}
