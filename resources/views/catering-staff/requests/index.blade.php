@extends('layouts.app')

@section('title', 'My Requests')

@section('content')
<div style="padding:24px; max-width:1100px; margin:0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h1>
            @if(isset($filter) && $filter == 'pending')
                Pending Requests
            @elseif(isset($filter) && $filter == 'approved')
                Approved Requests
            @elseif(isset($filter) && $filter == 'rejected')
                Rejected Requests
            @else
                My Catering Requests
            @endif
        </h1>
        <div style="display:flex;gap:12px;align-items:center;">
            <a href="{{ route('catering-staff.requests.create') }}" 
               style="background:#2563eb;color:white;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;display:inline-flex;align-items:center;gap:6px;">
                <span style="font-size:18px;">+</span> Create New Request
            </a>
            <div style="display:flex;gap:8px;border-left:2px solid #e5e7eb;padding-left:12px;">
                <a href="{{ route('catering-staff.requests.index', ['filter' => 'pending']) }}" 
                   style="background:{{ isset($filter) && $filter == 'pending' ? '#f59e0b' : '#fef3c7' }};color:{{ isset($filter) && $filter == 'pending' ? 'white' : '#92400e' }};border-radius:8px;padding:8px 14px;text-decoration:none;font-size:13px;font-weight:500;">
                    Pending
                </a>
                <a href="{{ route('catering-staff.requests.index', ['filter' => 'approved']) }}" 
                   style="background:{{ isset($filter) && $filter == 'approved' ? '#10b981' : '#d1fae5' }};color:{{ isset($filter) && $filter == 'approved' ? 'white' : '#065f46' }};border-radius:8px;padding:8px 14px;text-decoration:none;font-size:13px;font-weight:500;">
                    Approved
                </a>
                <a href="{{ route('catering-staff.requests.index', ['filter' => 'rejected']) }}" 
                   style="background:{{ isset($filter) && $filter == 'rejected' ? '#ef4444' : '#fee2e2' }};color:{{ isset($filter) && $filter == 'rejected' ? 'white' : '#991b1b' }};border-radius:8px;padding:8px 14px;text-decoration:none;font-size:13px;font-weight:500;">
                    Rejected
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($requests->count())
    <div style="background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                <tr>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">REQUEST ID</th>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">FLIGHT</th>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">REQUESTED DATE</th>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">STATUS</th>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">ITEMS</th>
                    <th style="padding:14px 16px;text-align:left;font-weight:600;color:#374151;font-size:13px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:14px 16px;font-weight:600;color:#1f2937;">#{{ $req->id }}</td>
                    <td style="padding:14px 16px;">
                        <div style="font-weight:500;color:#1f2937;">{{ $req->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#6b7280;">{{ $req->flight->origin }} → {{ $req->flight->destination }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ \Carbon\Carbon::parse($req->flight->departure_time)->format('M d, Y H:i') }}</div>
                    </td>
                    <td style="padding:14px 16px;color:#4b5563;">
                        {{ \Carbon\Carbon::parse($req->requested_date)->format('M d, Y') }}
                    </td>
                    <td style="padding:14px 16px;">
                        @php
                            $statusConfig = [
                                'pending_inventory' => ['label' => 'Pending Inventory', 'bg' => '#fef3c7', 'color' => '#92400e'],
                                'pending_supervisor' => ['label' => 'Pending Supervisor', 'bg' => '#fed7aa', 'color' => '#9a3412'],
                                'supervisor_approved' => ['label' => 'Supervisor Approved', 'bg' => '#dbeafe', 'color' => '#1e40af'],
                                'sent_to_security' => ['label' => 'Sent to Security', 'bg' => '#e0e7ff', 'color' => '#4338ca'],
                                'security_approved' => ['label' => 'Security Approved', 'bg' => '#ddd6fe', 'color' => '#5b21b6'],
                                'catering_approved' => ['label' => 'Catering Approved', 'bg' => '#d1fae5', 'color' => '#065f46'],
                                'rejected' => ['label' => 'Rejected', 'bg' => '#fee2e2', 'color' => '#991b1b'],
                            ];
                            $config = $statusConfig[$req->status] ?? ['label' => ucwords(str_replace('_', ' ', $req->status)), 'bg' => '#f3f4f6', 'color' => '#374151'];
                        @endphp
                        <span style="display:inline-block;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:500;background:{{ $config['bg'] }};color:{{ $config['color'] }};">
                            {{ $config['label'] }}
                        </span>
                    </td>
                    <td style="padding:14px 16px;">
                        <details style="cursor:pointer;">
                            <summary style="color:#2563eb;font-weight:500;font-size:13px;">{{ $req->items->count() }} items</summary>
                            <ul style="margin-top:8px;padding-left:20px;font-size:12px;color:#4b5563;">
                                @foreach($req->items as $item)
                                    <li>{{ $item->product->name }} (Qty: {{ $item->quantity_requested }})</li>
                                @endforeach
                            </ul>
                        </details>
                    </td>
                    <td style="padding:14px 16px;">
                        <a href="{{ route('catering-staff.requests.show', $req) }}" 
                           style="display:inline-block;background:#2563eb;color:white;padding:6px 14px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:500;">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:16px;">{{ $requests->links() }}</div>
    </div>
    @else
    <div style="background:white;border-radius:12px;padding:60px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        <div style="font-size:48px;margin-bottom:16px;">📋</div>
        <h3 style="color:#6b7280;font-size:18px;margin-bottom:8px;">No requests found</h3>
        <p style="color:#9ca3af;margin-bottom:24px;">You haven't created any requests yet.</p>
        <a href="{{ route('catering-staff.requests.create') }}" 
           style="display:inline-block;background:#2563eb;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:500;">
            Create Your First Request
        </a>
    </div>
    @endif
</div>
@endsection