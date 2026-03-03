@extends('layouts.app')

@section('title', 'Inventory Supervisor Dashboard')

@section('content')
    <div class="content-header">

    </div>

    <!-- Stats Cards -->
    <!-- Stats Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <!-- Pending Products -->
        <div class="card-atcl" style="padding: 24px; display: flex; gap: 20px; align-items: center;">
            <div
                style="width: 56px; height: 56px; border-radius: 12px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 28px; height: 28px; color: #1e3a8a;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 28px; font-weight: 800; color: #1e3a8a; line-height: 1;">{{ $pendingProducts }}</div>
                <div
                    style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pending Products</div>
            </div>
        </div>

        <!-- Pending Movements -->
        <div class="card-atcl" style="padding: 24px; display: flex; gap: 20px; align-items: center;">
            <div
                style="width: 56px; height: 56px; border-radius: 12px; background: #fffbeb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 28px; height: 28px; color: #b45309;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 28px; font-weight: 800; color: #b45309; line-height: 1;">{{ $pendingMovements }}
                </div>
                <div
                    style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pending Movements</div>
            </div>
        </div>

        <!-- Approved Products -->
        <div class="card-atcl" style="padding: 24px; display: flex; gap: 20px; align-items: center;">
            <div
                style="width: 56px; height: 56px; border-radius: 12px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 28px; height: 28px; color: #059669;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 28px; font-weight: 800; color: #059669; line-height: 1;">{{ $approvedProducts }}
                </div>
                <div
                    style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Approved Products</div>
            </div>
        </div>

        <!-- Pending Requests -->
        <a href="#pending-requests-section" style="text-decoration:none; color:inherit; display:block;">
            <div class="card-atcl" style="padding: 24px; display: flex; gap: 20px; align-items: center;">
                <div
                    style="width: 56px; height: 56px; border-radius: 12px; background: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 28px; height: 28px; color: #dc2626;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 28px; font-weight: 800; color: #dc2626; line-height: 1;">{{ $pendingRequests }}
                    </div>
                    <div
                        style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Pending Requests</div>
                </div>
            </div>
        </a>

        <!-- Approved Requests -->
        <a href="#approved-requests-section" style="text-decoration:none; color:inherit; display:block;">
            <div class="card-atcl" style="padding: 24px; display: flex; gap: 20px; align-items: center;">
                <div
                    style="width: 56px; height: 56px; border-radius: 12px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 28px; height: 28px; color: #16a34a;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 28px; font-weight: 800; color: #16a34a; line-height: 1;">{{ $approvedRequests }}
                    </div>
                    <div
                        style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Approved Requests</div>
                </div>
            </div>
        </a>

        <!-- Low Stock Alert -->
        <div class="card-atcl"
            style="padding: 24px; display: flex; gap: 20px; align-items: center; border-left: 4px solid #ef4444;">
            <div
                style="width: 56px; height: 56px; border-radius: 12px; background: #fee2e2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 28px; height: 28px; color: #b91c1c;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 28px; font-weight: 800; color: #b91c1c; line-height: 1;">{{ $lowStockProducts }}
                </div>
                <div
                    style="font-size: 13px; font-weight: 600; color: #6b7280; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Low Stock Level</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <!-- Quick Actions -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <a href="{{ route('inventory-supervisor.products.index') }}" class="btn-atcl btn-atcl-primary"
            style="display: flex; align-items: center; gap: 12px; justify-content: center; padding: 16px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Pending Products ({{ $pendingProducts }})</span>
        </a>

        <a href="{{ route('inventory-supervisor.products.all') }}" class="btn-atcl btn-atcl-secondary"
            style="display: flex; align-items: center; gap: 12px; justify-content: center; padding: 16px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span>All Products & Trends</span>
        </a>

        <a href="{{ route('inventory-supervisor.approvals.movements') }}" class="btn-atcl btn-atcl-primary"
            style="display: flex; align-items: center; gap: 12px; justify-content: center; padding: 16px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <span>Pending Movements ({{ $pendingMovements }})</span>
        </a>

        <a href="{{ route('inventory-supervisor.stock-movements.index') }}" class="btn-atcl btn-atcl-secondary"
            style="display: flex; align-items: center; gap: 12px; justify-content: center; padding: 16px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0h2a2 2 0 012 2v0a2 2 0 01-2 2h-2a2 2 0 01-2-2v0z">
                </path>
            </svg>
            <span>Transaction History</span>
        </a>
    </div>

    <!-- DYNAMIC PERMISSION-BASED ACTIONS (Auto-appear when permissions added) -->
    <x-permission-actions :exclude="['approve products', 'approve stock movements', 'view stock levels', 'generate stock movement reports']" />
    </div>

    <!-- Pending Requests Table -->
    @if($pendingRequestsList->count() > 0)
        <div class="card-atcl" id="pending-requests-section" style="padding: 24px; margin-bottom: 32px; overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">Pending Catering Requests</h3>
                <span
                    style="background: #fffbeb; color: #b45309; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 700;">{{ $pendingRequestsList->count() }}
                    Waiting</span>
            </div>
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Flight</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Items</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Requested By</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Date</th>
                        <th
                            style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequestsList as $request)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 16px;">
                                <div style="font-weight: 700; color: #111827;">{{ $request->flight->flight_number ?? 'N/A' }}</div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 500;">{{ $request->flight->origin ?? '' }}
                                    → {{ $request->flight->destination ?? '' }}</div>
                            </td>
                            <td style="padding: 16px;">
                                @foreach($request->items->take(2) as $item)
                                    <div style="font-size: 13px; color: #4b5563;">
                                        <span style="font-weight: 600;">{{ $item->product->name }}</span>
                                        <span style="color: #94a3b8;">x{{ $item->quantity_requested }}</span>
                                    </div>
                                @endforeach
                                @if($request->items->count() > 2)
                                    <div style="font-size: 11px; color: #1e3a8a; font-weight: 700; margin-top: 2px;">
                                        +{{ $request->items->count() - 2 }} OTHERS</div>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 600; color: #374151;">{{ $request->requester->name ?? 'Staff' }}</div>
                                <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">
                                    {{ $request->requester->roles->first()->name ?? '' }}</div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 600; color: #374151;">{{ $request->created_at->format('d M, Y') }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ $request->created_at->diffForHumans() }}</div>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form action="{{ route('inventory-supervisor.requests.approve', $request) }}" method="POST">
                                        @csrf
                                        @foreach($request->items as $item)
                                            <input type="hidden" name="items[{{ $item->id }}][quantity_approved]"
                                                value="{{ $item->quantity_requested }}">
                                        @endforeach
                                        <button type="submit" class="btn-atcl"
                                            style="padding: 6px 12px; font-size: 12px; background: #059669; color: white;">Approve
                                            Quick</button>
                                    </form>
                                    <a href="{{ route('inventory-supervisor.requests.show', $request) }}"
                                        class="btn-atcl btn-atcl-secondary" style="padding: 6px 12px; font-size: 12px;">Review</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pending Products Table -->
    @if($pendingProductsList->count() > 0)
        <div class="card-atcl" style="padding: 24px; margin-bottom: 32px; overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">New Products Awaiting Approval</h3>
                <a href="{{ route('inventory-supervisor.approvals.products') }}"
                    style="color: #1e3a8a; font-weight: 700; text-decoration: none; font-size: 13px; text-transform: uppercase;">View All →</a>
            </div>
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Product Details</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Category</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Unit Price</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Initial Stock</th>
                        <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingProductsList as $product)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 16px;">
                                <div style="font-weight: 700; color: #111827;">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $product->sku }}</div>
                            </td>
                            <td style="padding: 16px; font-size: 14px; color: #4b5563;">{{ $product->category->name }}</td>
                            <td style="padding: 16px; font-size: 14px; text-align: center; font-weight: 700; color: #111827;">{{ $product->currency }} {{ number_format($product->unit_price, 2) }}</td>
                            <td style="padding: 16px; font-size: 14px; text-align: center; color: #4b5563;">{{ $product->quantity_in_stock }} {{ $product->unit_of_measure }}</td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form action="{{ route('inventory-supervisor.approvals.products.approve', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl" style="padding: 6px 12px; font-size: 12px; background: #059669; color: white;">Approve</button>
                                    </form>
                                    <form action="{{ route('inventory-supervisor.approvals.products.reject', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl" style="padding: 6px 12px; font-size: 12px; background: #dc2626; color: white;">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pending Stock Movements Table -->
    @if($movementsToVerify->count() > 0)
        <div class="card-atcl" style="padding: 24px; margin-bottom: 32px; overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">Stock Movements Verification</h3>
                <a href="{{ route('inventory-supervisor.approvals.movements') }}"
                    style="color: #1e3a8a; font-weight: 700; text-decoration: none; font-size: 13px; text-transform: uppercase;">View All →</a>
            </div>
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Recorded By</th>
                        <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movementsToVerify as $movement)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; font-size: 13px; color: #6b7280; white-space: nowrap;">{{ $movement->created_at->format('d M, Y H:i') }}</td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 700; color: #111827;">{{ $movement->product->name }}</div>
                            </td>
                            <td style="padding: 16px;">
                                @php
                                    $badgeStyles = [
                                        'incoming' => 'background: #d1fae5; color: #065f46;',
                                        'issued' => 'background: #fef3c7; color: #92400e;',
                                        'returned' => 'background: #dbeafe; color: #1e40af;'
                                    ];
                                    $style = $badgeStyles[$movement->type] ?? 'background: #f3f4f6; color: #374151;';
                                @endphp
                                <span style="padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; {{ $style }}">
                                    {{ $movement->type }}
                                </span>
                            </td>
                            <td style="padding: 16px; font-size: 15px; text-align: center; font-weight: 700; color: #111827;">{{ $movement->quantity }}</td>
                            <td style="padding: 16px; font-size: 14px; color: #4b5563;">{{ $movement->user->name }}</td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form action="{{ route('inventory-supervisor.approvals.movements.approve', $movement) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl" style="padding: 6px 12px; font-size: 12px; background: #059669; color: white;">Verify</button>
                                    </form>
                                    <form action="{{ route('inventory-supervisor.approvals.movements.reject', $movement) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl" style="padding: 6px 12px; font-size: 12px; background: #dc2626; color: white;">Flag</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Recently Approved Movements -->
    @if($recentlyApproved->count() > 0)
        <div class="card-atcl" style="padding: 24px; margin-bottom: 32px; overflow-x: auto;">
            <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0 0 24px 0;">Recently Verified Transactions</h3>
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Approval Date</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                        <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Verified By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentlyApproved as $movement)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; font-size: 13px; color: #6b7280; white-space: nowrap;">{{ $movement->approved_at->format('d M, Y H:i') }}</td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 700; color: #111827;">{{ $movement->product->name }}</div>
                            </td>
                            <td style="padding: 16px;">
                                <span style="padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #ecfdf5; color: #065e3a;">
                                    {{ $movement->type }}
                                </span>
                            </td>
                            <td style="padding: 16px; font-size: 15px; text-align: center; font-weight: 700; color: #111827;">{{ $movement->quantity }}</td>
                            <td style="padding: 16px; text-align: right; font-size: 14px; font-weight: 600; color: #3b82f6;">
                                {{ $movement->approvedBy->name ?? 'System' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif



    <!-- Low Stock Items Alert -->
    @if($lowStockItems->count() > 0)
        <div class="card-atcl" style="padding: 24px; margin-top: 32px; border-left: 4px solid #dc2626; overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #b91c1c; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Critical Low Stock Warning
                </h3>
                <span style="padding: 6px 14px; background: #fee2e2; color: #b91c1c; border-radius: 9999px; font-size: 12px; font-weight: 800;">{{ $lowStockItems->count() }} CRITICAL ITEMS</span>
            </div>
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #fff5f5; border-bottom: 2px solid #fed7d7;">
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Product</th>
                        <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Category</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Current</th>
                        <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Min Level</th>
                        <th style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $product)
                    <tr style="border-bottom: 1px solid #fee2e2;">
                        <td style="padding: 16px; font-weight: 700; color: #111827;">{{ $product->name }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #4b5563;">{{ $product->category->name }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 800; background: {{ $product->quantity_in_stock == 0 ? '#b91c1c' : '#dc2626' }}; color: white;">
                                {{ $product->quantity_in_stock }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; color: #6b7280;">{{ $product->reorder_level }}</td>
                        <td style="padding: 16px; text-align: right;">
                            @if($product->quantity_in_stock == 0)
                                <span style="padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: #b91c1c; color: white;">OUT OF STOCK</span>
                            @else
                                <span style="padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: #fca5a5; color: #7f1d1d;">LOW LEVEL</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
