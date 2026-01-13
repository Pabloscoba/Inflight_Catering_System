@extends('layouts.app')

@section('title', 'Requests Awaiting Security')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>🔐 Security Authentication</h1>
            <p>Review and authenticate requests awaiting security clearance</p>
        </div>
        <a href="{{ route('security-staff.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:#d1fae5;border-left:4px solid #059669;padding:16px 20px;border-radius:8px;margin-bottom:24px;color:#065f46;font-weight:600;">
    <div style="display:flex;align-items:center;gap:12px;">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
</div>
@endif

<!-- Requests Awaiting Authentication -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📋 Pending Authentication</h3>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Requests forwarded by Inventory Personnel awaiting security clearance</p>
        </div>
        <div style="background:#fef3c7;color:#92400e;padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;">
            {{ $requests->count() }} requests
        </div>
    </div>

    @if($requests->count())
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Request ID</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Flight Details</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Route</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Departure</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requested By</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Items</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:700;color:#2563eb;font-size:16px;">#{{ $req->id }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            {{ optional($req->requested_date)->format('M d, Y') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $req->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $req->flight->airline }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="font-weight:600;color:#1f2937;font-size:13px;">{{ $req->flight->origin }}</span>
                            <svg style="width:14px;height:14px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            <span style="font-weight:600;color:#1f2937;font-size:13px;">{{ $req->flight->destination }}</span>
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:13px;">
                            {{ \Carbon\Carbon::parse($req->flight->departure_time)->format('M d, Y') }}
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            {{ \Carbon\Carbon::parse($req->flight->departure_time)->format('H:i A') }}
                        </div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:13px;">{{ $req->requester->name }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $req->requester->email }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <details style="cursor:pointer;">
                            <summary style="font-weight:600;color:#2563eb;font-size:13px;list-style:none;display:flex;align-items:center;gap:6px;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                {{ $req->items->count() }} items
                            </summary>
                            <div style="margin-top:8px;padding:12px;background:#f9fafb;border-radius:8px;">
                                @foreach($req->items as $it)
                                    <div style="padding:6px 0;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
                                        <span style="font-size:12px;color:#1f2937;font-weight:500;">{{ $it->product->name }}</span>
                                        <span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700;">{{ $it->quantity_requested }} units</span>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                            <button type="button" onclick="showAuthenticateConfirmation({{ $req->id }})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:10px 18px;border-radius:8px;border:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;cursor:pointer;box-shadow:0 2px 8px rgba(16,185,129,0.25);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(16,185,129,0.35)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(16,185,129,0.25)'">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Authenticate
                            </button>
                            <form id="auth-form-{{ $req->id }}" method="POST" action="{{ route('security-staff.requests.authenticate', $req) }}" style="display:none;">
                                @csrf
                            </form>
                            <a href="{{ route('security-staff.requests.show', $req) }}" style="background:#f3f4f6;color:#374151;padding:10px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
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

    <div style="padding:16px 24px;border-top:1px solid #f3f4f6;background:#f9fafb;">
        {{ $requests->links() }}
    </div>
    @else
    <div style="padding:60px 28px;text-align:center;">
        <div style="font-size:48px;margin-bottom:16px;">✅</div>
        <h4 style="font-size:18px;font-weight:600;color:#374151;margin-bottom:8px;">All Clear!</h4>
        <p style="color:#6b7280;font-size:14px;">No requests awaiting security authentication at the moment.</p>
        <a href="{{ route('security-staff.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:white;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;margin-top:16px;">
            Back to Dashboard
        </a>
    </div>
    @endif
</div>

<script>
function showAuthenticateConfirmation(requestId) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">✓ Authenticate Request?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Request #${requestId}</strong></p>
            <p style="margin:0 0 8px 0;">• Issue stock from main inventory</p>
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
