@extends('layouts.app')

@section('title', 'Authenticate Returns')

@section('content')
<div style="max-width:1200px;margin:0 auto;">
    <h1 style="font-size:32px;font-weight:700;color:#1a202c;margin-bottom:8px;">🔒 Authenticate Product Returns</h1>
    <p style="color:#718096;margin-bottom:32px;">Verify returned products and adjust inventory stock levels</p>

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
        <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:28px;">🔒</span>
            </div>
            <div style="flex:1;">
                <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $pendingReturns->count() }}</div>
                <div style="font-size:13px;color:#718096;margin-top:4px;">Pending Authentication</div>
            </div>
        </div>

        <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:28px;">✅</span>
            </div>
            <div style="flex:1;">
                <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $authenticatedReturns->count() }}</div>
                <div style="font-size:13px;color:#718096;margin-top:4px;">Recently Authenticated</div>
            </div>
        </div>
    </div>

    <!-- Pending Authentication -->
    @if($pendingReturns->count() > 0)
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <div>
                <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">🔒 Returns Pending Authentication</h2>
                <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Verify returned products and update stock levels</p>
            </div>
        </div>

        <div style="display:grid;gap:16px;">
            @foreach($pendingReturns as $return)
            <div style="background:#f7fafc;border-radius:12px;padding:20px;border:2px solid #e2e8f0;">
                <div style="display:grid;grid-template-columns:1fr auto;gap:20px;margin-bottom:16px;">
                    <div>
                        <div style="font-weight:700;color:#1f2937;font-size:18px;margin-bottom:8px;">{{ $return->product->name }}</div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:8px;">
                            <div>
                                <div style="font-size:12px;color:#6b7280;">Flight Number</div>
                                <div style="font-weight:600;color:#374151;">{{ $return->request->flight->flight_number }}</div>
                            </div>
                            <div>
                                <div style="font-size:12px;color:#6b7280;">Quantity Returned</div>
                                <div style="font-weight:700;color:#1e40af;font-size:16px;">{{ $return->quantity_returned }}</div>
                            </div>
                            <div>
                                <div style="font-size:12px;color:#6b7280;">Condition</div>
                                @if($return->condition === 'good')
                                    <span style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:6px;font-weight:600;font-size:12px;">✅ Good</span>
                                @elseif($return->condition === 'damaged')
                                    <span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:6px;font-weight:600;font-size:12px;">⚠️ Damaged</span>
                                @else
                                    <span style="background:#fed7aa;color:#92400e;padding:4px 8px;border-radius:6px;font-weight:600;font-size:12px;">⏰ Expired</span>
                                @endif
                            </div>
                            <div>
                                <div style="font-size:12px;color:#6b7280;">Returned By</div>
                                <div style="font-weight:600;color:#374151;">{{ $return->returnedBy->name }}</div>
                            </div>
                        </div>
                        @if($return->reason)
                        <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:12px;border-radius:6px;margin-top:8px;">
                            <div style="font-size:12px;color:#92400e;font-weight:600;margin-bottom:4px;">Return Reason:</div>
                            <div style="font-size:13px;color:#78350f;">{{ $return->reason }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <form action="{{ route('security-staff.returns.authenticate', $return) }}" method="POST" style="background:white;border:2px solid #e2e8f0;border-radius:8px;padding:16px;">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 2fr auto;gap:16px;align-items:end;">
                        <div>
                            <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">Verified Quantity *</label>
                            <input type="number" name="verified_quantity" required min="0" max="{{ $return->quantity_returned }}" value="{{ $return->quantity_returned }}" style="width:100%;padding:10px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;">
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">Verification Notes (Optional)</label>
                            <input type="text" name="verification_notes" placeholder="Additional notes about this return" style="width:100%;padding:10px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;">
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button type="button" onclick="showAuthReturnConfirmation({{ $return->id }})" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:10px 20px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Authenticate
                            </button>
                            <button type="button" onclick="showRejectForm({{ $return->id }})" style="display:inline-flex;align-items:center;gap:6px;background:#ef4444;color:white;padding:10px 16px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Reject Form (Hidden by default) -->
                <form id="rejectForm{{ $return->id }}" action="{{ route('security-staff.returns.reject', $return) }}" method="POST" style="display:none;background:#fef2f2;border:2px solid #fca5a5;border-radius:8px;padding:16px;margin-top:12px;">
                    @csrf
                    <label style="display:block;font-weight:600;color:#991b1b;margin-bottom:8px;font-size:14px;">Rejection Reason *</label>
                    <textarea name="rejection_reason" required rows="2" placeholder="Explain why this return is being rejected" style="width:100%;padding:10px;border:2px solid #fca5a5;border-radius:8px;font-size:14px;resize:vertical;"></textarea>
                    <div style="display:flex;gap:8px;margin-top:12px;">
                        <button type="button" onclick="showRejectReturnConfirmation({{ $return->id }})" style="background:#dc2626;color:white;padding:8px 16px;border:none;border-radius:6px;font-weight:600;font-size:13px;cursor:pointer;">
                            Confirm Rejection
                        </button>
                        <button type="button" onclick="hideRejectForm({{ $return->id }})" style="background:#e5e7eb;color:#374151;padding:8px 16px;border:none;border-radius:6px;font-weight:600;font-size:13px;cursor:pointer;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div style="background:white;border-radius:16px;padding:48px 28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;text-align:center;color:#9ca3af;">
        <div style="font-size:48px;margin-bottom:12px;">🔒</div>
        <div style="font-size:16px;">No returns pending authentication</div>
    </div>
    @endif

    <!-- Authenticated Returns History -->
    @if($authenticatedReturns->count() > 0)
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">✅ Recently Authenticated Returns</h2>
        <p style="color:#718096;margin:0 0 24px 0;font-size:14px;">Last 20 authenticated returns with stock adjustments</p>

        <div style="display:grid;gap:12px;">
            @foreach($authenticatedReturns as $return)
            <div style="background:#f0fdf4;border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;gap:16px;border:2px solid #86efac;">
                <div style="flex:1;">
                    <div style="font-weight:700;color:#166534;margin-bottom:4px;">{{ $return->product->name }}</div>
                    <div style="font-size:13px;color:#15803d;">
                        Flight {{ $return->request->flight->flight_number }} | Qty: {{ $return->quantity_returned }} | 
                        Returned by {{ $return->returnedBy->name }}
                    </div>
                    @if($return->notes)
                    <div style="font-size:12px;color:#4d7c0f;margin-top:4px;">Note: {{ $return->notes }}</div>
                    @endif
                </div>
                <div style="text-align:right;">
                    <div style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;margin-bottom:4px;">
                        ✅ Authenticated
                    </div>
                    <div style="font-size:11px;color:#4d7c0f;">{{ $return->verified_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function showRejectForm(id) {
    document.getElementById('rejectForm' + id).style.display = 'block';
}

function hideRejectForm(id) {
    document.getElementById('rejectForm' + id).style.display = 'none';
}

function showAuthReturnConfirmation(returnId) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Authenticate Return?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 8px 0;">Una uhakika unataka kuthibitisha return hii?</p>
            <p style="margin:0;">Stock itaboreshwa automatically.</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeAuthReturnModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitAuthReturnForm(${returnId})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✓ Authenticate</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'auth-return-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeAuthReturnModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentAuthReturnConfirmDiv = confirmDiv;
}

function closeAuthReturnModal() {
    const overlay = document.getElementById('auth-return-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentAuthReturnConfirmDiv) window.currentAuthReturnConfirmDiv.remove();
}

function submitAuthReturnForm(returnId) {
    closeAuthReturnModal();
    document.getElementById('authForm' + returnId).submit();
}

function showRejectReturnConfirmation(returnId) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#dc2626;">Reject Return?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0;">Una uhakika unataka kukataa return hii?</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeRejectReturnModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitRejectReturnForm(${returnId})" style="background:#dc2626;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✗ Reject</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'reject-return-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeRejectReturnModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentRejectReturnConfirmDiv = confirmDiv;
}

function closeRejectReturnModal() {
    const overlay = document.getElementById('reject-return-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentRejectReturnConfirmDiv) window.currentRejectReturnConfirmDiv.remove();
}

function submitRejectReturnForm(returnId) {
    closeRejectReturnModal();
    document.getElementById('rejectForm' + returnId).submit();
}
</script>
@endsection
