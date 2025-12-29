@extends('layouts.app')

@section('title', 'Catering Staff Dashboard')

@section('content')
<div class="content-header">
    <h1>Catering Staff Dashboard</h1>
    <p>Manage your catering requests</p>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Total Requests -->
    <a href="{{ route('catering-staff.requests.index') }}" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor:pointer;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $totalRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Total Requests</div>
        </div>
    </div>
    </a>

    <!-- Pending Requests -->
    <a href="{{ route('catering-staff.requests.index', ['filter' => 'pending']) }}" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor:pointer;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $pendingRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Pending</div>
        </div>
    </div>
    </a>

    <!-- Approved Requests -->
    <a href="{{ route('catering-staff.requests.index', ['filter' => 'approved']) }}" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor:pointer;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $approvedRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Approved</div>
        </div>
    </div>
    </a>

    <!-- Rejected Requests -->
    <a href="{{ route('catering-staff.requests.index', ['filter' => 'rejected']) }}" style="text-decoration:none;color:inherit;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s; cursor:pointer;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $rejectedRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Rejected</div>
        </div>
    </div>
    </a>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <!-- Core Catering Staff Actions (Always Visible) -->
    <a href="{{ route('catering-staff.requests.create') }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Create New Request</span>
    </a>

    <a href="{{ route('catering-staff.requests.items-to-receive') }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(74, 222, 128, 0.4); transition: all 0.3s; position: relative;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <span>Receive Items</span>
        @if(isset($itemsToReceive) && $itemsToReceive > 0)
        <span style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);">{{ $itemsToReceive }}</span>
        @endif
    </a>

    <a href="{{ route('catering-staff.requests.index', ['filter' => 'pending']) }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>View Pending</span>
    </a>

    <a href="{{ route('catering-staff.requests.index', ['filter' => 'approved']) }}" style="display: flex; align-items: center; gap: 12px; padding: 18px 24px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(67, 233, 123, 0.4); transition: all 0.3s;">
        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Approved Requests</span>
    </a>
    
    <!-- DYNAMIC PERMISSION-BASED ACTIONS (Auto-appear when permissions added) -->
    <x-permission-actions :exclude="['create catering request', 'view own catering requests', 'receive approved items', 'view product list']" />
</div>

