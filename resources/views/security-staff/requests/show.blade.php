@extends('layouts.app')

@section('title', 'Request #' . $request->id . ' - Security Review')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>🔐 Request #{{ $request->id }} - Security Review</h1>
            <p>Review request details before authentication</p>
        </div>
        <a href="{{ route('security-staff.requests.awaiting-authentication') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>
</div>

<!-- Request Status -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 8px 0;">Request Status</h3>
            <p style="font-size:13px;color:#6b7280;margin:0;">Current workflow stage</p>
        </div>
        <span style="background:#fef3c7;color:#92400e;padding:10px 20px;border-radius:12px;font-size:14px;font-weight:700;">
            ⏳ {{ strtoupper(str_replace('_', ' ', $request->status)) }}
        </span>
    </div>
</div>

<!-- Request & Flight Information -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:24px;">
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
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->requester->name }}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $request->requester->email }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Requested Date</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $request->requested_date->format('M d, Y H:i A') }}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $request->requested_date->diffForHumans() }}</div>
            </div>
        </div>
    </div>

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
        </div>
    </div>
</div>

<!-- Requested Items -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📦 Requested Items</h3>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Products to be issued from inventory</p>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">#</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Product</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Category</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Meal Type</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Quantity</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">SKU</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items as $index => $item)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:16px 20px;font-weight:600;color:#6b7280;">{{ $index + 1 }}</td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $item->product->name }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <span style="background:#eff6ff;color:#1e40af;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:600;">
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
                            <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;white-space:nowrap;">
                                {{ $badge['icon'] }} {{ $badge['label'] }}
                            </span>
                        @else
                            <span style="color:#9ca3af;font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;color:#2563eb;">{{ $item->quantity_requested }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">units</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <code style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-size:12px;color:#4b5563;">{{ $item->product->sku }}</code>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Authentication Action -->
@if($request->status === 'sent_to_security')
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">🔐 Security Authentication</h3>
    <p style="font-size:14px;color:#6b7280;margin-bottom:24px;">Authenticate this request to issue stock from main inventory and forward to Catering Incharge.</p>
    
    <button type="button" onclick="showAuthenticateConfirmation({{ $request->id }}, {{ $request->items->sum('quantity_requested') }})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:14px 32px;border-radius:8px;border:none;font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:10px;cursor:pointer;box-shadow:0 4px 12px rgba(16,185,129,0.25);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(16,185,129,0.35)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(16,185,129,0.25)'">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        Authenticate Request
    </button>
    <form id="auth-form-{{ $request->id }}" method="POST" action="{{ route('security-staff.requests.authenticate', $request) }}" style="display:none;">
        @csrf
    </form>
</div>
@endif

<script>
function showAuthenticateConfirmation(requestId, totalUnits) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">✓ Authenticate Request?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Request #${requestId}</strong></p>
            <p style="margin:0 0 8px 0;">• Issue ${totalUnits} units from main inventory</p>
            <p style="margin:0 0 8px 0;">• Forward to Catering Incharge for approval</p>
            <p style="margin:0;">• Update request status to security_approved</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeAuthModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitAuthForm(${requestId})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✓ Authenticate</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'auth-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeAuthModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentAuthConfirmDiv = confirmDiv;
}

function closeAuthModal() {
    const overlay = document.getElementById('auth-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentAuthConfirmDiv) window.currentAuthConfirmDiv.remove();
}

function submitAuthForm(requestId) {
    closeAuthModal();
    document.getElementById('auth-form-' + requestId).submit();
}
</script>

@endsection
