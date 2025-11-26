@extends('layouts.app')

@section('title', 'Security Staff Dashboard')

@section('content')
<div class="content-header">
    <h1>Security Staff Dashboard</h1>
    <p>Security verification and authentication</p>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $pendingVerification }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Pending Verification</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $verifiedToday }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Verified Today</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $blockedToday }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Blocked Today</div>
        </div>
    </div>

    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px; transition: all 0.3s ease;">
        <div style="width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1; margin-bottom: 6px;">{{ $todayFlights->count() }}</div>
            <div style="font-size: 14px; color: #6c757d; font-weight: 500;">Today's Flights</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <a href="{{ route('security-staff.requests.awaiting-authentication') }}" style="display: flex; align-items: center; gap: 14px; padding: 18px 22px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 14px; color: white; text-decoration: none; font-weight: 600; font-size: 15px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transition: all 0.3s ease;">
        <svg style="width: 26px; height: 26px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Awaiting Authentication</span>
    </a>
</div>

<!-- Orders Pending Security Check -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">🔒 Orders Pending Security Check</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Requests awaiting security authentication</p>
        </div>
        <div style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $ordersToVerify->count() }} pending
        </div>
    </div>

    @if($ordersToVerify->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requester</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersToVerify as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#2563eb;font-size:16px;">#{{ $request->id }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">
                            {{ $request->flight->origin }} → {{ $request->flight->destination }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('H:i A') }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <span style="background:#dbeafe;color:#1e40af;padding:6px 12px;border-radius:12px;font-size:13px;font-weight:700;">
                            {{ $request->items->count() }} items
                        </span>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:13px;">{{ $request->requester->name }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $request->requester->email }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <a href="{{ route('security-staff.requests.show', $request) }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;transition:background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Verify
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">✅</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">All Clear!</h4>
        <p style="color:#6b7280;font-size:14px;">No orders pending security verification at the moment.</p>
    </div>
    @endif
</div>

<!-- Today's Flights -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">✈️ Today's Flight Schedule</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Flights scheduled for {{ now()->format('l, F d, Y') }}</p>
        </div>
        <div style="background:#eff6ff;color:#1e40af;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $todayFlights->count() }} flights
        </div>
    </div>

    @if($todayFlights->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight #</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Airline</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Passengers</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requests</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Security Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todayFlights as $flight)
                @php
                    $hasPendingRequests = $flight->requests->where('status', 'sent_to_security')->count() > 0;
                    $hasApprovedRequests = $flight->requests->where('status', 'security_approved')->count() > 0;
                @endphp
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#2563eb;font-size:16px;">{{ $flight->flight_number }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:13px;">{{ $flight->airline }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $flight->aircraft_type }}</div>
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
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i A') }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ \Carbon\Carbon::parse($flight->departure_time)->diffForHumans() }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-weight:700;font-size:16px;color:#4b5563;">{{ $flight->passenger_count }}</div>
                        <div style="font-size:10px;color:#9ca3af;margin-top:2px;">passengers</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($flight->requests->count() > 0)
                        <span style="background:#dbeafe;color:#1e40af;padding:6px 12px;border-radius:12px;font-size:13px;font-weight:700;">
                            {{ $flight->requests->count() }}
                        </span>
                        @else
                        <span style="color:#9ca3af;font-size:12px;">None</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($hasPendingRequests)
                        <span style="background:#fef3c7;color:#92400e;padding:6px 12px;border-radius:12px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            PENDING
                        </span>
                        @elseif($hasApprovedRequests || $flight->requests->count() > 0)
                        <span style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:12px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            CLEARED
                        </span>
                        @else
                        <span style="background:#f3f4f6;color:#6b7280;padding:6px 12px;border-radius:12px;font-size:11px;font-weight:600;">
                            NO REQUESTS
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
        <div style="font-size:48px;margin-bottom:16px;">✈️</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Flights Today</h4>
        <p style="color:#6b7280;font-size:14px;">There are no flights scheduled for today.</p>
    </div>
    @endif
</div>

<!-- Recent Stock Movements (Authentication History) -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📋 Recent Stock Movements</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">History of authenticated stock dispatches</p>
        </div>
        <div style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $recentStockMovements->count() }} movements
        </div>
    </div>

    @if($recentStockMovements->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Date & Time</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Product</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Quantity</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Type</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requested By</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Authenticated By</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentStockMovements as $movement)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $movement->created_at->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">{{ $movement->created_at->format('H:i A') }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $movement->product->name }}</div>
                        <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:11px;color:#4b5563;margin-top:2px;display:inline-block;">{{ $movement->product->sku }}</code>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-weight:700;font-size:18px;color:#ef4444;">{{ $movement->quantity }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        @if($movement->type === 'issued')
                        <span style="background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">
                            ⬇️ ISSUED
                        </span>
                        @elseif($movement->type === 'incoming')
                        <span style="background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">
                            ⬆️ INCOMING
                        </span>
                        @else
                        <span style="background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:12px;font-size:11px;font-weight:600;">
                            ↩️ RETURNED
                        </span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-size:13px;color:#4b5563;">{{ $movement->user->name }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $movement->user->email }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:inline-flex;align-items:center;gap:8px;background:#dbeafe;color:#1e40af;padding:8px 12px;border-radius:8px;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span style="font-weight:600;font-size:13px;">Security Staff</span>
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">{{ $movement->created_at->diffForHumans() }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <span style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:12px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            AUTHENTICATED
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">📋</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Stock Movements Yet</h4>
        <p style="color:#6b7280;font-size:14px;">Stock movement history will appear here once requests are authenticated.</p>
    </div>
    @endif
</div>
@endsection