<!-- My Recent Requests -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📋 My Recent Requests</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Track your latest catering requests and their status</p>
        </div>
        <a href="{{ route('catering-staff.requests.index') }}" 
           style="display:inline-flex;align-items:center;gap:6px;background:#eff6ff;color:#2563eb;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s;">
            View All
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    
    @if($myRecentRequests->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requested Date</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myRecentRequests as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#1f2937;font-size:15px;">#{{ $request->id }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ $request->flight->origin }} → {{ $request->flight->destination }}
                        </div>
                        <div style="color:#9ca3af;font-size:11px;margin-top:2px;">
                            Departs: {{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:inline-flex;align-items:center;gap:6px;background:#f3f4f6;padding:4px 10px;border-radius:6px;">
                            <svg style="width:14px;height:14px;color:#6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span style="color:#4b5563;font-size:13px;font-weight:600;">{{ $request->items->count() }} items</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">
                            {{ $request->created_at->format('M d, Y') }}
                        </div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ $request->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        @php
                            $statusConfig = [
                                'pending_inventory' => ['label' => 'Pending Inventory', 'bg' => '#fef3c7', 'color' => '#92400e'],
                                'pending_supervisor' => ['label' => 'Pending Supervisor', 'bg' => '#fed7aa', 'color' => '#9a3412'],
                                'supervisor_approved' => ['label' => 'Supervisor Approved', 'bg' => '#dbeafe', 'color' => '#1e40af'],
                                'sent_to_security' => ['label' => 'Sent to Security', 'bg' => '#e0e7ff', 'color' => '#4338ca'],
                                'security_approved' => ['label' => 'Security Approved', 'bg' => '#ddd6fe', 'color' => '#5b21b6'],
                                'catering_approved' => ['label' => 'Approved', 'bg' => '#d1fae5', 'color' => '#065f46'],
                                'rejected' => ['label' => 'Rejected', 'bg' => '#fee2e2', 'color' => '#991b1b'],
                                'received' => ['label' => 'Received', 'bg' => '#e0e7ff', 'color' => '#4338ca'],
                            ];
                            $config = $statusConfig[$request->status] ?? ['label' => ucwords(str_replace('_', ' ', $request->status)), 'bg' => '#f3f4f6', 'color' => '#374151'];
                        @endphp
                        <span style="display:inline-block;padding:5px 12px;border-radius:12px;font-size:12px;font-weight:600;background:{{ $config['bg'] }};color:{{ $config['color'] }};">
                            {{ $config['label'] }}
                        </span>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <a href="{{ route('catering-staff.requests.show', $request) }}" 
                               style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>
                            @if($request->status === 'catering_approved')
                            <form method="POST" action="{{ route('catering-staff.requests.send-to-ramp', $request) }}" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Send Request #{{ $request->id }} to Ramp Dispatcher for dispatch?')" 
                                   style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:8px 16px;border-radius:8px;border:none;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
                                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Send to Ramp
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">📝</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Requests Yet</h4>
        <p style="color:#6b7280;font-size:14px;margin-bottom:20px;">You haven't created any catering requests yet.</p>
        <a href="{{ route('catering-staff.requests.create') }}" 
           style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Your First Request
        </a>
    </div>
    @endif
</div>

<!-- Items Ready for Receipt from Inventory -->
@if($readyForCollection->count() > 0)
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📦 Items Ready to Receive</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Inventory Personnel has issued items - receive them to proceed</p>
        </div>
        <div style="background:#dbeafe;color:#1e40af;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $readyForCollection->count() }} ready
        </div>
    </div>
    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Approved Date</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($readyForCollection as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#1f2937;font-size:15px;">#{{ $request->id }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ $request->flight->origin }} → {{ $request->flight->destination }}
                        </div>
                        <div style="color:#9ca3af;font-size:11px;margin-top:2px;">
                            Departs: {{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;margin-bottom:6px;">{{ $request->items->count() }} items:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                            @foreach($request->items->take(3) as $item)
                            <span style="background:#f3f4f6;padding:4px 8px;border-radius:6px;font-size:11px;color:#4b5563;font-weight:600;">
                                {{ $item->product->name }} ({{ $item->approved_quantity ?? $item->quantity_requested }})
                            </span>
                            @endforeach
                            @if($request->items->count() > 3)
                            <span style="background:#e5e7eb;padding:4px 8px;border-radius:6px;font-size:11px;color:#6b7280;font-weight:600;">
                                +{{ $request->items->count() - 3 }} more
                            </span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">
                            {{ $request->updated_at->format('M d, Y') }}
                        </div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ $request->updated_at->format('H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <form method="POST" action="{{ route('catering-staff.requests.receive-items', $request) }}" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Receive items for Request #{{ $request->id }}? This will send the request to Catering Incharge for final approval.')" 
                                   style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:8px 16px;border-radius:8px;border:none;font-size:13px;font-weight:600;cursor:pointer;">
                                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Receive Items
                                </button>
                            </form>
                            <a href="{{ route('catering-staff.requests.show', $request) }}" 
                               style="display:inline-flex;align-items:center;gap:6px;background:#6b7280;color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Upcoming Flights -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">✈️ Upcoming Flights</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Next 7 days - Create requests for scheduled flights</p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="background:#eff6ff;color:#1e40af;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
                {{ $upcomingFlights->count() }} flights
            </div>
            <a href="{{ route('catering-staff.flights.create') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s;">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Flight
            </a>
        </div>
    </div>
    
    @if($upcomingFlights->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Number</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Airline</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Arrival</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upcomingFlights as $flight)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#1f2937;font-size:15px;">{{ $flight->flight_number }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#4b5563;font-size:14px;">{{ $flight->airline }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $flight->origin }}</span>
                            <svg style="width:16px;height:16px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $flight->destination }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y') }}
                        </div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">
                            {{ \Carbon\Carbon::parse($flight->arrival_time)->format('M d, Y') }}
                        </div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        @php
                            $statusColors = [
                                'scheduled' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Scheduled'],
                                'delayed' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Delayed'],
                                'departed' => ['bg' => '#e0e7ff', 'text' => '#4338ca', 'label' => 'Departed'],
                                'arrived' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Arrived'],
                                'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Cancelled'],
                            ];
                            $status = $statusColors[$flight->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => ucfirst($flight->status)];
                        @endphp
                        <span style="display:inline-block;padding:5px 12px;border-radius:12px;font-size:12px;font-weight:600;background:{{ $status['bg'] }};color:{{ $status['text'] }};">
                            {{ $status['label'] }}
                        </span>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($flight->status === 'scheduled')
                        <a href="{{ route('catering-staff.requests.create') }}?flight_id={{ $flight->id }}" 
                           style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Request Items
                        </a>
                        @else
                        <span style="color:#9ca3af;font-size:13px;">Not available</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">📅</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Upcoming Flights</h4>
        <p style="color:#6b7280;font-size:14px;margin-bottom:20px;">There are no flights scheduled in the next 7 days.</p>
        <p style="color:#9ca3af;font-size:13px;">Contact operations if this looks incorrect.</p>
    </div>
    @endif
</div>

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
</style>

<!-- Stock Alerts -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;margin-top:32px;">
    
    <!-- Out of Stock Alert -->
    @if($outOfStockItems->count() > 0)
    <div style="background:#fee2e2;border-left:4px solid #dc2626;border-radius:12px;padding:18px 20px;box-shadow:0 2px 8px rgba(220,38,38,0.15);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <svg style="width:22px;height:22px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <div>
                <h4 style="font-size:15px;font-weight:700;color:#991b1b;margin:0;">🚫 Out of Stock</h4>
                <p style="font-size:12px;color:#7f1d1d;margin:2px 0 0 0;">{{ $outOfStockItems->count() }} items unavailable</p>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            @foreach($outOfStockItems->take(3) as $item)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#991b1b;font-weight:600;">
                {{ $item->name }}
            </span>
            @endforeach
            @if($outOfStockItems->count() > 3)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#991b1b;font-weight:600;">
                +{{ $outOfStockItems->count() - 3 }}
            </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Low Stock Alert -->
    @if($lowStockItems->count() > 0)
    <div style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:12px;padding:18px 20px;box-shadow:0 2px 8px rgba(245,158,11,0.15);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <svg style="width:22px;height:22px;color:#f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h4 style="font-size:15px;font-weight:700;color:#92400e;margin:0;">⚠️ Low Stock</h4>
                <p style="font-size:12px;color:#78350f;margin:2px 0 0 0;">{{ $lowStockItems->count() }} below minimum</p>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            @foreach($lowStockItems->take(3) as $item)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#92400e;font-weight:600;">
                {{ $item->name }} ({{ $item->total_available }})
            </span>
            @endforeach
            @if($lowStockItems->count() > 3)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#92400e;font-weight:600;">
                +{{ $lowStockItems->count() - 3 }}
            </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Near Empty Alert -->
    @if($nearEmptyItems->count() > 0)
    <div style="background:#fef9c3;border-left:4px solid #eab308;border-radius:12px;padding:18px 20px;box-shadow:0 2px 8px rgba(234,179,8,0.15);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <svg style="width:22px;height:22px;color:#eab308;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
            </svg>
            <div>
                <h4 style="font-size:15px;font-weight:700;color:#854d0e;margin:0;">📉 Near Empty</h4>
                <p style="font-size:12px;color:#713f12;margin:2px 0 0 0;">{{ $nearEmptyItems->count() }} running low</p>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            @foreach($nearEmptyItems->take(3) as $item)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#854d0e;font-weight:600;">
                {{ $item->name }} ({{ $item->total_available }})
            </span>
            @endforeach
            @if($nearEmptyItems->count() > 3)
            <span style="background:white;padding:5px 10px;border-radius:6px;font-size:11px;color:#854d0e;font-weight:600;">
                +{{ $nearEmptyItems->count() - 3 }}
            </span>
            @endif
        </div>
    </div>
    @endif
</div>

@if($unifiedLowStockCount == 0 && $unifiedStock->count() > 0)
<div style="background:#d1fae5;border-left:4px solid #059669;border-radius:12px;padding:18px 20px;margin-top:32px;box-shadow:0 2px 8px rgba(5,150,105,0.15);">
    <div style="display:flex;align-items:center;gap:10px;">
        <svg style="width:22px;height:22px;color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h4 style="font-size:15px;font-weight:700;color:#065f46;margin:0;">✓ All Stock Levels Healthy</h4>
            <p style="font-size:12px;color:#047857;margin:2px 0 0 0;">All products are above minimum stock requirements</p>
        </div>
    </div>
</div>
@endif

<!-- UNIFIED CATERING STOCK - All Sources Combined -->
@if($unifiedStock->count() > 0)
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div style="flex:1;">
                <h3 style="font-size:22px;font-weight:700;color:#1a1a1a;margin:0;display:flex;align-items:center;gap:10px;">
                    📦 My Catering Department Stock
                </h3>
                <p style="font-size:13px;color:#6b7280;margin:8px 0 0 0;">Complete inventory from all sources - Ready for flight operations</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="background:#eff6ff;color:#1e40af;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;">
                    {{ $unifiedStock->count() }} products
                </div>
                @if($unifiedLowStockCount > 0)
                <div style="background:#fef3c7;color:#92400e;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;">
                    {{ $unifiedLowStockCount }} low stock
                </div>
                @endif
            </div>
        </div>
        
        <!-- Stock Source Legend -->
        <div style="display:flex;gap:24px;padding:12px 16px;background:#f9fafb;border-radius:10px;border:1px solid #e5e7eb;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:10px;height:10px;border-radius:50%;background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"></div>
                <span style="font-size:12px;color:#374151;font-weight:600;">Mini Stock</span>
                <span style="font-size:11px;color:#6b7280;">(From Inventory)</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:10px;height:10px;border-radius:50%;background:linear-gradient(135deg, #10b981 0%, #059669 100%);"></div>
                <span style="font-size:12px;color:#374151;font-weight:600;">Workflow Stock</span>
                <span style="font-size:11px;color:#6b7280;">(Security → Catering Incharge)</span>
            </div>
        </div>
    </div>
    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Product</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">SKU</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Category</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Mini Stock</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Workflow Stock</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Total Available</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Reorder Level</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unifiedStock as $stock)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $stock->name }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <code style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-size:12px;color:#4b5563;">{{ $stock->sku }}</code>
                    </td>
                    <td style="padding:16px 20px;">
                        <span style="background:#f3f4f6;color:#374151;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">{{ $stock->category }}</span>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($stock->mini_stock > 0)
                        <div style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg, #eff6ff, #dbeafe);padding:8px 12px;border-radius:8px;border:1px solid #bfdbfe;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#3b82f6;"></div>
                            <span style="font-weight:700;color:#1e40af;font-size:15px;">{{ $stock->mini_stock }}</span>
                        </div>
                        @else
                        <span style="color:#d1d5db;font-size:14px;">—</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($stock->workflow_stock > 0)
                        <div style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg, #d1fae5, #a7f3d0);padding:8px 12px;border-radius:8px;border:1px solid #6ee7b7;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#10b981;"></div>
                            <span style="font-weight:700;color:#065f46;font-size:15px;">{{ $stock->workflow_stock }}</span>
                        </div>
                        @else
                        <span style="color:#d1d5db;font-size:14px;">—</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="background:#f9fafb;padding:10px 16px;border-radius:10px;border:2px solid #e5e7eb;">
                            <div style="font-weight:700;color:#1f2937;font-size:20px;">{{ $stock->total_available }}</div>
                            <div style="font-size:10px;color:#6b7280;margin-top:2px;text-transform:uppercase;letter-spacing:0.5px;">units</div>
                        </div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="color:#6b7280;font-size:14px;">{{ $stock->reorder_level }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($stock->is_low_stock)
                        <span style="background:#fef3c7;color:#92400e;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                            ⚠️ Low Stock
                        </span>
                        @else
                        <span style="background:#d1fae5;color:#065f46;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                            ✓ In Stock
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">📦</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Stock Available</h4>
        <p style="color:#6b7280;font-size:14px;">No products in catering stock yet. Request items from inventory.</p>
    </div>
    @endif
</div>

<!-- Stock Details Modal -->
<div id="stockDetailsModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;" onclick="if(event.target === this) toggleStockDetails()">
    <div style="background:white;border-radius:16px;width:90%;max-width:1200px;max-height:90vh;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#2563eb,#1d4ed8);">
            <div>
                <h3 style="font-size:22px;font-weight:700;color:white;margin:0;">📦 Detailed Stock Information</h3>
                <p style="font-size:13px;color:#dbeafe;margin:4px 0 0 0;">Complete overview of your catering department inventory</p>
            </div>
            <button onclick="toggleStockDetails()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div style="padding:28px;max-height:calc(90vh - 150px);overflow-y:auto;">
            
            <!-- Summary Cards -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:28px;">
                <!-- Total Products -->
                <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);padding:20px;border-radius:12px;border:2px solid #bfdbfe;">
                    <div style="color:#1e40af;font-size:13px;font-weight:600;text-transform:uppercase;margin-bottom:8px;">Total Products</div>
                    <div style="font-size:32px;font-weight:700;color:#1e3a8a;">{{ $availableStock->count() }}</div>
                </div>
                
                <!-- Total Available Units -->
                <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);padding:20px;border-radius:12px;border:2px solid #6ee7b7;">
                    <div style="color:#065f46;font-size:13px;font-weight:600;text-transform:uppercase;margin-bottom:8px;">Total Available</div>
                    <div style="font-size:32px;font-weight:700;color:#064e3b;">{{ $availableStock->sum('total_available') }}</div>
                    <div style="color:#047857;font-size:11px;margin-top:4px;">units ready for flights</div>
                </div>
                
                <!-- Low Stock Items -->
                <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);padding:20px;border-radius:12px;border:2px solid #fcd34d;">
                    <div style="color:#92400e;font-size:13px;font-weight:600;text-transform:uppercase;margin-bottom:8px;">Low Stock</div>
                    <div style="font-size:32px;font-weight:700;color:#78350f;">{{ $lowStockItems->count() }}</div>
                    <div style="color:#b45309;font-size:11px;margin-top:4px;">items need restock</div>
                </div>
                
                <!-- Out of Stock -->
                <div style="background:linear-gradient(135deg,#fee2e2,#fecaca);padding:20px;border-radius:12px;border:2px solid #fca5a5;">
                    <div style="color:#991b1b;font-size:13px;font-weight:600;text-transform:uppercase;margin-bottom:8px;">Out of Stock</div>
                    <div style="font-size:32px;font-weight:700;color:#7f1d1d;">{{ $outOfStockItems->count() }}</div>
                    <div style="color:#b91c1c;font-size:11px;margin-top:4px;">items unavailable</div>
                </div>
            </div>

            <!-- Detailed Products List -->
            @if($availableStock->count() > 0)
            <div style="background:#f9fafb;border-radius:12px;padding:20px;">
                <h4 style="font-size:16px;font-weight:700;color:#374151;margin:0 0 16px 0;">📋 Product Details</h4>
                
                @foreach($availableStock as $stock)
                @php
                    $isOutOfStock = $stock->total_available == 0;
                    $isLow = $stock->total_available > 0 && $stock->total_available < $stock->min_stock;
                    $percentage = ($stock->total_available / max($stock->min_stock, 1)) * 100;
                    $used = $stock->total_received - $stock->total_available;
                @endphp
                
                <div style="background:white;border-radius:10px;padding:20px;margin-bottom:12px;border-left:4px solid {{ $isOutOfStock ? '#dc2626' : ($isLow ? '#f59e0b' : '#059669') }};box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;">
                        <!-- Product Info -->
                        <div>
                            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:4px;">Product</div>
                            <div style="font-weight:700;color:#1f2937;font-size:16px;margin-bottom:4px;">{{ $stock->name }}</div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <code style="background:#f3f4f6;padding:3px 8px;border-radius:4px;font-size:11px;color:#4b5563;">{{ $stock->sku }}</code>
                                <span style="background:#eff6ff;color:#1e40af;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:600;">{{ $stock->category }}</span>
                            </div>
                        </div>
                        
                        <!-- Quantity Info -->
                        <div>
                            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:8px;">Inventory</div>
                            <div style="display:flex;gap:16px;">
                                <div>
                                    <div style="font-size:11px;color:#6b7280;margin-bottom:2px;">Received</div>
                                    <div style="font-size:20px;font-weight:700;color:#4b5563;">{{ $stock->total_received }}</div>
                                </div>
                                <div>
                                    <div style="font-size:11px;color:#6b7280;margin-bottom:2px;">Available</div>
                                    <div style="font-size:20px;font-weight:700;color:{{ $isOutOfStock ? '#dc2626' : ($isLow ? '#f59e0b' : '#059669') }};">{{ $stock->total_available }}</div>
                                </div>
                                <div>
                                    <div style="font-size:11px;color:#6b7280;margin-bottom:2px;">Used</div>
                                    <div style="font-size:20px;font-weight:700;color:#9ca3af;">{{ $used }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status & Date -->
                        <div>
                            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:8px;">Status</div>
                            @if($isOutOfStock)
                            <div style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:8px;">
                                ❌ OUT OF STOCK
                            </div>
                            @elseif($isLow)
                            <div style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:8px;">
                                ⚠️ LOW STOCK ({{ number_format($percentage, 0) }}%)
                            </div>
                            @else
                            <div style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:8px;">
                                ✓ SUFFICIENT ({{ number_format($percentage, 0) }}%)
                            </div>
                            @endif
                            
                            @if($stock->last_restocked)
                            <div style="font-size:11px;color:#6b7280;">
                                Last restocked: <strong>{{ \Carbon\Carbon::parse($stock->last_restocked)->diffForHumans() }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div style="margin-top:12px;">
                        <div style="width:100%;height:8px;background:#e5e7eb;border-radius:4px;overflow:hidden;">
                            <div style="width:{{ min($percentage, 100) }}%;height:100%;background:{{ $isOutOfStock ? '#dc2626' : ($isLow ? '#f59e0b' : '#059669') }};transition:width 0.3s;"></div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:10px;color:#9ca3af;">
                            <span>0</span>
                            <span>Min: {{ $stock->min_stock }}</span>
                            <span>{{ $stock->total_available }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

<script>
function toggleStockDetails() {
    const modal = document.getElementById('stockDetailsModal');
    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>

@endsection
