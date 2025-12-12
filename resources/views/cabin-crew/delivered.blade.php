@extends('layouts.app')

@section('title', 'Delivered Requests')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>✅ Service History</h1>
            <p>Completed deliveries and served requests</p>
        </div>
        <a href="{{ route('cabin-crew.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Stats Summary -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-bottom:32px;">
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">✅</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $deliveredRequests->total() }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Total Completed</div>
        </div>
    </div>

    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📦</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">
                {{ $deliveredRequests->sum(function($req) { return $req->items->count(); }) }}
            </div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Total Items Served</div>
        </div>
    </div>
</div>

<!-- Delivered Requests List -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📋 Completed Services</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">History of delivered and served requests</p>
    </div>

    @if($deliveredRequests->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Type</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Completed At</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveredRequests as $request)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#667eea;font-size:14px;">#{{ $request->id }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#6b7280;">{{ $request->flight->airline ?? 'N/A' }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-size:13px;color:#4a5568;">
                            <span style="font-weight:600;">{{ $request->flight->origin }}</span>
                            <span style="color:#cbd5e0;margin:0 4px;">→</span>
                            <span style="font-weight:600;">{{ $request->flight->destination }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:18px;font-weight:700;color:#2563eb;">{{ $request->items->count() }}</div>
                        <div style="font-size:11px;color:#9ca3af;">products</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @php
                            $typeBadges = [
                                'meal' => ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'Meal'],
                                'product' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'label' => 'Product'],
                                'mixed' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'label' => 'Mixed']
                            ];
                            $badge = $typeBadges[$request->request_type ?? 'product'] ?? $typeBadges['product'];
                        @endphp
                        <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:6px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @php
                            $completedAt = $request->served_at ?? $request->delivered_at;
                        @endphp
                        @if($completedAt)
                        <div style="font-size:13px;color:#4a5568;font-weight:600;">{{ \Carbon\Carbon::parse($completedAt)->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ \Carbon\Carbon::parse($completedAt)->format('h:i A') }}</div>
                        @else
                        <span style="color:#9ca3af;">N/A</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($request->status === 'served')
                        <span style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;padding:6px 16px;border-radius:12px;font-size:12px;font-weight:600;">
                            🍽️ Served
                        </span>
                        @else
                        <span style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:6px 16px;border-radius:12px;font-size:12px;font-weight:600;">
                            ✅ Delivered
                        </span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <a href="{{ route('cabin-crew.products.view', $request) }}" style="background:#4299e1;color:white;border:none;padding:8px 16px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;display:inline-block;">
                            👁️ View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding:20px 28px;border-top:1px solid #f3f4f6;">
        {{ $deliveredRequests->links() }}
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">✅</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Completed Services</div>
        <div style="font-size:14px;color:#a0aec0;">No delivered or served requests yet</div>
    </div>
    @endif
</div>
@endsection
