@extends('layouts.app')

@section('title', 'Product Returns Management')

@section('content')
<div style="max-width:1200px;margin:0 auto;">
    <h1 style="font-size:32px;font-weight:700;color:#1a202c;margin-bottom:8px;">↩️ Product Returns Management</h1>
    <p style="color:#718096;margin-bottom:32px;">Receive returns from Cabin Crew and forward to Security for authentication</p>

    <!-- Stats Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
        <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:28px;">📦</span>
            </div>
            <div style="flex:1;">
                <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $pendingReturns->count() }}</div>
                <div style="font-size:13px;color:#718096;margin-top:4px;">Pending Returns</div>
            </div>
        </div>

        <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
            <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#6366f1 0%,#4f46e5 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:28px;">✅</span>
            </div>
            <div style="flex:1;">
                <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $forwardedReturns->count() }}</div>
                <div style="font-size:13px;color:#718096;margin-top:4px;">Forwarded to Security</div>
            </div>
        </div>
    </div>

    <!-- Pending Returns -->
    @if($pendingReturns->count() > 0)
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <div>
                <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📦 Pending Returns from Cabin Crew</h2>
                <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Receive and forward to Security for authentication</p>
            </div>
            <form action="{{ route('ramp-dispatcher.returns.bulk-receive') }}" method="POST" id="bulkForm">
                @csrf
                <input type="hidden" name="return_ids[]" id="bulkIds">
                <button type="button" onclick="bulkReceive()" style="display:inline-flex;align-items:center;gap:8px;background:#10b981;color:white;padding:10px 20px;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Receive All Selected
                </button>
            </form>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;">
                <thead>
                    <tr style="background:#f7fafc;border-bottom:2px solid #e2e8f0;">
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">
                            <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                        </th>
                        <th style="padding:14px 20px;text-align:left;font-size:13px;font-weight:700;color:#2d3748;">Product</th>
                        <th style="padding:14px 20px;text-align:left;font-size:13px;font-weight:700;color:#2d3748;">Flight</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Quantity</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Condition</th>
                        <th style="padding:14px 20px;text-align:left;font-size:13px;font-weight:700;color:#2d3748;">Returned By</th>
                        <th style="padding:14px 20px;text-align:center;font-size:13px;font-weight:700;color:#2d3748;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReturns as $return)
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:16px 20px;text-align:center;">
                            <input type="checkbox" class="return-checkbox" value="{{ $return->id }}">
                        </td>
                        <td style="padding:16px 20px;">
                            <div style="font-weight:700;color:#1f2937;margin-bottom:4px;">{{ $return->product->name }}</div>
                            @if($return->reason)
                            <div style="font-size:12px;color:#6b7280;">Reason: {{ $return->reason }}</div>
                            @endif
                        </td>
                        <td style="padding:16px 20px;">
                            <div style="font-weight:600;color:#374151;">{{ $return->request->flight->flight_number }}</div>
                            <div style="font-size:12px;color:#9ca3af;">{{ $return->request->flight->origin }} → {{ $return->request->flight->destination }}</div>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <span style="background:#dbeafe;color:#1e40af;padding:6px 12px;border-radius:12px;font-weight:600;font-size:14px;">
                                {{ $return->quantity_returned }}
                            </span>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            @if($return->condition === 'good')
                                <span style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:12px;font-weight:600;font-size:13px;">✅ Good</span>
                            @elseif($return->condition === 'damaged')
                                <span style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:12px;font-weight:600;font-size:13px;">⚠️ Damaged</span>
                            @else
                                <span style="background:#fed7aa;color:#92400e;padding:6px 12px;border-radius:12px;font-weight:600;font-size:13px;">⏰ Expired</span>
                            @endif
                        </td>
                        <td style="padding:16px 20px;">
                            <div style="font-weight:600;color:#374151;">{{ $return->returnedBy->name }}</div>
                            <div style="font-size:12px;color:#9ca3af;">{{ $return->returned_at->diffForHumans() }}</div>
                        </td>
                        <td style="padding:16px 20px;text-align:center;">
                            <button type="button" onclick="showReceiveReturnConfirmation({{ $return->id }})" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:8px 16px;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                Receive & Forward
                            </button>
                            <form id="receive-return-form-{{ $return->id }}" action="{{ route('ramp-dispatcher.returns.receive', $return) }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div style="background:white;border-radius:16px;padding:48px 28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;text-align:center;color:#9ca3af;">
        <div style="font-size:48px;margin-bottom:12px;">📭</div>
        <div style="font-size:16px;">No pending returns from Cabin Crew</div>
    </div>
    @endif

    <!-- Forwarded Returns -->
    @if($forwardedReturns->count() > 0)
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">✅ Forwarded to Security</h2>
        <p style="color:#718096;margin:0 0 24px 0;font-size:14px;">Recently processed returns awaiting Security authentication</p>

        <div style="display:grid;gap:12px;">
            @foreach($forwardedReturns as $return)
            <div style="background:#f7fafc;border-radius:12px;padding:16px;display:flex;justify-content:space-between;align-items:center;gap:16px;">
                <div style="flex:1;">
                    <div style="font-weight:700;color:#1f2937;margin-bottom:4px;">{{ $return->product->name }}</div>
                    <div style="font-size:13px;color:#6b7280;">
                        Flight {{ $return->request->flight->flight_number }} | Qty: {{ $return->quantity_returned }} | 
                        Returned by {{ $return->returnedBy->name }}
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="background:{{ $return->status === 'authenticated' ? '#d1fae5' : '#dbeafe' }};color:{{ $return->status === 'authenticated' ? '#065f46' : '#1e40af' }};padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;margin-bottom:4px;">
                        @if($return->status === 'authenticated')
                            ✅ Authenticated
                        @else
                            🔒 Pending Security
                        @endif
                    </div>
                    <div style="font-size:11px;color:#9ca3af;">{{ $return->received_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.return-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

function bulkReceive() {
    const checkboxes = document.querySelectorAll('.return-checkbox:checked');
    if (checkboxes.length === 0) {
        if (typeof Toast !== 'undefined') {
            Toast.warning('Please select at least one return to receive');
        }
        return;
    }
    
    showBulkReceiveConfirmation(checkboxes.length);
}

function submitBulkReceive() {
    const checkboxes = document.querySelectorAll('.return-checkbox:checked');
    const form = document.getElementById('bulkForm');
    
    // Clear existing values
    form.querySelectorAll('input[name="return_ids[]"]').forEach(input => input.remove());
    
    // Add selected IDs
    checkboxes.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'return_ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });
    
    form.submit();
}

function showReceiveReturnConfirmation(returnId) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Receive & Forward Return?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 8px 0;">Una uhakika unataka kupokea return hii?</p>
            <p style="margin:0;">Itapelekwa kwa Security kwa ajili ya authentication.</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeReceiveReturnModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitReceiveReturnForm(${returnId})" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Receive & Forward</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'receive-return-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeReceiveReturnModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentReceiveReturnConfirmDiv = confirmDiv;
}

function closeReceiveReturnModal() {
    const overlay = document.getElementById('receive-return-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentReceiveReturnConfirmDiv) window.currentReceiveReturnConfirmDiv.remove();
}

function submitReceiveReturnForm(returnId) {
    closeReceiveReturnModal();
    document.getElementById('receive-return-form-' + returnId).submit();
}

function showBulkReceiveConfirmation(count) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Bulk Receive Returns?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 8px 0;">Una uhakika unataka kupokea returns <strong>${count}</strong>?</p>
            <p style="margin:0;">Zitapelekwa kwa Security kwa ajili ya authentication.</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeBulkReceiveModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitBulkReceiveConfirm()" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Receive All</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'bulk-receive-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeBulkReceiveModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentBulkReceiveConfirmDiv = confirmDiv;
}

function closeBulkReceiveModal() {
    const overlay = document.getElementById('bulk-receive-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentBulkReceiveConfirmDiv) window.currentBulkReceiveConfirmDiv.remove();
}

function submitBulkReceiveConfirm() {
    closeBulkReceiveModal();
    submitBulkReceive();
}
</script>
@endsection
