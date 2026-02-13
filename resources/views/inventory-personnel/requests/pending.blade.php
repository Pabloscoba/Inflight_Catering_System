@extends('layouts.app')

@section('content')
<div style="padding:24px;">
    <h2 style="font-size:24px;font-weight:600;margin-bottom:16px;">Pending Requests from Catering Staff</h2>

    @if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    @if($requests->count() > 0)
    <div style="background:white;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Request ID</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Flight</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Requester</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Date</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Items</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:12px;">#{{ $req->id }}</td>
                    <td style="padding:12px;">{{ $req->flight->flight_number }}<br><small style="color:#6b7280;">{{ $req->flight->origin }} → {{ $req->flight->destination }}</small></td>
                    <td style="padding:12px;">{{ $req->requester->name }}</td>
                    <td style="padding:12px;">{{ $req->requested_date }}</td>
                    <td style="padding:12px;">
                        <details>
                            <summary style="cursor:pointer;color:#2563eb;">{{ $req->items->count() }} items</summary>
                            <ul style="margin-top:8px;margin-left:16px;">
                                @foreach($req->items as $it)
                                    <li>{{ $it->product->name }} ({{ $it->quantity_requested }})</li>
                                @endforeach
                            </ul>
                        </details>
                    </td>
                    <td style="padding:12px;">
                        <button type="button" onclick="showForwardConfirmation({{ $req->id }})" class="btn btn-sm" style="background:#2563eb;color:white;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;">Forward to Supervisor</button>
                        <form id="forward-form-{{ $req->id }}" method="POST" action="{{ route('inventory-personnel.requests.forward-to-supervisor', $req) }}" style="display:none;">
                            @csrf
                        </form>
                        <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm" style="margin-left:8px;background:#f3f4f6;color:#374151;padding:8px 12px;border-radius:6px;text-decoration:none;">View</a>
                        <a href="{{ route('inventory-personnel.requests.edit', $req) }}" class="btn btn-sm" style="margin-right:8px;background:#2563eb;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;">✎ Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:12px;">{{ $requests->links() }}</div>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#6b7280;background:white;border-radius:8px;">
        No pending requests from Catering Staff at the moment.
    </div>
    @endif
</div>

<script>
function showForwardConfirmation(requestId) {
    // Show custom confirmation modal
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Forward to Supervisor?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Request #${requestId}</strong></p>
            <p style="margin:0;">Una uhakika unataka kupeleka ombi hili kwa Supervisor kwa ajili ya kibali?</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeForwardModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitForwardForm(${requestId})" style="background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Forward</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'forward-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeForwardModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentForwardConfirmDiv = confirmDiv;
}

function closeForwardModal() {
    const overlay = document.getElementById('forward-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentForwardConfirmDiv) window.currentForwardConfirmDiv.remove();
}

function submitForwardForm(requestId) {
    closeForwardModal();
    document.getElementById('forward-form-' + requestId).submit();
}
</script>

@endsection
