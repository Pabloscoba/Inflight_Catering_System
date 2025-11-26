@extends('layouts.app')

@section('title', 'Inventory Personnel Dashboard')

@section('content')
<div class="content-header">
    <h1>Inventory Personnel Dashboard</h1>
    <p>Manage products and stock levels</p>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $totalProducts }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Total Products</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $lowStockProducts }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Low Stock Items</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $outOfStockProducts }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Out of Stock</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease; min-width: 0;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0; overflow: hidden;">
            <div style="font-size: 28px; font-weight: 700; color: #1a1a1a; line-height: 1.2; margin-bottom: 6px; word-wrap: break-word; overflow-wrap: break-word;">${{ number_format($totalStockValue, 2) }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Total Stock Value</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <a href="{{ route('inventory-personnel.products.create') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Add New Product</span>
    </a>
    <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
        </svg>
        <span>Add Stock</span>
    </a>
    <a href="{{ route('inventory-personnel.products.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <span>View All Products</span>
    </a>
    <a href="{{ route('inventory-personnel.stock-movements.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(250, 112, 154, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span>Stock Reports</span>
    </a>
    <a href="{{ route('inventory-personnel.requests.pending') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25); transition: all 0.3s ease; position: relative;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Pending Requests</span>
        @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
        <span style="position: absolute; top: -8px; right: -8px; background: #dc2626; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.5);">{{ $pendingRequestsCount }}</span>
        @endif
    </a>
    <a href="{{ route('inventory-personnel.requests.supervisor-approved') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Approved (To Dispatch)</span>
    </a>
</div>

<!-- Low Stock Alert -->
@if($lowStockItems->count() > 0)
<div class="alert alert-warning" style="margin: 20px 0;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; margin-right: 10px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        <strong>Low Stock Alert!</strong> {{ $lowStockProducts }} product(s) are running low on stock. Please reorder soon.
    </div>
</div>
@endif

<!-- Recent Stock Movements -->
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Recent Stock Movements</h3>
        <a href="{{ route('inventory-personnel.stock-movements.index') }}" style="color: #0066cc; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            View All 
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    @if($recentMovements->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e9ecef;">
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Date</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Product</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Type</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Quantity</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentMovements as $movement)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                    <td style="padding: 14px 16px; font-size: 14px; color: #495057; white-space: nowrap;">{{ $movement->created_at->format('M d, Y H:i') }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $movement->product->name }}</td>
                    <td style="padding: 14px 16px;">
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: capitalize; 
                            background: {{ $movement->type == 'incoming' ? '#d4edda' : ($movement->type == 'issued' ? '#fff3cd' : '#d1ecf1') }}; 
                            color: {{ $movement->type == 'incoming' ? '#155724' : ($movement->type == 'issued' ? '#856404' : '#0c5460') }};">
                            {{ ucfirst($movement->type) }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; font-size: 14px; font-weight: 600; color: #1a1a1a;">{{ $movement->quantity }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #6c757d;">{{ $movement->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p style="text-align: center; padding: 40px; color: #6c757d; font-size: 14px;">No stock movements yet</p>
    @endif
</div>

<!-- Products Needing Attention -->
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Products Needing Attention</h3>
    </div>
    @if($lowStockItems->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e9ecef;">
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Product</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Category</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Current Stock</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Minimum Stock</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockItems as $product)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                    <td style="padding: 14px 16px; font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $product->name }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #495057;">{{ $product->category->name }}</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 50px; padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 700; background: #fee; color: #c00;">
                            {{ $product->quantity_in_stock }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; font-size: 14px; font-weight: 600; color: #6c757d;">{{ $product->reorder_level }}</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3); transition: all 0.2s;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p style="text-align: center; padding: 40px; color: #6c757d; font-size: 14px;">All products are adequately stocked ✓</p>
    @endif
</div>

<style>
/* Hover effects for stat cards */
div[style*="background: white"][style*="box-shadow"]:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}

/* Hover effects for action buttons */
a[style*="background: linear-gradient"]:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
    opacity: 0.95;
}

/* Table row hover */
tr:hover {
    background: #f8f9fa !important;
}

/* Restock button hover */
a[href*="incoming"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4) !important;
}

.alert {
    padding: 15px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
}
</style>
@endsection
