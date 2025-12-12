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
    <a href="{{ route('inventory-personnel.products.index') }}?view=stock" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.25); transition: all 0.3s ease;">
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
    <a href="{{ route('inventory-personnel.requests.supervisor-approved') }}" style="position: relative; display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Forward to Security</span>
        @if(isset($supervisorApprovedCount) && $supervisorApprovedCount > 0)
        <span style="position: absolute; top: -8px; right: -8px; background: #dc2626; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.5);">{{ $supervisorApprovedCount }}</span>
        @endif
    </a>
    <a href="{{ route('inventory-personnel.stock-movements.transfer-to-catering') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
        </svg>
        <span>Transfer to Catering</span>
    </a>
</div>

<!-- Supervisor Approved Requests -->
@if(isset($supervisorApprovedRequests) && $supervisorApprovedRequests->count() > 0)
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">✅ Supervisor Approved - Forward to Security</h3>
        <a href="{{ route('inventory-personnel.requests.supervisor-approved') }}" style="color: #0ea5e9; font-weight: 600; text-decoration: none; font-size: 14px;">View All →</a>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Request ID</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Flight</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Products</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Requester</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supervisorApprovedRequests as $request)
            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                <td style="padding: 14px 16px;">
                    <span style="font-weight: 600; color: #1e40af;">#{{ $request->id }}</span>
                </td>
                <td style="padding: 14px 16px;">
                    <div style="font-weight: 600; color: #1a1a1a;">{{ $request->flight->flight_number ?? 'N/A' }}</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ $request->flight->origin ?? '' }} → {{ $request->flight->destination ?? '' }}</div>
                </td>
                <td style="padding: 14px 16px;">
                    @foreach($request->items->take(2) as $item)
                        <div style="font-size: 13px; color: #374151; margin-bottom: 4px;">
                            <span style="font-weight: 600;">{{ $item->product->name }}</span> 
                            <span style="color: #9ca3af;">({{ $item->quantity_approved ?? $item->quantity_requested }})</span>
                        </div>
                    @endforeach
                    @if($request->items->count() > 2)
                        <div style="font-size: 12px; color: #0ea5e9; font-weight: 600;">+{{ $request->items->count() - 2 }} more</div>
                    @endif
                </td>
                <td style="padding: 14px 16px;">
                    <div style="font-weight: 600; color: #1a1a1a;">{{ $request->requester->name ?? 'Unknown' }}</div>
                    <div style="font-size: 11px; color: #6b7280;">{{ $request->requester->roles->first()->name ?? '' }}</div>
                </td>
                <td style="padding: 14px 16px; text-align: center;">
                    <a href="{{ route('inventory-personnel.requests.supervisor-approved') }}" 
                       style="display: inline-block; padding: 8px 18px; background: #0ea5e9; color: white; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Forward to Security →
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Transfer Stats -->
@if($pendingTransfersCount > 0 || $approvedTransfersCount > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
    @if($pendingTransfersCount > 0)
    <a href="#pending-transfers" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $pendingTransfersCount }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Pending Transfers</div>
        </div>
    </div>
    </a>
    @endif

    @if($approvedTransfersCount > 0)
    <a href="#approved-transfers" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $approvedTransfersCount }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Approved Transfers</div>
        </div>
    </div>
    </a>
    @endif
</div>
@endif

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

