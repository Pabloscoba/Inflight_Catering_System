@extends('layouts.app')

@section('page-title', 'Rejected Requests')
@section('page-description', 'View all rejected requests')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
    .empty-state h3 { margin-bottom: 8px; color: #475569; }
    .actions { display: flex; gap: 8px; }
    .reason-box { background: #fef2f2; padding: 8px 12px; border-radius: 6px; margin-top: 6px; font-size: 12px; color: #991b1b; border-left: 3px solid #dc2626; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    @if($requests->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Flight Details</th>
                    <th>Requester</th>
                    <th>Items</th>
                    <th>Rejected Date</th>
                    <th>Rejected By</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td><strong>#{{ $request->id }}</strong></td>
                        <td>
                            <div><strong>{{ $request->flight->flight_number }}</strong></div>
                            <div style="font-size: 12px; color: #64748b;">
                                {{ $request->flight->origin }} → {{ $request->flight->destination }}
                            </div>
                        </td>
                        <td>
                            <div>{{ $request->requester->name }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $request->requester->email }}</div>
                        </td>
                        <td>
                            <div>{{ $request->items->count() }} item(s)</div>
                            <div style="font-size: 12px; color: #64748b;">
                                Requested: {{ $request->items->sum('quantity_requested') }} units
                            </div>
                        </td>
                        <td>
                            <div>{{ $request->approved_date ? $request->approved_date->format('M d, Y') : 'N/A' }}</div>
                            <div style="font-size: 12px; color: #64748b;">
                                {{ $request->approved_date ? $request->approved_date->format('H:i A') : '' }}
                            </div>
                        </td>
                        <td>{{ $request->approver ? $request->approver->name : 'System' }}</td>
                        <td>
                            @if($request->rejection_reason)
                                <div class="reason-box">{{ $request->rejection_reason }}</div>
                            @else
                                <span style="color: #94a3b8; font-size: 12px;">No reason provided</span>
                            @endif
                        </td>
                        <td><span class="badge badge-danger">Rejected</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-sm btn-secondary">View</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding: 20px;">
            {{ $requests->links() }}
        </div>
    @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3>No Rejected Requests</h3>
            <p>There are no rejected requests yet.</p>
        </div>
    @endif
</div>
@endsection
