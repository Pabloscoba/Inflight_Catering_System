@extends('layouts.app')

@section('title', 'Approved Requests')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>✅ Approved Requests</h1>
            <p>All requests approved by Catering Incharge and ready for Catering Staff collection</p>
        </div>
        <a href="{{ route('catering-incharge.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Stats Summary -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;margin-bottom:32px;">
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);display:flex;align-items:center;justify-content:center;">
                <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <div style="font-size:32px;font-weight:700;color:#1a1a1a;">{{ $requests->total() }}</div>
                <div style="font-size:14px;color:#6b7280;">Total Approved</div>
            </div>
        </div>
    </div>
</div>

<!-- Approved Requests Table -->
@if($requests->count() > 0)
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">Approved Requests History</h3>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Complete history of requests approved and ready for collection</p>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requested By</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Approved Date</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-size:18px;font-weight:700;color:#2563eb;">#{{ $request->id }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#1f2937;font-size:16px;">{{ $request->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $request->flight->airline }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->origin }}</span>
                            <svg style="width:14px;height:14px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            <span style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->destination }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('h:i A') }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->requester->name }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $request->requester->email }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <button onclick="document.getElementById('items-{{ $request->id }}').style.display='block'" style="background:#eff6ff;color:#1e40af;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                            <div style="font-size:20px;font-weight:700;">{{ $request->items->count() }}</div>
                            <div style="font-size:10px;text-transform:uppercase;">View Items</div>
                        </button>
                        
                        <!-- Items Modal -->
                        <div id="items-{{ $request->id }}" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.6);z-index:1000;padding:20px;overflow-y:auto;" onclick="this.style.display='none'">
                            <div style="max-width:800px;margin:40px auto;background:white;border-radius:16px;padding:28px;box-shadow:0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                                    <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">Request #{{ $request->id }} - Items</h3>
                                    <button onclick="document.getElementById('items-{{ $request->id }}').style.display='none'" style="background:#f3f4f6;border:none;width:32px;height:32px;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
                                </div>
                                <table style="width:100%;border-collapse:collapse;">
                                    <thead>
                                        <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                                            <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#374151;">Product</th>
                                            <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#374151;">Category</th>
                                            <th style="padding:12px;text-align:center;font-size:12px;font-weight:600;color:#374151;">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($request->items as $item)
                                        <tr style="border-bottom:1px solid #f3f4f6;">
                                            <td style="padding:12px;">
                                                <div style="font-weight:600;color:#1f2937;">{{ $item->product->name }}</div>
                                                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $item->product->sku }}</div>
                                            </td>
                                            <td style="padding:12px;">
                                                <span style="background:#eff6ff;color:#1e40af;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:600;">
                                                    {{ $item->product->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td style="padding:12px;text-align:center;">
                                                <span style="font-size:18px;font-weight:700;color:#2563eb;">{{ $item->quantity_approved ?? $item->quantity_requested }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">
                            @if($request->approved_date)
                                {{ $request->approved_date->format('M d, Y') }}
                            @else
                                <span style="color:#ef4444;">N/A</span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">
                            @if($request->approved_date)
                                {{ $request->approved_date->format('h:i A') }}
                            @else
                                <span style="color:#ef4444;">N/A</span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                            @if($request->approved_date)
                                {{ $request->approved_date->diffForHumans() }}
                            @else
                                <span style="color:#ef4444;">N/A</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <span style="background:#d1fae5;color:#065f46;padding:8px 14px;border-radius:12px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            APPROVED
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
    <div style="padding:20px 28px;border-top:1px solid #f3f4f6;">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@else
<div style="background:white;border-radius:16px;padding:60px 28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);text-align:center;">
    <div style="font-size:64px;margin-bottom:16px;">📋</div>
    <h3 style="font-size:20px;font-weight:600;color:#1a1a1a;margin:0 0 8px 0;">No Approved Requests</h3>
    <p style="font-size:14px;color:#6b7280;margin:0;">No requests have been approved yet.</p>
</div>
@endif

@endsection
