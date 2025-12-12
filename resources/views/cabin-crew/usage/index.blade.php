@extends('layouts.app')

@section('title', 'Usage Tracking')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>📊 Usage Tracking</h1>
            <p>Track and manage product usage during flights</p>
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
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📦</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $requests->total() }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Total Requests</div>
        </div>
    </div>
</div>

<!-- Requests List -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📋 All Requests</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Track product usage and manage inventory</p>
    </div>

    @if($requests->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;" colspan="5">Request Details & Product Usage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                @php
                    $totalUsed = $request->items->sum('quantity_used');
                    $totalApproved = $request->items->sum(fn($i) => $i->quantity_approved ?? $i->quantity_requested);
                    $usagePercent = $totalApproved > 0 ? round(($totalUsed / $totalApproved) * 100, 1) : 0;
                    $hasUsage = $totalUsed > 0;
                @endphp
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:16px 20px;" colspan="5">
                        <div style="display:flex;justify-content:space-between;align-items:start;gap:16px;">
                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                                    <div style="font-weight:700;color:#667eea;font-size:14px;">#{{ $request->id }}</div>
                                    <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $request->flight->flight_number }}</div>
                                    <div style="font-size:12px;color:#6b7280;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                                </div>
                                
                                @if($hasUsage)
                                <!-- Product Usage Details -->
                                <div style="background:#f9fafb;border-radius:8px;padding:12px;margin-top:8px;">
                                    <div style="font-size:12px;font-weight:600;color:#4b5563;margin-bottom:8px;">📦 Product Usage:</div>
                                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:8px;">
                                        @foreach($request->items->where('quantity_used', '>', 0) as $item)
                                        <div style="background:white;border-radius:6px;padding:10px;border-left:3px solid #38a169;">
                                            <div style="font-weight:600;color:#1f2937;font-size:13px;margin-bottom:4px;">{{ $item->product->name }}</div>
                                            <div style="display:flex;gap:12px;font-size:12px;color:#6b7280;">
                                                <span>✅ Used: <strong style="color:#38a169;">{{ $item->quantity_used ?? 0 }}</strong></span>
                                                <span>📋 Approved: <strong>{{ $item->quantity_approved ?? $item->quantity_requested }}</strong></span>
                                            </div>
                                            @if($item->usage_notes)
                                            <div style="margin-top:4px;font-size:11px;color:#9ca3af;">💬 {{ Str::limit($item->usage_notes, 50) }}</div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;">
                                        Total Usage: <strong style="color:#38a169;">{{ $totalUsed }} / {{ $totalApproved }}</strong> ({{ $usagePercent }}%)
                                    </div>
                                </div>
                                @else
                                <div style="background:#fef3c7;border-radius:8px;padding:10px;margin-top:8px;font-size:12px;color:#92400e;">
                                    ⚠️ No usage recorded yet for this request
                                </div>
                                @endif
                            </div>
                            
                            <div style="text-align:right;flex-shrink:0;">
                                @php
                                    $statusBadges = [
                                        'loaded' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'Loaded'],
                                        'flight_received' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'Received'],
                                        'delivered' => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Delivered'],
                                        'served' => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Served']
                                    ];
                                    $badge = $statusBadges[$request->status] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => ucfirst($request->status)];
                                @endphp
                                <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:6px 16px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:8px;">
                                    {{ $badge['label'] }}
                                </span>
                                <div style="font-size:18px;font-weight:700;color:#2563eb;margin-bottom:8px;">{{ $request->items->count() }} items</div>
                                <a href="{{ route('cabin-crew.products.view', $request) }}" style="background:#4299e1;color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;display:inline-block;">
                                    👁️ View Details
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding:20px 28px;border-top:1px solid #f3f4f6;">
        {{ $requests->links() }}
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">📊</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Usage Records</div>
        <div style="font-size:14px;color:#a0aec0;">No requests available for usage tracking</div>
    </div>
    @endif
</div>
@endsection
