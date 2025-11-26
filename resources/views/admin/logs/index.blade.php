@extends('layouts.app')

@section('page-title', 'Audit Logs')
@section('page-description', 'Track all system activities and changes')

@section('content')
<style>
    .filter-section { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; border: 1px solid #ddd; }
    .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #64748b; color: white; }
    .btn-danger { background: #dc2626; color: white; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; font-size: 14px; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-created { background: #d1fae5; color: #065f46; }
    .badge-updated { background: #dbeafe; color: #1e40af; }
    .badge-deleted { background: #fee2e2; color: #991b1b; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .actions { display: flex; gap: 8px; }
    .model-badge { padding: 4px 10px; background: #f1f5f9; border-radius: 4px; font-size: 12px; color: #475569; font-family: monospace; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Filters Section -->
<div class="filter-section">
    <form method="GET" action="{{ route('admin.logs.index') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label>User</label>
                <select name="user_id" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Model Type</label>
                <select name="subject_type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type['value'] }}" {{ request('subject_type') == $type['value'] ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Event</label>
                <select name="event" class="form-control">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div class="form-group">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">Clear Filters</a>
        </div>
    </form>
</div>

<!-- Audit Logs Table -->
<div class="card">
    @if($logs->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td><strong>#{{ $log->id }}</strong></td>
                        <td>
                            @if($log->causer)
                                <div>{{ $log->causer->name }}</div>
                                <div style="font-size: 12px; color: #64748b;">{{ $log->causer->email }}</div>
                            @else
                                <span style="color: #94a3b8;">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $log->event }}">
                                {{ ucfirst($log->event) }}
                            </span>
                        </td>
                        <td>
                            <span class="model-badge">{{ class_basename($log->subject_type) }}</span>
                        </td>
                        <td>
                            {{ $log->description ?? 'No description' }}
                        </td>
                        <td>
                            <div>{{ $log->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $log->created_at->format('H:i A') }}</div>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.logs.show', $log->id) }}" class="btn btn-sm btn-secondary">View Details</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding: 20px;">
            {{ $logs->withQueryString()->links() }}
        </div>
    @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 style="margin-bottom: 8px; color: #475569;">No Audit Logs Found</h3>
            <p>No activity logs match your current filters.</p>
        </div>
    @endif
</div>
@endsection
