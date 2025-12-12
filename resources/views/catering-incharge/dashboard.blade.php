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
            📋 Approve Requests
        </a>
        <a href="{{ route('catering-incharge.requests.approved') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; display: block;">
            ✅ View Approved
        </a>
        <a href="{{ route('catering-incharge.receipts.stock-overview') }}" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 18px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3); transition: all 0.3s ease; display: block;">
            📊 Stock Overview
        </a>
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
                                <form action="{{ route('catering-incharge.requests.approve', $request) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approve this request? Stock will be allocated to Catering Staff.')" style="background: #28a745; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                                        ✓ Approve
                                    </button>
                                </form>
                                <button onclick="document.getElementById('reject-request-{{ $request->id }}').style.display='block'" style="background: #dc3545; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                    ✗ Reject
                                </button>
                            </div>
                            <!-- Reject Modal -->
                            <div id="reject-request-{{ $request->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
                                    <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Reject Request</h4>
                                    <form action="{{ route('catering-incharge.requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" required placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; min-height: 100px; margin-bottom: 16px;"></textarea>
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

    <!-- Low Stock Alert -->
    @if($lowStockItems->count() > 0)
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0;">
                <svg style="width: 24px; height: 24px; display: inline-block; vertical-align: middle; margin-right: 8px; color: #dc3545;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Low Catering Stock Alert
            </h3>
            <span style="background: #dc3545; color: white; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;">{{ $lowStockItems->count() }} items</span>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Available</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Total Received</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $stock)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $stock->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $stock->product->category->name }}</td>
                        <td style="padding: 14px;">
                            <span style="background: {{ $stock->quantity_available == 0 ? '#f8d7da' : '#fff3cd' }}; color: {{ $stock->quantity_available == 0 ? '#721c24' : '#856404' }}; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">
                                {{ $stock->quantity_available }}
                            </span>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $stock->quantity_received }}</td>
                        <td style="padding: 14px;">
                            @if($stock->quantity_available == 0)
                            <span style="background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">OUT OF STOCK</span>
                            @else
                            <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">LOW STOCK</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script>
// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.style.background === 'rgba(0, 0, 0, 0.5)') {
        event.target.style.display = 'none';
    }
});
</script>

@endsection
