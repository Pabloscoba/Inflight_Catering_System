@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">Products Management</h1>
        <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Monitor and manage system-wide inventory</p>
    </div>
    <div style="display: flex; gap: 12px;">
        @php
            $backRoute = 'inventory-personnel.dashboard';
            $createRoute = 'inventory-personnel.products.create';
            if (auth()->user()->hasRole('Cabin Crew')) {
                $backRoute = 'cabin-crew.dashboard';
                $createRoute = 'cabin-crew.products.create';
            } elseif (auth()->user()->hasRole('Catering Staff')) {
                $backRoute = 'catering-staff.dashboard';
            } elseif (auth()->user()->hasRole('Security Staff')) {
                $backRoute = 'security-staff.dashboard';
            } elseif (auth()->user()->hasRole('Ramp Dispatcher')) {
                $backRoute = 'ramp-dispatcher.dashboard';
            }
        @endphp
        @can('create products')
        <a href="{{ route($createRoute) }}" class="btn-atcl btn-atcl-primary">+ Add New Product</a>
        @endcan
        <a href="{{ route($backRoute) }}" class="btn-atcl btn-atcl-secondary">← Back to Dashboard</a>
    </div>
</div>
</head>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <!-- Stock Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 30px;">
    <div class="card-atcl" style="padding: 24px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">Total Products</div>
        <div style="font-size: 32px; font-weight: 700; color: #1e3a8a;">{{ $products->total() }}</div>
    </div>
    <div class="card-atcl" style="padding: 24px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">In Stock</div>
        <div style="font-size: 32px; font-weight: 700; color: #059669;">{{ $products->filter(fn($p) => $p->quantity_in_stock > $p->reorder_level)->count() }}</div>
        <div style="font-size: 12px; color: #059669; opacity: 0.8;">✓ Ready to use</div>
    </div>
    <div class="card-atcl" style="padding: 24px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">Low Stock</div>
        <div style="font-size: 32px; font-weight: 700; color: #d97706;">{{ $products->filter(fn($p) => $p->quantity_in_stock <= $p->reorder_level && $p->quantity_in_stock > 0)->count() }}</div>
        <div style="font-size: 12px; color: #d97706; opacity: 0.8;">⚠️ Need restocking</div>
    </div>
    <div class="card-atcl" style="padding: 24px; display: flex; flex-direction: column; gap: 8px;">
        <div style="font-size: 14px; color: #6b7280; font-weight: 500;">Out of Stock</div>
        <div style="font-size: 32px; font-weight: 700; color: #dc2626;">{{ $products->filter(fn($p) => $p->quantity_in_stock == 0)->count() }}</div>
        <div style="font-size: 12px; color: #dc2626; opacity: 0.8;">❌ Urgent action needed</div>
    </div>
</div>
        
        <form method="GET" action="{{ route('inventory-personnel.products.index') }}" class="card-atcl" style="padding: 20px; margin-bottom: 24px; display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end;">
    <div style="flex: 1; min-width: 250px;">
        <label class="label-atcl">Search Products</label>
        <input type="text" name="search" class="input-atcl" placeholder="Product name, SKU..." value="{{ request('search') }}">
    </div>
    <div style="flex: 1; min-width: 150px;">
        <label class="label-atcl">Category</label>
        <select name="category" class="input-atcl">
            <option value="">All Categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div style="flex: 1; min-width: 150px;">
        <label class="label-atcl">Stock Status</label>
        <select name="stock_status" class="input-atcl">
            <option value="">All Stock</option>
            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>
    </div>
    <div style="display: flex; gap: 8px;">
        <button type="submit" class="btn-atcl btn-atcl-primary" style="height: 44px;">Apply Filters</button>
        @if(request()->hasAny(['search', 'category', 'stock_status', 'is_active']))
        <a href="{{ route('inventory-personnel.products.index') }}" class="btn-atcl btn-atcl-secondary" style="height: 44px; display: flex; align-items: center;">Clear</a>
        @endif
    </div>
</form>
        
        <div class="card-atcl" style="overflow: hidden;">
            @if($products->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background: white;">
                        @foreach($products as $product)
                        <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                            <td style="padding: 16px;">
                                <div style="font-weight: 600; color: #111827; margin-bottom: 4px;">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $product->sku }}</div>
                            </td>
                            <td style="padding: 16px;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; background: #f3f4f6; color: #4b5563;">{{ $product->category->name }}</span>
                            </td>
                            <td style="padding: 16px; color: #4b5563;">{{ $product->sku }}</td>
                            <td style="padding: 16px; font-weight: 500; color: #111827;">TZS {{ number_format($product->unit_price, 2) }}</td>
                            <td style="padding: 16px;">
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $product->quantity_in_stock <= 0 ? '#ef4444' : ($product->quantity_in_stock <= $product->reorder_level ? '#f59e0b' : '#10b981') }}"></div>
                                        <strong style="font-size: 16px; color: {{ $product->quantity_in_stock <= 0 ? '#ef4444' : ($product->quantity_in_stock <= $product->reorder_level ? '#d97706' : '#059669') }}">
                                            {{ $product->quantity_in_stock }}
                                        </strong>
                                        <span style="color: #6b7280; font-size: 13px;">{{ $product->unit_of_measure }}</span>
                                    </div>
                                    @if($product->quantity_in_stock <= 0)
                                        <span style="font-size: 11px; font-weight: 700; color: #ef4444; text-transform: uppercase;">OUT OF STOCK</span>
                                    @elseif($product->quantity_in_stock <= $product->reorder_level)
                                        <span style="font-size: 11px; font-weight: 700; color: #d97706; text-transform: uppercase;">LOW STOCK</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; background: {{ $product->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $product->is_active ? '#065f46' : '#991b1b' }}; width: fit-content;">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; gap: 8px;">
                                    @if($product->status === 'approved' && $product->is_active && $product->quantity_in_stock == 0)
                                        <a href="{{ route('inventory-personnel.products.add-stock', $product) }}" class="btn-atcl btn-atcl-primary" style="height: 32px; padding: 0 12px; font-size: 12px;" title="Add initial stock">📦 Stock</a>
                                    @endif
                                    <a href="{{ route('inventory-personnel.products.edit', $product) }}" class="btn-atcl btn-atcl-primary" style="height: 32px; padding: 0 12px; font-size: 12px;">Edit</a>
                                    <form method="POST" action="{{ route('inventory-personnel.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-atcl btn-atcl-danger" style="height: 32px; padding: 0 12px; font-size: 12px;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
            <div class="pagination">
                @if($products->onFirstPage())
                <span>Previous</span>
                @else
                <a href="{{ $products->previousPageUrl() }}">Previous</a>
                @endif
                <span class="active">{{ $products->currentPage() }}</span>
                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">Next</a>
                @else
                <span>Next</span>
                @endif
            </div>
            @endif
            @else
            <div class="empty-state">
                <p>No products found. {{ request()->hasAny(['search', 'category', 'stock_status', 'is_active']) ? 'Try adjusting your filters.' : 'Add your first product!' }}</p>
            </div>
            @endif
        </div>
    </div>
@endsection