@extends('layouts.app')

@section('page-title', 'All Requests')
@section('page-description', 'Manage flight catering requests')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #dc2626; color: white; }
    .filters { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #475569; }
    .filter-group input, .filter-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #dc2626; }
    .pagination { display: flex; gap: 8px; justify-content: center; padding: 20px; }
    .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; }
    .pagination .active { background: #0b1a68; color: white; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .actions { display: flex; gap: 8px; }
</style>

@if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="filters">
                <form method="GET" action="{{ route('admin.requests.index') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Search Requester</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name...">
                        </div>
                        <div class="filter-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Flight</label>
                            <select name="flight_id">
                                <option value="">All Flights</option>
                                @foreach($flights as $flight)
                                    <option value="{{ $flight->id }}" {{ request('flight_id') == $flight->id ? 'selected' : '' }}>{{ $flight->flight_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                @if($requests->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Flight</th>
                                <th>Requester</th>
                                <th>Items</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td><strong>#{{ $req->id }}</strong></td>
                                    <td>{{ $req->flight->flight_number }}<br><small style="color: #94a3b8;">{{ $req->flight->origin }} → {{ $req->flight->destination }}</small></td>
                                    <td>{{ $req->requester->name }}</td>
                                    <td>{{ $req->items->count() }} items ({{ $req->getTotalItemsCount() }} qty)</td>
                                    <td>{{ $req->requested_date->format('d M Y') }}</td>
                                    <td><span class="badge" style="background: {{ $req->getStatusBackground() }}; color: {{ $req->getStatusColor() }};">{{ ucfirst($req->status) }}</span></td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm btn-secondary">View</a>
                                            @if($req->isPending())
                                                <a href="{{ route('admin.requests.approve-form', $req) }}" class="btn btn-sm btn-primary">Approve</a>
                                                <form method="POST" action="{{ route('admin.requests.destroy', $req) }}" style="display: inline;" onsubmit="return confirm('Delete this request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination">{{ $requests->links() }}</div>
                @else
                    <div class="empty-state">
                        <h3 style="margin-bottom: 8px; color: #475569;">No requests found</h3>
                        <p>Create your first request for a flight.</p>
                    </div>
                @endif
            </div>
@endsection
