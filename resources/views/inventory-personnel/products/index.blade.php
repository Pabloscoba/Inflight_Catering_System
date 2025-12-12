<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 1600px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .header-actions { display: flex; gap: 12px; }
        .btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; border: none; cursor: pointer; display: inline-block; }
        .btn-primary { background: #0066cc; color: #fff; }
        .btn-primary:hover { background: #0052a3; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        
        .filters { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 24px; display: flex; gap: 12px; flex-wrap: wrap; align-items: end; }
        .filter-group { display: flex; flex-direction: column; gap: 6px; }
        .filter-group label { font-size: 13px; font-weight: 600; color: #495057; }
        .filters input, .filters select { padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .filters input { min-width: 250px; }
        .filters select { min-width: 150px; }
        .filters input:focus, .filters select:focus { outline: none; border-color: #0066cc; }
        
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8f9fa; border-bottom: 2px solid #dee2e6; }
        th { padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap; }
        td { padding: 14px 16px; border-bottom: 1px solid #e9ecef; font-size: 14px; color: #495057; }
        tbody tr:hover { background: #f8f9fa; }
        
        .product-info h3 { font-size: 15px; font-weight: 600; color: #000; margin-bottom: 4px; }
        .product-info p { font-size: 12px; color: #6c757d; }
        
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-secondary { background: #e9ecef; color: #495057; }
        
        .stock-indicator { display: flex; align-items: center; gap: 6px; }
        .stock-dot { width: 8px; height: 8px; border-radius: 50%; }
        .stock-good { background: #28a745; }
        .stock-low { background: #ffc107; }
        .stock-out { background: #dc3545; }
        
        .actions { display: flex; gap: 8px; }
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 20px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #495057; font-size: 14px; }
        .pagination a:hover { background: #e9ecef; }
        .pagination .active { background: #0066cc; color: #fff; border-color: #0066cc; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Products Management</h1>
            <div class="header-actions">
                <a href="{{ route('inventory-personnel.products.create') }}" class="btn btn-primary">+ Add New Product</a>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">← Back</a>
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <!-- Stock Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Products</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $products->total() }}</div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">In Stock</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $products->filter(fn($p) => $p->quantity_in_stock > $p->reorder_level)->count() }}</div>
                <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">✓ Ready to use</div>
            </div>
            <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); padding: 24px; border-radius: 12px; color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div style="font-size: 14px; opacity: 0.8; margin-bottom: 8px;">Low Stock</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $products->filter(fn($p) => $p->quantity_in_stock <= $p->reorder_level && $p->quantity_in_stock > 0)->count() }}</div>
                <div style="font-size: 12px; opacity: 0.7; margin-top: 4px;">⚠️ Need restocking</div>
            </div>
            <div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); padding: 24px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Out of Stock</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $products->filter(fn($p) => $p->quantity_in_stock == 0)->count() }}</div>
                <div style="font-size: 12px; opacity: 0.8; margin-top: 4px;">❌ Urgent action needed</div>
            </div>
        </div>
        
        <form method="GET" action="{{ route('inventory-personnel.products.index') }}" class="filters">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" placeholder="Product name, SKU..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Stock Status</label>
                <select name="stock_status">
                    <option value="">All Stock</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="is_active">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
            @if(request()->hasAny(['search', 'category', 'stock_status', 'is_active']))
            <div class="filter-group">
                <label>&nbsp;</label>
                <a href="{{ route('inventory-personnel.products.index') }}" class="btn btn-secondary">Clear</a>
            </div>
            @endif
        </form>
        
        <div class="card">
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
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                    <p>{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $product->category->name }}</span>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>${{ number_format($product->unit_price, 2) }} / {{ $product->unit_of_measure }}</td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div class="stock-indicator">
                                        <span class="stock-dot {{ $product->isOutOfStock() ? 'stock-out' : ($product->isLowStock() ? 'stock-low' : 'stock-good') }}"></span>
                                        <strong style="font-size: 16px; {{ $product->isOutOfStock() ? 'color: #dc3545;' : ($product->isLowStock() ? 'color: #ffc107;' : 'color: #28a745;') }}">
                                            {{ $product->quantity_in_stock }}
                                        </strong>
                                        <span style="color: #6c757d;">{{ $product->unit_of_measure }}</span>
                                    </div>
                                    @if($product->isOutOfStock())
                                        <span class="badge badge-danger" style="width: fit-content;">OUT OF STOCK</span>
                                    @elseif($product->isLowStock())
                                        <span class="badge badge-warning" style="width: fit-content;">LOW STOCK (Reorder: {{ $product->reorder_level }})</span>
                                    @else
                                        <span class="badge badge-success" style="width: fit-content;">IN STOCK</span>
                                    @endif
                                    <small style="color: #6c757d;">Catering: {{ $product->catering_quantity }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($product->status === 'pending')
                                        <span class="badge badge-warning">⏳ Pending Approval</span>
                                    @elseif($product->status === 'approved')
                                        <span class="badge badge-success">✓ Approved</span>
                                    @elseif($product->status === 'rejected')
                                        <span class="badge badge-danger">✗ Rejected</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    @if($product->status === 'approved')
                                        <a href="{{ route('inventory-personnel.stock-movements.incoming') }}?product_id={{ $product->id }}" class="btn btn-sm" style="background: #059669; color: white;">📥 Add Stock</a>
                                    @endif
                                    <a href="{{ route('inventory-personnel.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('inventory-personnel.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
</body>
</html>
