@extends('layouts.app')

@section('title', 'Return Items')

@section('content')
<div style="max-width:1200px;margin:0 auto;">
    <h1 style="font-size:32px;font-weight:700;color:#1a202c;margin-bottom:8px;">↩️ Return Items</h1>
    <p style="color:#718096;margin-bottom:32px;">Return unused or defective products after flight service</p>

<!-- Active Returns -->
    @if($activeReturns->count() > 0)
    <div style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:16px;padding:24px;margin-bottom:24px;color:white;">
        <h2 style="font-size:20px;font-weight:700;margin:0 0 8px 0;">⏳ Returns In Progress</h2>
        <p style="margin:0 0 16px 0;opacity:0.9;font-size:14px;">{{ $activeReturns->count() }} item(s) being processed through Ramp → Security</p>
        
        <div style="display:grid;gap:12px;">
            @foreach($activeReturns as $return)
            <div style="background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);border-radius:12px;padding:16px;">
                <div style="display:flex;justify-content:space-between;align-items:start;gap:16px;">
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:16px;margin-bottom:4px;">{{ $return->product->name }}</div>
                        <div style="font-size:13px;opacity:0.9;margin-bottom:6px;">Flight: {{ $return->request->flight->flight_number }} | Qty: {{ $return->quantity_returned }}</div>
                        <div style="display:flex;gap:12px;font-size:12px;opacity:0.95;">
                            <span style="background:rgba(255,255,255,0.2);padding:4px 10px;border-radius:6px;">
                                @if($return->condition === 'good')
                                    ✅ Good Condition
                                @elseif($return->condition === 'damaged')
                                    ⚠️ Damaged
                                @else
                                    🚫 Expired
                                @endif
                            </span>
                        </div>
                        @if($return->reason)
                        <div style="margin-top:6px;font-size:12px;opacity:0.85;font-style:italic;">
                            💬 {{ Str::limit($return->reason, 60) }}
                        </div>
                        @endif
                    </div>
                    <div style="text-align:right;">
                        <div style="background:rgba(255,255,255,0.3);padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;margin-bottom:4px;">
                            @if($return->status === 'pending_ramp')
                                🚚 Pending Ramp
                            @elseif($return->status === 'received_by_ramp')
                                📦 Received by Ramp
                            @elseif($return->status === 'pending_security')
                                🔒 Pending Security
                            @endif
                        </div>
                        <div style="font-size:11px;opacity:0.8;">{{ $return->returned_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

<!-- Available Requests to Return Items -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">📦 Flights with Items to Return</h2>
        <p style="color:#718096;margin:0 0 24px 0;font-size:14px;">Select a request to initiate return process</p>

        @if($requests->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;">
                <thead>
                    <tr style="background:#f7fafc;border-bottom:2px solid #e2e8f0;">
                        <th style="padding:14px 20px;text-align:left;font-size:13px;font-weight:700;color:#2d3748;">Flight</th>
                        <th style="padding:14px 20px;text-align:left;font-size:13px;font-weight:700;color:#2d3748;">Route</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Items</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Status</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:16px 20px;">
                            <div style="font-weight:700;color:#1f2937;margin-bottom:4px;">{{ $request->flight->flight_number }}</div>
                            <div style="font-size:12px;color:#9ca3af;">{{ $request->flight->airline }}</div>
                        </td>
                        <td style="padding:16px 20px;">
                            <div style="font-weight:600;color:#374151;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                            <div style="font-size:12px;color:#9ca3af;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y H:i') }}</div>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <span style="background:#e0f2fe;color:#0369a1;padding:6px 12px;border-radius:12px;font-weight:600;font-size:13px;">
                                {{ $request->items->count() }} items
                            </span>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <span style="background:#d4edda;color:#155724;padding:6px 12px;border-radius:12px;font-weight:600;font-size:13px;">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <a href="{{ route('cabin-crew.returns.create', $request) }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Return Items
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="margin-top:24px;">
            {{ $requests->links() }}
        </div>
        @else
        <div style="text-align:center;padding:48px 20px;color:#9ca3af;">
            <div style="font-size:48px;margin-bottom:12px;">📭</div>
            <div style="font-size:16px;">No flights with items available for return</div>
        </div>
        @endif
    </div>

    <!-- Completed Returns History -->
    @if($completedReturns->count() > 0)
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">✅ Recently Completed Returns</h2>
        <p style="color:#718096;margin:0 0 24px 0;font-size:14px;">Last 10 authenticated returns</p>

        <div style="display:grid;gap:12px;">
            @foreach($completedReturns as $return)
            <div style="background:#f7fafc;border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:start;gap:16px;">
                <div style="flex:1;">
                    <div style="font-weight:700;color:#1f2937;margin-bottom:4px;">{{ $return->product->name }}</div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">
                        Flight {{ $return->request->flight->flight_number }} | Qty: {{ $return->quantity_returned }} | 
                        Verified by {{ $return->verifiedBy->name }}
                    </div>
                    <div style="display:flex;gap:10px;font-size:12px;">
                        <span style="background:{{ $return->condition === 'good' ? '#d4edda' : ($return->condition === 'damaged' ? '#fff3cd' : '#f8d7da') }};color:{{ $return->condition === 'good' ? '#155724' : ($return->condition === 'damaged' ? '#856404' : '#721c24') }};padding:4px 10px;border-radius:6px;font-weight:600;">
                            @if($return->condition === 'good')
                                ✅ Good
                            @elseif($return->condition === 'damaged')
                                ⚠️ Damaged
                            @else
                                🚫 Expired
                            @endif
                        </span>
                        @if($return->quantity_verified)
                        <span style="background:#e3f2fd;color:#0d47a1;padding:4px 10px;border-radius:6px;font-weight:600;">
                            📦 Verified: {{ $return->quantity_verified }}
                        </span>
                        @endif
                    </div>
                    @if($return->reason)
                    <div style="margin-top:6px;font-size:12px;color:#6b7280;font-style:italic;">
                        💬 {{ Str::limit($return->reason, 80) }}
                    </div>
                    @endif
                    @if($return->verification_notes)
                    <div style="margin-top:4px;font-size:11px;color:#9ca3af;">
                        🔍 Verification: {{ Str::limit($return->verification_notes, 80) }}
                    </div>
                    @endif
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="background:#d4edda;color:#155724;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;margin-bottom:4px;">
                        ✅ Authenticated
                    </div>
                    <div style="font-size:11px;color:#9ca3af;">{{ $return->verified_at->diffForHumans() }}</div>
                    @if($return->stock_adjusted)
                    <div style="margin-top:4px;font-size:10px;color:#059669;font-weight:600;">
                        📈 Stock Updated
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
