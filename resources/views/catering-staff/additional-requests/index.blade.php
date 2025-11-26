@extends('layouts.app')

@section('title', 'Additional Product Requests from Cabin Crew')

@section('content')
<div class="content-header">
    <h1>📦 Additional Product Requests</h1>
    <p>Manage product requests from Cabin Crew during ongoing flights</p>
</div>

@if(session('success'))
<div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:16px;border-radius:8px;margin-bottom:24px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:16px;border-radius:8px;margin-bottom:24px;">
    {{ session('error') }}
</div>
@endif

<!-- Pending Requests -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">⏳ Pending Approval</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Requests awaiting your response</p>
        </div>
        @if($pendingRequests->count() > 0)
        <span style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;padding:6px 16px;border-radius:20px;font-weight:600;font-size:14px;">
            {{ $pendingRequests->count() }}
        </span>
        @endif
    </div>

    @if($pendingRequests->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Original Request</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Qty Requested</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Reason</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Requested By</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRequests as $addRequest)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:16px;font-weight:700;color:#667eea;">#{{ $addRequest->id }}</td>
                    <td style="padding:16px;">
                        <a href="{{ route('catering-staff.requests.show', $addRequest->originalRequest) }}" style="color:#667eea;text-decoration:none;font-weight:600;">
                            #{{ $addRequest->original_request_id }}
                        </a>
                    </td>
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $addRequest->originalRequest->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $addRequest->originalRequest->flight->origin }} → {{ $addRequest->originalRequest->flight->destination }}</div>
                    </td>
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $addRequest->product->name }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $addRequest->product->category->name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding:16px;text-align:center;font-weight:600;color:#2d3748;">{{ $addRequest->quantity_requested }}</td>
                    <td style="padding:16px;font-size:13px;color:#4a5568;">
                        {{ Str::limit($addRequest->reason, 50) }}
                    </td>
                    <td style="padding:16px;font-size:13px;color:#4a5568;">
                        <div style="font-weight:600;color:#2d3748;">{{ $addRequest->requester->name }}</div>
                        <div style="font-size:11px;color:#a0aec0;">{{ $addRequest->created_at->diffForHumans() }}</div>
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <!-- Approve Button -->
                            <button onclick="openApproveModal({{ $addRequest->id }}, '{{ $addRequest->product->name }}', {{ $addRequest->quantity_requested }})" 
                                    style="background:#38a169;color:white;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                ✓ Approve
                            </button>
                            
                            <!-- Reject Button -->
                            <form action="{{ route('catering-staff.additional-requests.reject', $addRequest) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure you want to reject this request?')" 
                                        style="background:#e53e3e;color:white;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                    ✗ Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">✅</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Pending Requests</div>
        <div style="font-size:14px;color:#a0aec0;">All additional requests have been processed</div>
    </div>
    @endif
</div>

<!-- Approved/Delivered Requests -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 24px 0;">✅ Approved & Delivered Requests</h2>
    
    @if($approvedRequests->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Requested</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Status</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved By</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvedRequests as $addRequest)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:16px;font-weight:700;color:#667eea;">#{{ $addRequest->id }}</td>
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $addRequest->originalRequest->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $addRequest->originalRequest->flight->origin }} → {{ $addRequest->originalRequest->flight->destination }}</div>
                    </td>
                    <td style="padding:16px;font-weight:600;color:#2d3748;">{{ $addRequest->product->name }}</td>
                    <td style="padding:16px;text-align:center;color:#718096;">{{ $addRequest->quantity_requested }}</td>
                    <td style="padding:16px;text-align:center;font-weight:600;color:#38a169;">{{ $addRequest->quantity_approved }}</td>
                    <td style="padding:16px;text-align:center;">
                        @if($addRequest->status === 'approved')
                            <span style="background:#d4edda;color:#155724;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                                Approved
                            </span>
                        @else
                            <span style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                                Delivered
                            </span>
                        @endif
                    </td>
                    <td style="padding:16px;font-size:13px;color:#4a5568;">
                        <div style="font-weight:600;">{{ $addRequest->approver->name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:#a0aec0;">{{ $addRequest->approved_at ? $addRequest->approved_at->diffForHumans() : '' }}</div>
                    </td>
                    <td style="padding:16px;text-align:center;">
                        @if($addRequest->status === 'approved')
                            <form action="{{ route('catering-staff.additional-requests.delivered', $addRequest) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                    📦 Mark Delivered
                                </button>
                            </form>
                        @else
                            <span style="color:#a0aec0;font-size:12px;">Completed</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:40px 20px;color:#a0aec0;">
        <div style="font-size:14px;">No approved requests yet</div>
    </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:500px;width:90%;">
        <h3 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 24px 0;">Approve Additional Product Request</h3>
        
        <form id="approveForm" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Product</label>
                <div id="approveProductName" style="font-size:16px;font-weight:700;color:#2d3748;"></div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Requested Quantity</label>
                <div id="approveRequestedQty" style="font-size:16px;color:#718096;"></div>
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Quantity to Approve *</label>
                <input type="number" name="quantity_approved" min="1" required 
                       style="width:100%;padding:10px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;" 
                       placeholder="Enter approved quantity">
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="closeApproveModal()" 
                        style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background:#38a169;color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    ✓ Approve Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(requestId, productName, requestedQty) {
    document.getElementById('approveModal').style.display = 'flex';
    document.getElementById('approveProductName').textContent = productName;
    document.getElementById('approveRequestedQty').textContent = requestedQty;
    document.getElementById('approveForm').action = '{{ url("catering-staff/additional-requests") }}/' + requestId + '/approve';
    document.querySelector('#approveForm input[name="quantity_approved"]').value = requestedQty;
    document.querySelector('#approveForm input[name="quantity_approved"]').max = requestedQty * 2; // Allow up to double
}

function closeApproveModal() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('approveForm').reset();
}

document.getElementById('approveModal').addEventListener('click', function(e) {
    if (e.target === this) closeApproveModal();
});
</script>
@endsection
