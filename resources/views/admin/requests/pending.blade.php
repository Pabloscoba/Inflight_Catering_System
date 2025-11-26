@extends('layouts.app')

@section('page-title', 'Pending Approvals')
@section('page-description', 'Review and approve pending requests')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #dc2626; color: white; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; background: #fef3c7; color: #92400e; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-info { background: #dbeafe; color: #1e3a8a; border-left: 4px solid #3b82f6; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .actions { display: flex; gap: 8px; }
    .priority-indicator { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; }
    .priority-high { background: #ef4444; }
    .priority-normal { background: #3b82f6; }
</style>

@if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($requests->count() > 0)
                <div class="alert alert-info">
                    <strong>{{ $requests->count() }}</strong> request(s) awaiting approval. Please review and take action.
                </div>
            @endif

            <div class="card">
                @if($requests->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>ID</th>
                                <th>Flight Details</th>
                                <th>Requester</th>
                                <th>Items</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                @php
                                    $daysDiff = now()->diffInDays($req->flight->departure_time, false);
                                    $isUrgent = $daysDiff <= 1;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="priority-indicator {{ $isUrgent ? 'priority-high' : 'priority-normal' }}" title="{{ $isUrgent ? 'Urgent - Flight departing soon' : 'Normal priority' }}"></span>
                                    </td>
                                    <td><strong>#{{ $req->id }}</strong></td>
                                    <td>
                                        <strong>{{ $req->flight->flight_number }}</strong><br>
                                        <small style="color: #64748b;">{{ $req->flight->origin }} → {{ $req->flight->destination }}</small><br>
                                        <small style="color: {{ $isUrgent ? '#dc2626' : '#64748b' }}; font-weight: {{ $isUrgent ? '600' : 'normal' }};">
                                            Departs: {{ $req->flight->departure_time->format('d M Y H:i') }}
                                            @if($isUrgent)
                                                <span style="color: #dc2626;">({{ $daysDiff >= 0 ? 'in ' . $daysDiff . 'd' : 'overdue' }})</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ $req->requester->name }}</td>
                                    <td>{{ $req->items->count() }} items<br><small style="color: #64748b;">{{ $req->getTotalItemsCount() }} total qty</small></td>
                                    <td>{{ $req->requested_date->format('d M Y') }}<br><small style="color: #64748b;">{{ $req->requested_date->diffForHumans() }}</small></td>
                                    <td><span class="badge">Pending</span></td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm btn-secondary">View</a>
                                            <a href="{{ route('admin.requests.approve-form', $req) }}" class="btn btn-sm btn-primary">Approve</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <h3 style="margin-bottom: 8px; color: #475569;">No pending requests</h3>
                        <p>All requests have been processed. Great job!</p>
                    </div>
                @endif
            </div>
@endsection
