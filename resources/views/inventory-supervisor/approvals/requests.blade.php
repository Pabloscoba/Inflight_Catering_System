@extends('layouts.app')

@section('title', 'Pending Catering Requests')

@section('content')
<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
<div style="padding: 32px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Pending Catering Requests</h1>
                <p style="color: #718096; font-size: 16px; margin: 0;">Requests approved by Catering Incharge - awaiting your approval</p>
            </div>
            <a href="{{ route('inventory-supervisor.dashboard') }}" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">← Back to Dashboard</a>
        </div>
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

    <!-- Requests Table -->
    @if($requests->count() > 0)
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #1a202c; margin: 0 0 4px 0;">{{ $requests->total() }} Requests Awaiting Approval</h3>
            <p style="font-size: 13px; color: #718096; margin: 0;">These requests have been approved by Catering Incharge and need your approval before items can be issued</p>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Request ID</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Flight</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Requested By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Items</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Date</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">#{{ $request->id }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #212529;">
                            <div style="font-weight: 600;">{{ $request->flight->flight_number }}</div>
                            <div style="font-size: 12px; color: #6c757d;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requester->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">
                            <strong>{{ $request->items->count() }}</strong> items
                            <div style="font-size: 12px; color: #6c757d; margin-top: 4px;">
                                @foreach($request->items->take(2) as $item)
                                    • {{ $item->product->name }}<br>
                                @endforeach
                                @if($request->items->count() > 2)
                                    <span style="color: #667eea;">+{{ $request->items->count() - 2 }} more</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requested_date->format('M d, Y') }}</td>
                        <td style="padding: 14px;">
                            <span style="background: #10b981; color: white; padding: 6px 14px; border-radius: 12px; font-size: 12px; font-weight: 600;">✓ Catering Approved</span>
                        </td>
                        <td style="padding: 14px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('inventory-supervisor.requests.show', $request) }}" style="background: #4facfe; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    👁️ View
                                </a>
                                <button onclick="document.getElementById('approve-{{ $request->id }}').style.display='flex'" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    ✓ Approve
                                </button>
                                <button onclick="document.getElementById('reject-{{ $request->id }}').style.display='flex'" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    ✗ Reject
                                </button>
                            </div>

                            <!-- Approve Confirmation Modal -->
                            <div id="approve-{{ $request->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; animation: fadeIn 0.2s;" onclick="this.style.display='none'">
                                <div style="background: white; padding: 28px; border-radius: 16px; max-width: 520px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
                                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                            <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0; font-size: 20px; font-weight: 700; color: #1a202c;">Approve Request</h4>
                                            <p style="margin: 4px 0 0 0; font-size: 13px; color: #718096;">Request #{{ $request->id }}</p>
                                        </div>
                                    </div>
                                    <p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 0 0 24px 0;">Are you sure you want to approve this request? This will forward it to Inventory Personnel for issuing items.</p>
                                    <form action="{{ route('inventory-supervisor.requests.approve', $request) }}" method="POST">
                                        @csrf
                                        <div style="display: flex; gap: 12px;">
                                            <button type="button" onclick="document.getElementById('approve-{{ $request->id }}').style.display='none'" style="flex: 1; background: #e2e8f0; color: #475569; border: none; padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">Cancel</button>
                                            <button type="submit" style="flex: 1; background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(40, 167, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(40, 167, 69, 0.3)'">✓ Approve Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div id="reject-{{ $request->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; animation: fadeIn 0.2s;" onclick="this.style.display='none'">
                                <div style="background: white; padding: 28px; border-radius: 16px; max-width: 520px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
                                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                            <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 style="margin: 0; font-size: 20px; font-weight: 700; color: #1a202c;">Reject Request</h4>
                                            <p style="margin: 4px 0 0 0; font-size: 13px; color: #718096;">Request #{{ $request->id }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('inventory-supervisor.requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <div style="margin-bottom: 20px;">
                                            <label style="display: block; font-size: 14px; font-weight: 600; color: #4a5568; margin-bottom: 8px;">Rejection Reason *</label>
                                            <textarea name="rejection_reason" required placeholder="Provide detailed reason for rejection..." style="width: 100%; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px; min-height: 100px; font-family: inherit; transition: border-color 0.2s;" onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
                                        </div>
                                        <div style="display: flex; gap: 12px;">
                                            <button type="button" onclick="document.getElementById('reject-{{ $request->id }}').style.display='none'" style="flex: 1; background: #e2e8f0; color: #475569; border: none; padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">Cancel</button>
                                            <button type="submit" style="flex: 1; background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(220, 53, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.3)'">✗ Reject Request</button>
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

        <!-- Pagination -->
        <div style="margin-top: 24px;">
            {{ $requests->links() }}
        </div>
    </div>
    @else
    <div style="background: white; border-radius: 16px; padding: 60px 28px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <svg style="width: 80px; height: 80px; color: #cbd5e0; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 8px 0;">No Pending Requests</h3>
        <p style="color: #718096; font-size: 14px; margin: 0;">All requests have been processed. New requests will appear here once approved by Catering Incharge.</p>
    </div>
    @endif
</div>
@endsection
