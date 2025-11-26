@extends('layouts.app')

@section('title', 'Inventory Supervisor Dashboard')

@section('content')
<div class="content-header">
    <h1>Inventory Supervisor Dashboard</h1>
    <p>Approve products and stock movements from Inventory Personnel</p>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Pending Products -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $pendingProducts }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Pending Products</div>
        </div>
    </div>

    <!-- Pending Movements -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $pendingMovements }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Pending Movements</div>
        </div>
    </div>

    <!-- Approved Products -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $approvedProducts }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Approved Products</div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $lowStockProducts }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Low Stock Items</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <a href="{{ route('inventory-supervisor.approvals.products') }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Approve Products</span>
    </a>

    <a href="{{ route('inventory-supervisor.approvals.movements') }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <span>Approve Stock Movements</span>
    </a>

    <a href="{{ route('inventory-supervisor.stock-movements.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0h2a2 2 0 012 2v0a2 2 0 01-2 2h-2a2 2 0 01-2-2v0z"></path>
        </svg>
        <span>View All Movements</span>
    </a>
</div>

<!-- Pending Products Table -->
@if($pendingProductsList->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Pending Products Approval</h3>
        <a href="{{ route('inventory-supervisor.approvals.products') }}" style="color: #667eea; font-weight: 600; text-decoration: none; font-size: 14px;">View All →</a>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Category</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Price</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Stock</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingProductsList as $product)
            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $product->category->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center; font-weight: 600;">{{ $product->currency }} {{ number_format($product->unit_price, 2) }}</td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center;">{{ $product->quantity_in_stock }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    <form action="{{ route('inventory-supervisor.approvals.products.approve', $product) }}" method="POST" style="display: inline-block; margin-right: 8px;">
                        @csrf
                        <button type="submit" style="padding: 6px 16px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">✓ Approve</button>
                    </form>
                    <form action="{{ route('inventory-supervisor.approvals.products.reject', $product) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" style="padding: 6px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">✗ Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Pending Stock Movements Table -->
@if($movementsToVerify->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Pending Stock Movements Approval</h3>
        <a href="{{ route('inventory-supervisor.approvals.movements') }}" style="color: #667eea; font-weight: 600; text-decoration: none; font-size: 14px;">View All →</a>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Date</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Type</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Quantity</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">By</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movementsToVerify as $movement)
            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                <td style="padding: 14px 16px; font-size: 14px; white-space: nowrap;">{{ $movement->created_at->format('M d, Y H:i') }}</td>
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $movement->product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">
                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;
                        background: {{ $movement->type == 'incoming' ? '#d4edda' : ($movement->type == 'issued' ? '#fff3cd' : '#d1ecf1') }};
                        color: {{ $movement->type == 'incoming' ? '#155724' : ($movement->type == 'issued' ? '#856404' : '#0c5460') }};">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center; font-weight: 600;">{{ $movement->quantity }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $movement->user->name }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    <form action="{{ route('inventory-supervisor.approvals.movements.approve', $movement) }}" method="POST" style="display: inline-block; margin-right: 8px;">
                        @csrf
                        <button type="submit" style="padding: 6px 16px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">✓ Approve</button>
                    </form>
                    <form action="{{ route('inventory-supervisor.approvals.movements.reject', $movement) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" style="padding: 6px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">✗ Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Recently Approved Movements -->
@if($recentlyApproved->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 24px 0;">Recently Approved</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Date</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Type</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Quantity</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Approved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentlyApproved as $movement)
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 14px 16px; font-size: 14px; white-space: nowrap;">{{ $movement->approved_at->format('M d, Y H:i') }}</td>
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $movement->product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">
                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center; font-weight: 600;">{{ $movement->quantity }}</td>
                <td style="padding: 14px 16px; font-size: 14px; color: #28a745;">✓ {{ $movement->approvedBy->name ?? 'System' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Low Stock Items Alert -->
@if($lowStockItems->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">
            <svg style="width: 24px; height: 24px; display: inline-block; vertical-align: middle; color: #dc3545; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Low Stock Alert
        </h3>
        <span style="padding: 6px 16px; background: #dc3545; color: white; border-radius: 20px; font-size: 13px; font-weight: 600;">{{ $lowStockItems->count() }} items</span>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Category</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Current Stock</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Minimum Stock</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $product)
            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $product->category->name }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    <span style="padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 700; 
                        background: {{ $product->quantity_in_stock == 0 ? '#f8d7da' : '#fff3cd' }}; 
                        color: {{ $product->quantity_in_stock == 0 ? '#721c24' : '#856404' }};">
                        {{ $product->quantity_in_stock }}
                    </span>
                </td>
                <td style="padding: 14px 16px; text-align: center; font-weight: 600; color: #6c757d;">{{ $product->reorder_level }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    @if($product->quantity_in_stock == 0)
                    <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">
                        OUT OF STOCK
                    </span>
                    @else
                    <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #856404;">
                        LOW STOCK
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<style>
div[style*="background: white"][style*="box-shadow"]:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}

a[style*="background: linear-gradient"]:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
    opacity: 0.95;
}

tr:hover {
    background: #f8f9fa !important;
}

button:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