<!-- Pending Transfers (Awaiting Supervisor Approval) -->
<div id="pending-transfers"></div>
@if($pendingTransfers->count() > 0)
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:32px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">⏳ Pending Transfers to Catering</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Awaiting Inventory Supervisor approval</p>
        </div>
        <span style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);color:white;padding:6px 16px;border-radius:20px;font-weight:600;font-size:14px;">
            {{ $pendingTransfers->count() }}
        </span>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f7fafc;">
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Date</th>
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product</th>
                <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Quantity</th>
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Reference</th>
                <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingTransfers as $transfer)
            <tr style="border-bottom:1px solid #e2e8f0;">
                <td style="padding:16px;font-size:13px;color:#718096;">{{ $transfer->movement_date->format('M d, Y H:i') }}</td>
                <td style="padding:16px;">
                    <div style="font-weight:600;color:#2d3748;">{{ $transfer->product->name }}</div>
                    <div style="font-size:12px;color:#718096;">{{ $transfer->product->sku }}</div>
                </td>
                <td style="padding:16px;text-align:center;font-weight:700;color:#2d3748;">{{ $transfer->quantity }}</td>
                <td style="padding:16px;font-size:13px;color:#4a5568;">{{ $transfer->reference_number }}</td>
                <td style="padding:16px;text-align:center;">
                    <span style="background:#fef3c7;color:#d97706;padding:6px 14px;border-radius:12px;font-size:12px;font-weight:600;">
                        ⏳ Pending Approval
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Approved Transfers -->
<div id="approved-transfers"></div>
@if($approvedTransfers->count() > 0)
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:32px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">✅ Approved Transfers to Catering</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Successfully transferred to Catering Stock</p>
        </div>
        <span style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:6px 16px;border-radius:20px;font-weight:600;font-size:14px;">
            {{ $approvedTransfers->count() }}
        </span>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f7fafc;">
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved Date</th>
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product</th>
                <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Quantity</th>
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Reference</th>
                <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved By</th>
                <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedTransfers as $transfer)
            <tr style="border-bottom:1px solid #e2e8f0;">
                <td style="padding:16px;font-size:13px;color:#718096;">{{ $transfer->approved_at ? $transfer->approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                <td style="padding:16px;">
                    <div style="font-weight:600;color:#2d3748;">{{ $transfer->product->name }}</div>
                    <div style="font-size:12px;color:#718096;">{{ $transfer->product->sku }}</div>
                </td>
                <td style="padding:16px;text-align:center;font-weight:700;color:#2d3748;">{{ $transfer->quantity }}</td>
                <td style="padding:16px;font-size:13px;color:#4a5568;">{{ $transfer->reference_number }}</td>
                <td style="padding:16px;font-size:13px;color:#4a5568;">{{ $transfer->approvedBy ? $transfer->approvedBy->name : 'System' }}</td>
                <td style="padding:16px;text-align:center;">
                    <span style="background:#d1fae5;color:#059669;padding:6px 14px;border-radius:12px;font-size:12px;font-weight:600;">
                        ✓ Approved
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Recently Added Products -->
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Recently Added Products</h3>
        <a href="{{ route('inventory-personnel.products.index') }}" style="color: #0066cc; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            View All 
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    @if($recentProducts->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e9ecef;">
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Product Name</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">SKU</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Category</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Main Stock</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Catering Stock</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Added</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProducts as $product)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                    <td style="padding: 14px 16px; font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $product->name }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #6c757d;">{{ $product->sku }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #495057;">{{ $product->category->name }}</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 50px; padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 700; 
                            background: {{ $product->quantity_in_stock > 0 ? '#d4edda' : '#f8d7da' }}; 
                            color: {{ $product->quantity_in_stock > 0 ? '#155724' : '#721c24' }};">
                            {{ $product->quantity_in_stock }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 50px; padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 700; 
                            background: {{ $product->catering_stock > 0 ? '#cfe2ff' : '#e2e3e5' }}; 
                            color: {{ $product->catering_stock > 0 ? '#084298' : '#41464b' }};">
                            {{ $product->catering_stock }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #6c757d; white-space: nowrap;">{{ $product->created_at->diffForHumans() }}</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <a href="{{ route('inventory-personnel.products.edit', $product) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #0066cc; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 40px;">
        <svg style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <p style="color: #6c757d; font-size: 14px; margin: 0;">No products added yet</p>
        <a href="{{ route('inventory-personnel.products.create') }}" style="display: inline-flex; align-items: center; gap: 8px; margin-top: 16px; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Your First Product
        </a>
    </div>
    @endif
</div>

<!-- Main Stock Inventory -->
<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">Main Stock Inventory</h3>
        <a href="{{ route('inventory-personnel.products.index') }}" style="color: #0066cc; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 6px;">
            View All Products
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    @if($productsInStock->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e9ecef;">
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Product Name</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">SKU</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Category</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Main Stock</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Catering Stock</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Unit Price</th>
                    <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase; white-space: nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productsInStock as $product)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                    <td style="padding: 14px 16px; font-size: 14px; color: #1a1a1a; font-weight: 500;">{{ $product->name }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #6c757d;">{{ $product->sku }}</td>
                    <td style="padding: 14px 16px; font-size: 14px; color: #495057;">{{ $product->category->name }}</td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 60px; padding: 8px 14px; border-radius: 8px; font-size: 15px; font-weight: 700; 
                            background: {{ $product->quantity_in_stock <= $product->reorder_level ? '#fff3cd' : '#d4edda' }}; 
                            color: {{ $product->quantity_in_stock <= $product->reorder_level ? '#856404' : '#155724' }};">
                            {{ $product->quantity_in_stock }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 60px; padding: 8px 14px; border-radius: 8px; font-size: 15px; font-weight: 700; 
                            background: {{ $product->catering_stock > 0 ? '#cfe2ff' : '#e2e3e5' }}; 
                            color: {{ $product->catering_stock > 0 ? '#084298' : '#6c757d' }};">
                            {{ $product->catering_stock }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; font-size: 14px; font-weight: 600; color: #495057;">
                        {{ $product->currency }} {{ number_format($product->unit_price, 2) }}
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('inventory-personnel.products.edit', $product) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #0066cc; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('inventory-personnel.stock-movements.transfer-to-catering') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600; transition: all 0.2s;">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Transfer
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Stock Summary & Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding: 20px; background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
        <div style="display: flex; gap: 32px; align-items: center;">
            <div>
                <div style="font-size: 13px; color: #6c757d; font-weight: 500; margin-bottom: 4px;">Total Products in Stock</div>
                <div style="font-size: 24px; font-weight: 700; color: #1a1a1a;">{{ $productsInStock->total() }}</div>
            </div>
            <div style="width: 1px; height: 40px; background: #dee2e6;"></div>
            <div>
                <div style="font-size: 13px; color: #6c757d; font-weight: 500; margin-bottom: 4px;">Low Stock Items</div>
                <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ $lowStockProducts }}</div>
            </div>
            <div style="width: 1px; height: 40px; background: #dee2e6;"></div>
            <div>
                <div style="font-size: 13px; color: #6c757d; font-weight: 500; margin-bottom: 4px;">Out of Stock</div>
                <div style="font-size: 24px; font-weight: 700; color: #dc2626;">{{ $outOfStockProducts }}</div>
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('inventory-personnel.products.create') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
            <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                </svg>
                Add Stock
            </a>
            <a href="{{ route('inventory-personnel.stock-movements.transfer-to-catering') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Transfer to Catering
            </a>
        </div>
    </div>
    
    <!-- Pagination -->
    <div style="margin-top: 24px;">
        {{ $productsInStock->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px;">
        <svg style="width: 80px; height: 80px; color: #cbd5e1; margin-bottom: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <h4 style="font-size: 18px; font-weight: 600; color: #475569; margin-bottom: 12px;">No Products in Stock</h4>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px;">Add products and stock to get started</p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('inventory-personnel.products.create') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #0066cc; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 2px 8px rgba(0, 102, 204, 0.3);">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
            <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                </svg>
                Add Stock
            </a>
        </div>
    </div>
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
