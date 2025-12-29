@extends('layouts.app')

@section('content')
<style>
    .products-container { max-width: 1600px; margin: 0 auto; padding: 32px 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .page-header h1 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .page-header p { color: #6b7280; margin-top: 8px; font-size: 15px; }
    .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; font-weight: 500; transition: all 0.2s; }
    .back-btn:hover { background: #f9fafb; border-color: #d1d5db; }
    
    /* Stats Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px; }
    .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 24px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .stat-card.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-card.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .stat-label { font-size: 14px; opacity: 0.9; margin-bottom: 8px; }
    .stat-value { font-size: 36px; font-weight: 700; margin-bottom: 4px; }
    .stat-subtext { font-size: 13px; opacity: 0.85; }
    
    /* Filter Bar */
    .filter-bar { background: white; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .filter-group select, .filter-group input { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .filter-btn { padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; }
    .filter-btn:hover { background: #2563eb; }
    
    /* Products Table */
    .products-table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .products-table table { width: 100%; border-collapse: collapse; }
    .products-table thead { background: #f9fafb; }
    .products-table th { padding: 16px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
    .products-table td { padding: 18px 20px; border-top: 1px solid #f3f4f6; }
    .products-table tbody tr:hover { background: #f9fafb; }
    
    .product-name { font-size: 15px; font-weight: 600; color: #111827; margin: 0 0 4px; max-width: 250px; }
    .product-sku { font-family: 'Courier New', monospace; font-size: 12px; color: #6b7280; }
    .category-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .stock-cell { font-size: 14px; font-weight: 600; }
    .stock-good { color: #10b981; }
    .stock-low { color: #f59e0b; }
    .stock-out { color: #ef4444; }
    .stock-detail { font-size: 12px; color: #6b7280; margin-top: 4px; }
    
    /* Status Badge */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    .status-pending { background: #fef3c7; color: #92400e; }
    
    /* Action Buttons */
    .action-buttons { display: flex; gap: 8px; }
    .btn-sm { padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .btn-toggle { background: #3b82f6; color: white; }
    .btn-toggle:hover { background: #2563eb; }
    .btn-toggle.deactivate { background: #ef4444; }
    .btn-toggle.deactivate:hover { background: #dc2626; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-edit:hover { background: #d97706; }
    
    /* Trend Indicators */
    .trend { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; padding: 4px 8px; border-radius: 4px; }
    .trend-up { background: #fee2e2; color: #991b1b; }
    .trend-down { background: #d1fae5; color: #065f46; }
    .trend svg { width: 14px; height: 14px; }
    
    /* Empty State */
    .empty-state { text-align: center; padding: 64px 32px; }
    .empty-state svg { width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 16px; }
    .empty-state h3 { font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 8px; }
    .empty-state p { color: #6b7280; }
</style>

<div class="products-container">
    <div class="page-header">
        <div>
            <h1>All Products Management</h1>
            <p>Manage all products in inventory with activation control and trend monitoring</p>
        </div>
        <a href="{{ route('inventory-supervisor.dashboard') }}" class="back-btn">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-label">Total Products</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-subtext">In Inventory System</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-label">Active Products</div>
            <div class="stat-value">{{ $activeProducts }}</div>
            <div class="stat-subtext">Available for Requests</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-value">{{ $lowStockCount }}</div>
            <div class="stat-subtext">Below Reorder Level</div>
        </div>
        
        <div class="stat-card danger">
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value">{{ $outOfStockCount }}</div>
            <div class="stat-subtext">Requires Restocking</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Total Stock Value</div>
            <div class="stat-value">{{ number_format($totalStockValue) }}</div>
            <div class="stat-subtext">TZS Across All Products</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-label">Total Issues (30 days)</div>
            <div class="stat-value">{{ $issuesLast30Days }}</div>
            <div class="stat-subtext">Products Issued to Catering</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('inventory-supervisor.products.all') }}" class="filter-bar">
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-group">
            <label>Stock Level</label>
            <select name="stock_level">
                <option value="">All Levels</option>
                <option value="out" {{ request('stock_level') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                <option value="low" {{ request('stock_level') == 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="good" {{ request('stock_level') == 'good' ? 'selected' : '' }}>Good Stock</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" placeholder="Product name or SKU..." value="{{ request('search') }}">
        </div>
        
        <div style="align-self: flex-end;">
            <button type="submit" class="filter-btn">Filter</button>
        </div>
    </form>

    <!-- Products Table -->
    @if($products->isEmpty())
        <div class="empty-state" style="background: white; border-radius: 12px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3>No Products Found</h3>
            <p>Try adjusting your filters or add new products to the inventory.</p>
        </div>
    @else
        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Issues (30d)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-sku">SKU: {{ $product->sku }}</div>
                        </td>
                        <td>
                            <span class="category-badge">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #111827;">{{ $product->currency }} {{ number_format($product->unit_price, 2) }}</div>
                            <div class="stock-detail">per {{ $product->unit_of_measure }}</div>
                        </td>
                        <td>
                            @php
                                $stockClass = 'stock-good';
                                if ($product->quantity_in_stock == 0) $stockClass = 'stock-out';
                                elseif ($product->quantity_in_stock <= $product->reorder_level) $stockClass = 'stock-low';
                            @endphp
                            <div class="stock-cell {{ $stockClass }}">
                                {{ number_format($product->quantity_in_stock) }} {{ $product->unit_of_measure }}
                            </div>
                            <div class="stock-detail">Reorder: {{ $product->reorder_level }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #111827;">{{ $product->issues_count ?? 0 }} issues</div>
                            @if(isset($product->trend) && $product->trend != 0)
                                <div class="trend {{ $product->trend > 0 ? 'trend-up' : 'trend-down' }}">
                                    @if($product->trend > 0)
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        +{{ abs($product->trend) }}%
                                    @else
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        {{ $product->trend }}%
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($product->status == 'approved')
                                @if($product->is_active)
                                    <span class="status-badge status-active">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Inactive
                                    </span>
                                @endif
                            @else
                                <span class="status-badge status-pending">Pending Approval</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if($product->status == 'approved')
                                    <form action="{{ route('inventory-supervisor.products.toggle-active', $product) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-sm btn-toggle {{ $product->is_active ? 'deactivate' : '' }}">
                                            @if($product->is_active)
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                Deactivate
                                            @else
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Activate
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('inventory-supervisor.products.index') }}" class="btn-sm btn-edit">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Approve
                                    </a>
                                @endif
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
