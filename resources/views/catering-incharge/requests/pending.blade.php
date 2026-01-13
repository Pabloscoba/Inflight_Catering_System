@extends('layouts.app')

@section('title', 'Pending Staff Requests')

@section('content')
<div style="padding: 32px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Pending Staff Requests</h1>
                <p style="color: #718096; font-size: 16px; margin: 0;">Approve or reject requests from Catering Staff</p>
            </div>
            <a href="{{ route('catering-incharge.dashboard') }}" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
    @endif

    <!-- Requests Table -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        @if($requests->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Request ID</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Flight</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Requested By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Items</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Date</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Notes</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 700;">#{{ $request->id }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #212529;">{{ $request->flight->flight_number }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requester->name }}</td>
                        <td style="padding: 14px;">
                            <details style="cursor: pointer;">
                                <summary style="font-size: 14px; color: #667eea; font-weight: 600;">{{ $request->items->count() }} items</summary>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px; font-size: 13px; color: #6c757d;">
                                    @foreach($request->items as $item)
                                    <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
                                    @endforeach
                                </ul>
                            </details>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->requested_date->format('M d, Y') }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $request->notes ?? '-' }}</td>
                        <td style="padding: 14px;">
                            <div style="display: flex; gap: 8px;">
                                <button onclick="showApproveConfirmation({{ $request->id }}, '{{ $request->flight->flight_number }}')" style="background: #28a745; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                                    ✓ Approve
                                </button>
                                <button onclick="document.getElementById('reject-{{ $request->id }}').style.display='flex'" style="background: #dc3545; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                    ✗ Reject
                                </button>
                            </div>
                            <!-- Hidden Approve Form -->
                            <form id="approve-form-{{ $request->id }}" action="{{ route('catering-incharge.requests.approve', $request) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <!-- Reject Modal -->
                            <div id="reject-{{ $request->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
                                    <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Reject Request #{{ $request->id }}</h4>
                                    <p style="color: #6c757d; margin-bottom: 16px;">Flight: <strong>{{ $request->flight->flight_number }}</strong></p>
                                    <form action="{{ route('catering-incharge.requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" required placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; min-height: 100px; margin-bottom: 16px; font-family: inherit;"></textarea>
                                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('reject-{{ $request->id }}').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Cancel</button>
                                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Reject Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 24px;">
            {{ $requests->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <svg style="width: 64px; height: 64px; color: #cbd5e0; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 style="font-size: 20px; font-weight: 600; color: #4a5568; margin: 0 0 8px 0;">No Pending Requests</h3>
            <p style="color: #718096; margin: 0;">All staff requests have been processed.</p>
        </div>
        @endif
    </div>
</div>

<script>
function showApproveConfirmation(requestId, flightNumber) {
    // Show custom confirmation modal
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Approve Request?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 12px 0;"><strong>Flight:</strong> ${flightNumber}</p>
            <p style="margin:0 0 8px 0;">✓ Stock itatolewa kutoka catering inventory</p>
            <p style="margin:0 0 8px 0;">✓ Stock itagawiwa kwa Catering Staff</p>
            <p style="margin:0;color:#dc3545;"><strong>⚠️ Haiwezi kurudishwa</strong></p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeApproveModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitApproveForm(${requestId})" style="background:#28a745;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✓ Approve</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeApproveModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentConfirmDiv = confirmDiv;
}

function closeApproveModal() {
    const overlay = document.getElementById('modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentConfirmDiv) window.currentConfirmDiv.remove();
}

function submitApproveForm(requestId) {
    closeApproveModal();
    document.getElementById('approve-form-' + requestId).submit();
}

document.addEventListener('click', function(event) {
    if (event.target.style.background === 'rgba(0, 0, 0, 0.5)') {
        event.target.style.display = 'none';
    }
});
</script>

@endsection
