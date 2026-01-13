@extends('layouts.app')

@section('title', 'Ramp Dispatcher Dashboard')

@section('content')
<div class="content-header">
    <h1>Ramp Dispatcher Dashboard</h1>
    <p>Manage dispatch operations and loading</p>
</div>

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;margin-bottom:32px;">
    <!-- Ready for Dispatch -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;align-items:center;gap:20px;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $approvedOrders }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Ready for Dispatch</div>
        </div>
    </div>

    <!-- Dispatched Today -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;align-items:center;gap:20px;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $dispatchedToday }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Dispatched Today</div>
        </div>
    </div>

    <!-- Flights Today -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;align-items:center;gap:20px;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#fa709a 0%,#fee140 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $todayFlights }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Flights Today</div>
        </div>
    </div>

    <!-- Next 24 Hours -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;align-items:center;gap:20px;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $upcomingFlights->count() }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Next 24 Hours</div>
        </div>
    </div>
</div>

<!-- Quick Actions - DYNAMIC PERMISSION-BASED -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:32px;">
    <x-permission-actions :exclude="['view dispatch reports', 'view approved orders', 'mark items as dispatched', 'handover to flight crew']" />
</div>

<!-- Orders Ready for Dispatch -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📦 Orders Ready for Dispatch</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Approved requests sent by Catering Staff - Send to Flight Dispatcher for assessment</p>
        </div>
        <div style="background:#dbeafe;color:#1e40af;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $ordersToDispatch->count() }} orders
        </div>
    </div>
    
    @if($ordersToDispatch->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requester</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersToDispatch as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#1f2937;font-size:15px;">#{{ $request->id }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            Sent {{ $request->sent_to_ramp_at ? \Carbon\Carbon::parse($request->sent_to_ramp_at)->diffForHumans() : 'recently' }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ $request->flight->airline }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->origin }}</span>
                            <svg style="width:16px;height:16px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->destination }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">
                            {{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y') }}
                        </div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">
                            {{ \Carbon\Carbon::parse($request->flight->departure_time)->format('H:i') }}
                        </div>
                        @php
                            $hoursUntilDeparture = \Carbon\Carbon::parse($request->flight->departure_time)->diffInHours(now(), false);
                        @endphp
                        @if($hoursUntilDeparture > 0 && $hoursUntilDeparture < 24)
                        <div style="font-size:11px;color:#dc2626;font-weight:600;margin-top:4px;">
                            ⚠️ In {{ round($hoursUntilDeparture) }}h
                        </div>
                        @elseif($hoursUntilDeparture > 0)
                        <div style="font-size:11px;color:#059669;margin-top:4px;">
                            In {{ \Carbon\Carbon::parse($request->flight->departure_time)->diffForHumans() }}
                        </div>
                        @endif
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;margin-bottom:6px;">{{ $request->items->count() }} items:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                            @foreach($request->items->take(2) as $item)
                            <span style="background:#f3f4f6;padding:4px 8px;border-radius:6px;font-size:11px;color:#4b5563;font-weight:600;">
                                {{ $item->product->name }} ({{ $item->approved_quantity ?? $item->quantity_requested }})
                            </span>
                            @endforeach
                            @if($request->items->count() > 2)
                            <span style="background:#e5e7eb;padding:4px 8px;border-radius:6px;font-size:11px;color:#6b7280;font-weight:600;">
                                +{{ $request->items->count() - 2 }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="color:#1f2937;font-weight:500;font-size:14px;">{{ $request->requester->name }}</div>
                        <div style="color:#6b7280;font-size:12px;margin-top:2px;">Catering Staff</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <button type="button" onclick="showDispatchConfirmation({{ $request->id }}, '{{ $request->flight->flight_number }}', {{ $request->items->count() }})" 
                               style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:10px 18px;border-radius:8px;border:none;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Send
                        </button>
                        <form id="dispatch-form-{{ $request->id }}" method="POST" action="{{ route('ramp-dispatcher.requests.dispatch', $request) }}" style="display:none;">
                            @csrf
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">📭</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Orders Ready</h4>
        <p style="color:#6b7280;font-size:14px;margin-bottom:20px;">There are no requests ready for dispatch at the moment.</p>
        <p style="color:#9ca3af;font-size:13px;">Orders will appear here when Catering Staff sends approved requests.</p>
    </div>
    @endif
</div>

<!-- Upcoming Flights -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-top:32px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">✈️ Upcoming Flights</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Scheduled flights for the next 7 days</p>
        </div>
        <div style="background:#eff6ff;color:#1e40af;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $upcomingFlights->count() }} flights
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
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requests</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
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
                        @php
                            $hoursUntil = \Carbon\Carbon::parse($flight->departure_time)->diffInHours(now(), false);
                        @endphp
                        @if($hoursUntil > 0 && $hoursUntil < 24)
                        <div style="font-size:11px;color:#dc2626;font-weight:600;margin-top:4px;">
                            ⏰ In {{ round($hoursUntil) }}h
                        </div>
                        @elseif($hoursUntil > 0)
                        <div style="font-size:11px;color:#059669;margin-top:4px;">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->diffForHumans() }}
                        </div>
                        @endif
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
                            $readyRequests = $flight->requests->where('status', 'ready_for_dispatch')->count();
                            $dispatchedRequests = $flight->requests->where('status', 'dispatched')->count();
                            $totalRequests = $flight->requests->count();
                        @endphp
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @if($readyRequests > 0)
                            <span style="display:inline-block;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600;background:#dbeafe;color:#1e40af;">
                                {{ $readyRequests }} Ready
                            </span>
                            @endif
                            @if($dispatchedRequests > 0)
                            <span style="display:inline-block;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600;background:#d1fae5;color:#065f46;">
                                {{ $dispatchedRequests }} Dispatched
                            </span>
                            @endif
                            @if($totalRequests === 0)
                            <span style="display:inline-block;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600;background:#f3f4f6;color:#6b7280;">
                                No Requests
                            </span>
                            @endif
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">✈️</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">No Upcoming Flights</h4>
        <p style="color:#6b7280;font-size:14px;margin-bottom:20px;">There are no scheduled flights in the next 7 days.</p>
        <p style="color:#9ca3af;font-size:13px;">Flights will appear here once they are scheduled.</p>
    </div>
    @endif
</div>

<script>
function showDispatchConfirmation(requestId, flightNumber, itemsCount) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Send to Flight Dispatcher?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Request #${requestId}</strong></p>
            <p style="margin:0 0 8px 0;"><strong>Flight:</strong> ${flightNumber}</p>
            <p style="margin:0 0 8px 0;"><strong>Items:</strong> ${itemsCount}</p>
            <p style="margin:0;">Peleka kwa Flight Dispatcher kwa ajili ya assessment?</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeDispatchModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitDispatchForm(${requestId})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Send</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'dispatch-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeDispatchModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentDispatchConfirmDiv = confirmDiv;
}

function closeDispatchModal() {
    const overlay = document.getElementById('dispatch-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentDispatchConfirmDiv) window.currentDispatchConfirmDiv.remove();
}

function submitDispatchForm(requestId) {
    closeDispatchModal();
    document.getElementById('dispatch-form-' + requestId).submit();
}
</script>

@endsection
