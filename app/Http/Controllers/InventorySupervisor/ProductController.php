<?php

namespace App\Http\Controllers\InventorySupervisor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
     * Approve a product
     */
    public function approve(Product $product)
    {
        if ($product->status !== 'pending') {
            return back()->with('error', 'This product has already been processed');
        }

        $product->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Product approved successfully');
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
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Product rejected successfully');
    }
}
