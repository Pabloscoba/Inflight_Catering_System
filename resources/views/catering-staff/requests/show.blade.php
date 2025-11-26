@extends('layouts.app')

@section('title', 'Request #' . $requestModel->id . ' Details')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>📋 Request #{{ $requestModel->id }}</h1>
            <p>Complete request details and status information</p>
        </div>
        <a href="{{ route('catering-staff.requests.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Requests
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
            $status = $requestModel->status;
            $statusBg = '#f3f4f6';
            $statusColor = '#374151';
            if (in_array($status, ['pending_inventory', 'pending_supervisor'])) {
                $statusBg = '#fef3c7';
                $statusColor = '#92400e';
            }
            if (in_array($status, ['supervisor_approved', 'sent_to_security', 'security_approved'])) {
                $statusBg = '#dbeafe';
                $statusColor = '#1e40af';
            }
            if ($status == 'catering_approved') {
                $statusBg = '#d1fae5';
                $statusColor = '#065f46';
            }
            if ($status == 'rejected') {
                $statusBg = '#fee2e2';
                $statusColor = '#991b1b';
            }
        @endphp
        <span style="background:{{ $statusBg }};color:{{ $statusColor }};padding:10px 20px;border-radius:12px;font-size:14px;font-weight:700;">
            {{ strtoupper(str_replace('_', ' ', $status)) }}
        </span>
    </div>
    @if($requestModel->approved_by)
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#10b981 0%,#059669 100%);display:flex;align-items:center;justify-content:center;">
                <svg style="width:20px;height:20px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $requestModel->approver?->name ?? 'Catering Incharge' }}</div>
                <div style="font-size:12px;color:#6b7280;">Approved this request</div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Flight & Request Information -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:24px;">
    <!-- Flight Details -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;">✈️ Flight Information</h3>
        <div style="space-y:12px;">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Flight Number</label>
                <div style="font-size:20px;font-weight:700;color:#2563eb;">{{ $requestModel->flight->flight_number }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Route</label>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:14px;font-weight:600;color:#1f2937;">{{ $requestModel->flight->origin }}</span>
                    <svg style="width:16px;height:16px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <span style="font-size:14px;font-weight:600;color:#1f2937;">{{ $requestModel->flight->destination }}</span>
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Departure Time</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ \Carbon\Carbon::parse($requestModel->flight->departure_time)->format('M d, Y H:i A') }}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ \Carbon\Carbon::parse($requestModel->flight->departure_time)->diffForHumans() }}</div>
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;">📋 Request Details</h3>
        <div style="space-y:12px;">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Request ID</label>
                <div style="font-size:20px;font-weight:700;color:#2563eb;">#{{ $requestModel->id }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Requested Date</label>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ optional($requestModel->requested_date)->format('M d, Y H:i A') }}</div>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;">{{ optional($requestModel->requested_date)->diffForHumans() }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Total Items</label>
                <div style="font-size:18px;font-weight:700;color:#1f2937;">{{ $requestModel->items->count() }} Products</div>
            </div>
        </div>
    </div>
</div>

<!-- Requested Items -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0;">📦 Requested Items</h3>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">Products requested for this flight</p>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Product</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Requested</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Approved</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Available Stock</th>
                    @if($requestModel->status == 'catering_approved')
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($requestModel->items as $item)
                @php
                    $approvedQty = $item->quantity_approved ?? $item->quantity_requested;
                    $cateringAvailable = \App\Models\CateringStock::where('product_id', $item->product_id)->where('status','approved')->sum('quantity_available');
                @endphp
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $item->product->name }}</div>
                        <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:11px;color:#4b5563;margin-top:2px;display:inline-block;">{{ $item->product->sku ?? 'N/A' }}</code>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;color:#2563eb;">{{ $item->quantity_requested }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">units</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;color:#10b981;">{{ $approvedQty }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">units</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <div style="font-size:18px;font-weight:700;color:{{ $cateringAvailable > 0 ? '#059669' : '#dc2626' }};">{{ $cateringAvailable }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">in catering</div>
                    </td>
                    @if($requestModel->status == 'catering_approved')
                    <td style="padding:16px 20px;text-align:center;">
                        <span style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;">
                            ✓ Ready
                        </span>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Notes & Audit History -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:24px;">
    <!-- Notes -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">📝 Notes</h3>
        <div style="font-size:14px;color:#4b5563;line-height:1.6;">
            {{ $requestModel->notes ?? 'No additional notes provided for this request.' }}
        </div>
    </div>

    <!-- Audit History -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">📊 Audit History</h3>
        <div style="space-y:12px;">
            <div style="margin-bottom:12px;">
                <div style="font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Created</div>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $requestModel->created_at->format('M d, Y') }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $requestModel->created_at->format('h:i A') }} • {{ $requestModel->created_at->diffForHumans() }}</div>
            </div>
            @if($requestModel->approved_date)
            <div style="margin-bottom:12px;padding-top:12px;border-top:1px solid #f3f4f6;">
                <div style="font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Approved</div>
                <div style="font-size:14px;font-weight:600;color:#1f2937;">{{ $requestModel->approved_date->format('M d, Y') }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $requestModel->approved_date->format('h:i A') }} • {{ $requestModel->approved_date->diffForHumans() }}</div>
            </div>
            @endif
            @if($requestModel->rejection_reason)
            <div style="margin-bottom:12px;padding-top:12px;border-top:1px solid #f3f4f6;">
                <div style="font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:4px;">Rejection Reason</div>
                <div style="font-size:13px;color:#dc2626;">{{ $requestModel->rejection_reason }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection