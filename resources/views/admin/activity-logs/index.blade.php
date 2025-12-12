@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>📋 Activity Logs</h1>
            <p>Track all system activities and user actions</p>
        </div>
        <div style="display:flex;gap:12px;">
            <button onclick="document.getElementById('exportForm').submit()" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                📥 Export Logs
            </button>
            <button onclick="document.getElementById('deleteModal').style.display='flex'" style="background:linear-gradient(135deg,#ff6b6b 0%,#ee5a6f 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                🗑️ Delete Old Logs
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📊</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ number_format($stats['total_activities']) }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Total Activities</div>
        </div>
    </div>

    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📅</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ number_format($stats['today_activities']) }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Today's Activities</div>
        </div>
    </div>

    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📆</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ number_format($stats['this_week']) }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">This Week</div>
        </div>
    </div>

    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">🗓️</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ number_format($stats['this_month']) }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">This Month</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">🔍 Filter Logs</h3>
    
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" id="filterForm">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">User</label>
                <select name="user_id" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->roles->pluck('name')->first() ?? 'No Role' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">Event Type</label>
                <select name="event" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
                    <option value="">All Events</option>
                    @foreach($eventTypes as $event)
                        <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                            {{ ucfirst($event) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">Log Name</label>
                <select name="log_name" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
                    <option value="">All Logs</option>
                    @foreach($logNames as $logName)
                        <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                            {{ ucfirst($logName) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">Subject Type</label>
                <select name="subject_type" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
                    <option value="">All Types</option>
                    @foreach($subjectTypes as $type)
                        <option value="{{ $type }}" {{ request('subject_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
            </div>
            
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:#4a5568;margin-bottom:6px;">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:10px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
            </div>
        </div>
        
        @if(request()->hasAny(['user_id', 'event', 'log_name', 'subject_type', 'date_from', 'date_to']))
        <div style="margin-top:16px;">
            <a href="{{ route('admin.activity-logs.index') }}" style="background:#e2e8f0;color:#2d3748;padding:8px 16px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;display:inline-block;">
                Clear Filters
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Activity Logs Table -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📋 Activity Logs</h2>
        <p style="font-size:13px;color:#6b7280;margin:4px 0 0 0;">{{ $activities->total() }} total activities</p>
    </div>

    @if($activities->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">Time</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">User</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">Event</th>
                    <th style="padding:14px 20px;text-align:left;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">Description</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">Subject</th>
                    <th style="padding:14px 20px;text-align:center;font-weight:600;color:#374151;font-size:13px;text-transform:uppercase;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <td style="padding:16px 20px;">
                        <div style="font-size:13px;color:#4a5568;font-weight:600;">{{ $activity->created_at->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ $activity->created_at->format('h:i A') }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-weight:600;color:#1f2937;font-size:14px;">{{ $activity->causer?->name ?? 'System' }}</div>
                        <div style="font-size:12px;color:#6b7280;">{{ $activity->causer?->roles->pluck('name')->first() ?? 'System Administrator' }}</div>
                    </td>
                    <td style="padding:16px 20px;">
                        @php
                            $eventColors = [
                                'created' => ['bg' => '#d1fae5', 'color' => '#065f46'],
                                'updated' => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                                'deleted' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                'login' => ['bg' => '#e0e7ff', 'color' => '#3730a3'],
                                'logout' => ['bg' => '#f3f4f6', 'color' => '#374151'],
                            ];
                            $color = $eventColors[$activity->event] ?? ['bg' => '#fef3c7', 'color' => '#92400e'];
                        @endphp
                        <span style="background:{{ $color['bg'] }};color:{{ $color['color'] }};padding:6px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                            {{ ucfirst($activity->event ?? 'N/A') }}
                        </span>
                    </td>
                    <td style="padding:16px 20px;">
                        <div style="font-size:13px;color:#4a5568;">{{ Str::limit($activity->description, 80) }}</div>
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        @if($activity->subject_type)
                        <div style="font-size:12px;color:#6b7280;">{{ class_basename($activity->subject_type) }}</div>
                        <div style="font-size:11px;color:#9ca3af;">ID: {{ $activity->subject_id }}</div>
                        @else
                        <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>
                    <td style="padding:16px 20px;text-align:center;">
                        <a href="{{ route('admin.activity-logs.show', $activity) }}" style="background:#4299e1;color:white;padding:6px 16px;border-radius:8px;font-weight:600;font-size:12px;text-decoration:none;display:inline-block;">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding:20px 28px;border-top:1px solid #f3f4f6;">
        {{ $activities->links() }}
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">📋</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Activity Logs Found</div>
        <div style="font-size:14px;color:#a0aec0;">Try adjusting your filters</div>
    </div>
    @endif
</div>

<!-- Export Form (Hidden) -->
<form id="exportForm" method="GET" action="{{ route('admin.activity-logs.export') }}" style="display:none;">
    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
    <input type="hidden" name="event" value="{{ request('event') }}">
    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
</form>

<!-- Delete Old Logs Modal -->
<div id="deleteModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1000;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:500px;width:90%;">
        <h3 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 16px 0;">🗑️ Delete Old Logs</h3>
        <p style="color:#6b7280;font-size:14px;margin:0 0 24px 0;">Delete activity logs older than specified number of days.</p>
        
        <form method="POST" action="{{ route('admin.activity-logs.delete-old') }}">
            @csrf
            @method('DELETE')
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Delete logs older than (days)</label>
                <input type="number" name="days" min="1" max="365" value="90" required style="width:100%;padding:12px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;">
                <div style="font-size:12px;color:#9ca3af;margin-top:6px;">Recommended: 90 days</div>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 20px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" onclick="return confirm('Are you sure you want to delete old logs?')" style="background:linear-gradient(135deg,#ff6b6b 0%,#ee5a6f 100%);color:white;border:none;padding:10px 20px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                    Delete Logs
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
