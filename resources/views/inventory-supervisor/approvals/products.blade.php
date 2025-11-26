@extends('layouts.app')

@section('title', 'Approve Products')

@section('content')
<div class="content-header">
    <h1>Pending Products for Approval</h1>
    <p>Review and approve products created by Inventory Personnel</p>
</div>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #c3e6cb;">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #f5c6cb;">
    ✗ {{ session('error') }}
</div>
@endif

@if($products->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product Name</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">SKU</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Category</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Price</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Stock</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Reorder Level</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px; color: #666;">{{ $product->sku }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $product->category->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center; font-weight: 600;">
                    {{ $product->currency }} {{ number_format($product->unit_price, 2) }}
                </td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center;">{{ $product->quantity_in_stock }}</td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center;">{{ $product->reorder_level }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <form action="{{ route('inventory-supervisor.approvals.products.approve', $product) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Approve this product?')" style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                ✓ Approve
                            </button>
                        </form>
                        <form action="{{ route('inventory-supervisor.approvals.products.reject', $product) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Reject this product?')" style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                ✗ Reject
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div style="margin-top: 24px;">
        {{ $products->links() }}
    </div>
</div>
@else
<div style="background: white; border-radius: 16px; padding: 60px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <svg style="width: 64px; height: 64px; color: #cbd5e0; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <h3 style="font-size: 20px; color: #4a5568; margin-bottom: 8px;">No Pending Products</h3>
    <p style="color: #718096;">All products have been reviewed!</p>
</div>
@endif

<style>
tr:hover {
    background: #f8f9fa !important;
}

button:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
