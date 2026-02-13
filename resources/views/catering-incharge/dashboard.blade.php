@extends('layouts.app')

@section('title', 'Catering Incharge Dashboard')

@section('content')
<div style="padding: 32px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Catering Incharge Dashboard</h1>
        <p style="color: #718096; font-size: 16px; margin: 0;">Approve product receipts from inventory and manage catering staff requests</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <!-- Pending Receipts -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
            <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 32px; font-weight: 700; color: #1a202c; line-height: 1;">{{ $pendingReceipts }}</div>
                <div style="font-size: 14px; color: #718096; margin-top: 4px;">Pending Receipts</div>
            </div>
        </div>

        <!-- Pending Staff Requests -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
            <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 32px; font-weight: 700; color: #1a202c; line-height: 1;">{{ $pendingRequests }}</div>
                <div style="font-size: 14px; color: #718096; margin-top: 4px;">Pending Staff Requests</div>
            </div>
        </div>

        <!-- Approved Receipts -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
            <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 32px; font-weight: 700; color: #1a202c; line-height: 1;">{{ $approvedReceipts }}</div>
                <div style="font-size: 14px; color: #718096; margin-top: 4px;">Approved Receipts</div>
            </div>
        </div>

        <!-- Total Catering Stock -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
            <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 32px; font-weight: 700; color: #1a202c; line-height: 1;">{{ $totalCateringStock }}</div>
                <div style="font-size: 14px; color: #718096; margin-top: 4px;">Available Stock Units</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
        <a href="{{ route('catering-incharge.receipts.pending') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s ease; display: block;">
            📦 Approve Receipts
        </a>
        <a href="{{ route('catering-incharge.requests.pending') }}" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3); transition: all 0.3s ease; display: block;">
            📋 Pending Approval
        </a>
        <a href="{{ route('catering-incharge.requests.pending-final') }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); transition: all 0.3s ease; display: block; position: relative;">
            🔒 Final Approval
            @if(isset($pendingItemReceipts) && $pendingItemReceipts > 0)
            <span style="position: absolute; top: -8px; right: -8px; background: #dc2626; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.5);">{{ $pendingItemReceipts }}</span>
            @endif
        </a>
        <a href="{{ route('catering-incharge.requests.approved') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; display: block;">
            ✅ View Approved
        </a>
        <a href="{{ route('catering-incharge.receipts.stock-overview') }}" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3); transition: all 0.3s ease; display: block;">
            📊 Stock Overview
        </a>
        
        <!-- DYNAMIC PERMISSION-BASED ACTIONS (Auto-appear when permissions added) -->
        <x-permission-actions :exclude="['approve initial request', 'approve final request after receipt', 'view approved requests', 'check mini stock']" />
    </div>

    <!-- Low Stock Alert - Always Visible -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px; border-left: 4px solid {{ $lowStockItems->count() > 0 ? '#dc3545' : '#28a745' }};">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0;">
                <svg style="width: 24px; height: 24px; display: inline-block; vertical-align: middle; margin-right: 8px; color: {{ $lowStockItems->count() > 0 ? '#dc3545' : '#28a745' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($lowStockItems->count() > 0)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @endif
                </svg>
                {{ $lowStockItems->count() > 0 ? 'Low Stock Alert - Action Required' : 'Stock Levels - All Good' }}
            </h3>
            <span style="background: {{ $lowStockItems->count() > 0 ? '#dc3545' : '#28a745' }}; color: white; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;">{{ $lowStockItems->count() }} {{ $lowStockItems->count() == 1 ? 'item' : 'items' }}</span>
        </div>
        
        @if($lowStockItems->count() > 0)
        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            <p style="margin: 0; color: #856404; font-size: 14px;">⚠️ <strong>{{ $lowStockItems->count() }}</strong> product(s) are running low or out of stock. Please coordinate with Inventory to restock these items.</p>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Current Stock</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reorder Level</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $product)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $product->category->name }}</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: {{ $product->quantity_in_stock == 0 ? '#f8d7da' : '#fff3cd' }}; color: {{ $product->quantity_in_stock == 0 ? '#721c24' : '#856404' }}; padding: 6px 14px; border-radius: 12px; font-size: 15px; font-weight: 700;">
                                {{ $product->quantity_in_stock }} {{ $product->unit_of_measure ?? 'units' }}
                            </span>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d; text-align: center;">{{ $product->reorder_level }} {{ $product->unit_of_measure ?? 'units' }}</td>
                        <td style="padding: 14px;">
                            @if($product->quantity_in_stock == 0)
                            <span style="background: #f8d7da; color: #721c24; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">🚨 OUT OF STOCK</span>
                            @else
                            <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">⚠️ LOW STOCK</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 40px 20px;">
            <svg style="width: 64px; height: 64px; color: #28a745; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h4 style="font-size: 18px; font-weight: 600; color: #28a745; margin: 0 0 8px 0;">✅ All Stock Levels Are Healthy</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">No products are currently below their reorder levels.</p>
        </div>
        @endif
    </div>

    <!-- Pending Product Receipts Table -->
    @if($pendingReceiptsList->count() > 0)
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0;">Pending Product Receipts from Inventory</h3>
            <span style="background: #667eea; color: white; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;">{{ $pendingReceiptsList->count() }} pending</span>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Quantity</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reference</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Received By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Date</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReceiptsList as $receipt)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529;">{{ $receipt->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $receipt->quantity_received }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->reference_number ?? 'N/A' }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->receivedBy->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->received_date->format('M d, Y') }}</td>
                        <td style="padding: 14px;">
                            <div style="display: flex; gap: 8px;">
                                <form action="{{ route('catering-incharge.receipts.approve', $receipt) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: #28a745; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                                        ✓ Approve
                                    </button>
                                </form>
                                <button onclick="document.getElementById('reject-receipt-{{ $receipt->id }}').style.display='block'" style="background: #dc3545; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                    ✗ Reject
                                </button>
                            </div>
                            <!-- Reject Modal -->
                            <div id="reject-receipt-{{ $receipt->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
                                    <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Reject Receipt</h4>
                                    <form action="{{ route('catering-incharge.receipts.reject', $receipt) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" required placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; min-height: 100px; margin-bottom: 16px;"></textarea>
                                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('reject-receipt-{{ $receipt->id }}').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Cancel</button>
                                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pending Catering Staff Requests -->
    @if($pendingStaffRequests->count() > 0)
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 4px 0;">
                    📋 Catering Staff Requests Awaiting Approval
                </h3>
                <p style="font-size: 13px; color: #718096; margin: 0;">Review and approve requests from Catering Staff for flight preparation</p>
            </div>
            <span style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 12px rgba(240,147,251,0.3);">{{ $pendingStaffRequests->count() }} Pending</span>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Request ID</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Flight</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Requested By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Items</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Date</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingStaffRequests as $request)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">#{{ $request->id }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #212529;">{{ $request->flight->flight_number }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requester->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->items->count() }} items</td>
                        <td style="padding: 14px;">
                            @if($request->status == 'security_approved')
                                <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">🔐 Security Verified</span>
                            @elseif($request->status == 'supervisor_approved')
                                <span style="background: #3b82f6; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">✓ Supervisor OK</span>
                            @else
                                <span style="background: #f59e0b; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">⏳ Forwarded</span>
                            @endif
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requested_date->format('M d, Y') }}</td>
                        <td style="padding: 14px;">
                            <div style="display: flex; gap: 8px;">
                                <button onclick="showApproveConfirmation({{ $request->id }}, '{{ $request->flight->flight_number }}')" style="background: #28a745; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                                    ✓ Approve
                                </button>
                                <button onclick="document.getElementById('reject-request-{{ $request->id }}').style.display='flex'" style="background: #dc3545; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                    ✗ Reject
                                </button>
                            </div>
                            <!-- Hidden Approve Form -->
                            <form id="approve-form-{{ $request->id }}" action="{{ route('catering-incharge.requests.approve', $request) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <!-- Reject Modal -->
                            <div id="reject-request-{{ $request->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
                                    <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Reject Request</h4>
                                    <form action="{{ route('catering-incharge.requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" required placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; min-height: 100px; margin-bottom: 16px; font-family: inherit;"></textarea>
                                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('reject-request-{{ $request->id }}').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Cancel</button>
                                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Catering Staff Activity Oversight Section -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 4px 0;">
                👥 Catering Staff Activity Oversight
            </h3>
            <p style="font-size: 13px; color: #718096; margin: 0;">Monitor Catering Staff performance and stock management</p>
        </div>

        <!-- Staff Activity Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 20px; color: white;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 4px;">{{ $cateringStaffActivity['total_staff'] }}</div>
                <div style="font-size: 13px; opacity: 0.95;">Total Catering Staff</div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; padding: 20px; color: white;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 4px;">{{ $cateringStaffActivity['active_requests'] }}</div>
                <div style="font-size: 13px; opacity: 0.95;">Active Requests</div>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; padding: 20px; color: white;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 4px;">{{ $cateringStaffActivity['pending_staff_receipt'] }}</div>
                <div style="font-size: 13px; opacity: 0.95;">Items Ready to Receive</div>
            </div>
            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; padding: 20px; color: white;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 4px;">{{ $cateringStaffActivity['pending_final_approval'] }}</div>
                <div style="font-size: 13px; opacity: 0.95;">Awaiting Final Approval</div>
            </div>
        </div>

        <!-- Recent Staff Requests -->
        @if($cateringStaffActivity['recent_staff_requests']->count() > 0)
        <div style="border-top: 2px solid #e9ecef; padding-top: 20px;">
            <h4 style="font-size: 16px; font-weight: 600; color: #1a202c; margin: 0 0 16px 0;">📊 Recent Staff Requests & Stock Usage</h4>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e9ecef;">
                            <th style="text-align: left; padding: 12px; font-size: 13px; font-weight: 600; color: #495057;">Staff Member</th>
                            <th style="text-align: left; padding: 12px; font-size: 13px; font-weight: 600; color: #495057;">Flight</th>
                            <th style="text-align: left; padding: 12px; font-size: 13px; font-weight: 600; color: #495057;">Products Requested</th>
                            <th style="text-align: left; padding: 12px; font-size: 13px; font-weight: 600; color: #495057;">Status</th>
                            <th style="text-align: left; padding: 12px; font-size: 13px; font-weight: 600; color: #495057;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cateringStaffActivity['recent_staff_requests'] as $req)
                        <tr style="border-bottom: 1px solid #f1f3f5;">
                            <td style="padding: 12px; font-size: 13px; color: #212529; font-weight: 600;">{{ $req->requester->name }}</td>
                            <td style="padding: 12px; font-size: 13px; color: #6c757d;">{{ $req->flight->flight_number ?? 'N/A' }}</td>
                            <td style="padding: 12px; font-size: 13px; color: #6c757d;">
                                @foreach($req->items->take(2) as $item)
                                    <div style="margin-bottom: 4px;">
                                        • {{ $item->product->name }} ({{ $item->quantity_requested }} {{ $item->product->unit_of_measure ?? 'units' }})
                                    </div>
                                @endforeach
                                @if($req->items->count() > 2)
                                    <span style="color: #667eea; font-size: 12px; font-weight: 600;">+{{ $req->items->count() - 2 }} more</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">
                                @if($req->status == 'pending_catering_incharge')
                                    <span style="background: #f59e0b; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">⏳ Pending Your Approval</span>
                                @elseif($req->status == 'catering_approved')
                                    <span style="background: #10b981; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">✓ Approved</span>
                                @elseif($req->status == 'items_issued')
                                    <span style="background: #3b82f6; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">📦 Items Issued</span>
                                @elseif($req->status == 'catering_staff_received')
                                    <span style="background: #8b5cf6; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">✅ Staff Received</span>
                                @elseif($req->status == 'pending_final_approval')
                                    <span style="background: #f97316; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">🔍 Needs Final Approval</span>
                                @elseif($req->status == 'supervisor_approved')
                                    <span style="background: #06b6d4; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">👔 Supervisor Approved</span>
                                @else
                                    <span style="background: #6c757d; color: white; padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $req->status)) }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px; font-size: 13px; color: #6c757d;">{{ $req->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 40px 20px; border-top: 2px solid #e9ecef; margin-top: 20px;">
            <svg style="width: 48px; height: 48px; color: #cbd5e0; margin-bottom: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p style="color: #718096; margin: 0; font-size: 14px;">No recent staff requests</p>
        </div>
        @endif
    </div>

</div>

<!-- Recent Stock Movements (Authenticated Dispatches) -->
@if($recentAuthenticatedRequests->count() > 0)
<div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; margin-top: 32px;">
    <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">📋 Recent Stock Movements</h3>
            <p style="font-size: 13px; color: #6b7280; margin: 4px 0 0 0;">History of authenticated stock dispatches</p>
        </div>
        <div style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
            {{ $recentAuthenticatedRequests->count() }} movements
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Request ID</th>
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Flight</th>
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Requested By</th>
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Items</th>
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 20px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentAuthenticatedRequests as $req)
                <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 700; color: #1f2937; font-size: 15px;">#{{ $req->id }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $req->flight->flight_number }}</div>
                        <div style="color: #6b7280; font-size: 12px; margin-top: 2px;">
                            {{ $req->flight->origin }} → {{ $req->flight->destination }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="color: #1f2937; font-weight: 500; font-size: 14px;">{{ $req->requester->name }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($req->items->take(2) as $item)
                            <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-size: 11px; color: #4b5563; font-weight: 600;">
                                {{ $item->product->name }} ({{ $item->quantity }})
                            </span>
                            @endforeach
                            @if($req->items->count() > 2)
                            <span style="background: #e5e7eb; padding: 4px 8px; border-radius: 6px; font-size: 11px; color: #6b7280; font-weight: 600;">
                                +{{ $req->items->count() - 2 }} more
                            </span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($req->status == 'security_authenticated')
                        <span style="background: #10b981; color: white; padding: 6px 12px; border-radius: 10px; font-size: 12px; font-weight: 600;">
                            ✓ Authenticated
                        </span>
                        @elseif($req->status == 'ramp_dispatched')
                        <span style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 10px; font-size: 12px; font-weight: 600;">
                            🚛 Dispatched
                        </span>
                        @elseif($req->status == 'loaded')
                        <span style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 10px; font-size: 12px; font-weight: 600;">
                            ✈️ Loaded
                        </span>
                        @elseif($req->status == 'delivered')
                        <span style="background: #059669; color: white; padding: 6px 12px; border-radius: 10px; font-size: 12px; font-weight: 600;">
                            📦 Delivered
                        </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="color: #1f2937; font-weight: 500; font-size: 14px;">
                            {{ $req->updated_at->format('M d, Y') }}
                        </div>
                        <div style="color: #6b7280; font-size: 12px; margin-top: 2px;">
                            {{ $req->updated_at->format('H:i') }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
function showApproveConfirmation(requestId, flightNumber) {
    // Show custom confirmation modal
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Approve Request?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Flight:</strong> ${flightNumber}</p>
            <p style="margin:0 0 8px 0;">✓ Stock itatolewa kutoka catering inventory</p>
            <p style="margin:0 0 8px 0;">✓ Stock itagawiwa kwa Catering Staff</p>
            <p style="margin:0;color:#dc3545;"><strong>⚠️ Haiwezi kurudishwa</strong></p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeApproveModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitApproveForm(${requestId})" style="background:#28a745;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✓ Approve</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeApproveModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentConfirmDiv = confirmDiv;
}

function closeApproveModal() {
    const overlay = document.getElementById('modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentConfirmDiv) window.currentConfirmDiv.remove();
}

function submitApproveForm(requestId) {
    closeApproveModal();
    document.getElementById('approve-form-' + requestId).submit();
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.style.background === 'rgba(0, 0, 0, 0.5)') {
        event.target.style.display = 'none';
    }
});
</script>

@endsection
