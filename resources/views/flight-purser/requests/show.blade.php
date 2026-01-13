@extends('layouts.app')

@section('title', 'Request #' . $request->id . ' Details')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>📋 Request #{{ $request->id }}</h1>
            <p>Request details for loading onto aircraft</p>
        </div>
        <a href="{{ route('flight-purser.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Request Status Card -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 8px 0;">Request Status</h3>
            <p style="font-size:13px;color:#6b7280;margin:0;">Current workflow stage</p>
        </div>
        @php
            $status = $request->status;
            $statusBg = '#f3f4f6';
            $statusColor = '#374151';
            
            if (in_array($status, ['dispatched', 'handed_to_flight'])) {
                $statusBg = '#dbeafe';
                $statusColor = '#1e40af';
            }
            if (in_array($status, ['loaded', 'flight_received'])) {
                $statusBg = '#d1fae5';
                $statusColor = '#065f46';
            }
            
            // Request type badge
            $typeBadges = [
                'meal' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '🍽️', 'label' => 'Meal Request'],
                'product' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '📦', 'label' => 'Product Request'],
                'mixed' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'icon' => '🔄', 'label' => 'Mixed Request']
            ];
            $typeBadge = $typeBadges[$request->request_type ?? 'product'] ?? $typeBadges['product'];
        @endphp
        <div style="display:flex;gap:12px;align-items:center;">
            <span style="background:{{ $typeBadge['bg'] }};color:{{ $typeBadge['color'] }};padding:10px 20px;border-radius:12px;font-size:13px;font-weight:700;">
                {{ $typeBadge['icon'] }} {{ $typeBadge['label'] }}
            </span>
            <span style="background:{{ $statusBg }};color:{{ $statusColor }};padding:10px 20px;border-radius:12px;font-size:14px;font-weight:700;">
                {{ strtoupper(str_replace('_', ' ', $status)) }}
            </span>
        </div>
    </div>
</div>

<!-- Flight & Request Information -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:24px;">
    <!-- Flight Details -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;">✈️ Flight Information</h3>
        <div style="space-y:12px;">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Flight Number</label>
                <div style="font-size:20px;font-weight:700;color:#2563eb;">{{ $request->flight->flight_number }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Route</label>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->flight->origin }}</span>
                    <svg style="width:16px;height:16px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <span style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->flight->destination }}</span>
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Departure Time</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y H:i A') }}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->diffForHumans() }}</div>
            </div>
            @if($request->flight->capacity)
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Aircraft Capacity</label>
                <div style="font-size:16px;font-weight:700;color:#1f2937;">{{ $request->flight->capacity }} passengers</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Request Details -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;">📋 Request Details</h3>
        <div style="space-y:12px;">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Request ID</label>
                <div style="font-size:20px;font-weight:700;color:#2563eb;">#{{ $request->id }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Requested By</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->requester->name ?? 'N/A' }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $request->requester->role->name ?? 'Catering Staff' }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Total Items</label>
                <div style="font-size:18px;font-weight:700;color:#1f2937;">{{ $request->items->count() }} Products</div>
            </div>
            @if($request->handed_to_flight_at)
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Handed Over</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->handed_to_flight_at->format('M d, Y h:i A') }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $request->handed_to_flight_at->diffForHumans() }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Requested Items -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📦 Request Items</h3>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Products to be loaded onto the aircraft</p>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Product</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Category</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Meal Type</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items as $item)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $item->product->name }}</div>
                        @if($item->product->sku)
                        <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:11px;color:#4b5563;margin-top:2px;display:inline-block;">{{ $item->product->sku }}</code>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <span style="background:#e0e7ff;color:#3730a3;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;">
                            {{ $item->product->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($item->meal_type)
                            @php
                                $mealBadges = [
                                    'breakfast' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '🍳', 'label' => 'Breakfast'],
                                    'lunch' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '🍽️', 'label' => 'Lunch'],
                                    'dinner' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '🌙', 'label' => 'Dinner'],
                                    'snack' => ['bg' => '#fce7f3', 'color' => '#9f1239', 'icon' => '🍪', 'label' => 'Snack'],
                                    'VIP_meal' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'icon' => '👑', 'label' => 'VIP'],
                                    'special_meal' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '⭐', 'label' => 'Special'],
                                    'non_meal' => ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'Non-Meal']
                                ];
                                $badge = $mealBadges[$item->meal_type] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'N/A'];
                            @endphp
                            <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;">
                                {{ $badge['icon'] }} {{ $badge['label'] }}
                            </span>
                        @else
                            <span style="color:#9ca3af;font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;color:#2563eb;">{{ $item->quantity_approved ?? $item->quantity_requested }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">units</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Notes -->
@if($request->notes)
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">📝 Notes</h3>
    <div style="font-size:14px;color:#4b5563;line-height:1.6;">
        {{ $request->notes }}
    </div>
</div>
@endif

<!-- Action Buttons -->
@if(in_array($request->status, ['dispatched', 'handed_to_flight']))
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 8px 0;">Ready to Load?</h3>
            <p style="font-size:13px;color:#6b7280;margin:0;">Confirm that all items have been received and loaded onto the aircraft.</p>
        </div>
        <form action="{{ route('flight-purser.requests.load', $request) }}" method="POST" id="load-request-form">
            @csrf
            <button type="button" onclick="showLoadRequestConfirmation({{ $request->id }})" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;border:none;padding:12px 32px;border-radius:12px;font-weight:700;font-size:14px;cursor:pointer;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(67,233,123,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                ✅ Confirm Receipt & Load onto Aircraft
            </button>
        </form>
    </div>
</div>
@endif

{{-- Load Request Confirmation Modal --}}
<div id="loadRequestModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2)">
        <h3 style="margin:0 0 12px;font-size:18px;font-weight:700">✅ Confirm Receipt & Load</h3>
        <p id="loadRequestMessage" style="color:#6b7280;margin:0 0 20px"></p>
        <div style="display:flex;gap:12px;justify-content:flex-end">
            <button onclick="closeLoadRequestModal()" style="padding:10px 20px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Cancel
            </button>
            <button onclick="submitLoadRequestForm()" style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    function showLoadRequestConfirmation(requestId) {
        document.getElementById('loadRequestMessage').textContent = 'Confirm receiving Request #' + requestId + ' and loading onto aircraft?';
        document.getElementById('loadRequestModal').style.display = 'flex';
    }

    function closeLoadRequestModal() {
        document.getElementById('loadRequestModal').style.display = 'none';
    }

    function submitLoadRequestForm() {
        document.getElementById('load-request-form').submit();
    }
</script>

@endsection
