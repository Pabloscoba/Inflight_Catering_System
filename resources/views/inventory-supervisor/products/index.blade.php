@extends('layouts.app')

@section('content')
<style>
    .approval-container { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .page-header h1 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .page-header p { color: #6b7280; margin-top: 8px; font-size: 15px; }
    .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; font-weight: 500; transition: all 0.2s; }
    .back-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; }
    .alert-success { background: #d1fae5; border: 1px solid #34d399; color: #065f46; }
    .alert-error { background: #fee2e2; border: 1px solid #f87171; color: #991b1b; }
    .empty-state { background: white; border-radius: 12px; padding: 64px 32px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 16px; }
    .empty-state h3 { font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 8px; }
    .empty-state p { color: #6b7280; }
    .products-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .products-table table { width: 100%; border-collapse: collapse; }
    .products-table thead { background: #f9fafb; }
    .products-table th { padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
    .products-table td { padding: 20px 24px; border-top: 1px solid #f3f4f6; }
    .products-table tbody tr:hover { background: #f9fafb; }
    .product-name { font-size: 15px; font-weight: 600; color: #111827; margin: 0 0 4px; }
    .product-desc { font-size: 13px; color: #6b7280; }
    .sku-badge { display: inline-block; font-family: 'Courier New', monospace; font-size: 13px; font-weight: 600; color: #374151; background: #f3f4f6; padding: 4px 10px; border-radius: 6px; }
    .category-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 13px; font-weight: 500; }
    .price-info { font-size: 15px; font-weight: 600; color: #111827; }
    .stock-info { font-size: 14px; color: #374151; }
    .stock-detail { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .time-ago { font-size: 13px; color: #6b7280; }
    .action-buttons { display: flex; gap: 8px; }
    .btn-approve { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #10b981; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
    .btn-reject:hover { background: #dc2626; }
    .btn-icon { width: 16px; height: 16px; }
</style>

<div class="approval-container">
    <div class="page-header">
        <div>
            <h1>Pending Products Approval</h1>
            <p>Review and approve new products added by Inventory Personnel</p>
        </div>
        <a href="{{ route('inventory-supervisor.dashboard') }}" class="back-btn">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <strong>✓ Success!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <strong>✗ Error!</strong> {{ session('error') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3>No Pending Products</h3>
            <p>All products have been reviewed. Great work!</p>
        </div>
    @else
        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="product-desc">{{ Str::limit($product->description, 60) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="sku-badge">{{ $product->sku }}</span>
                        </td>
                        <td>
                            <span class="category-badge">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="price-info">{{ $product->currency }} {{ number_format($product->unit_price, 2) }}</div>
                            <div class="stock-detail">per {{ $product->unit_of_measure }}</div>
                        </td>
                        <td>
                            <div class="stock-info">{{ number_format($product->quantity_in_stock) }} {{ $product->unit_of_measure }}</div>
                            <div class="stock-detail">Reorder at: {{ $product->reorder_level }}</div>
                        </td>
                        <td>
                            <div class="time-ago">{{ $product->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('inventory-supervisor.products.approve', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-approve">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('inventory-supervisor.products.reject', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-reject">
                                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
        @endif
    @endif
</div>
@endsection
