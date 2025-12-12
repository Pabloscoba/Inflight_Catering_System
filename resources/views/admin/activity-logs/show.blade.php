@extends('layouts.app')

@section('title', 'Activity Details')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>🔍 Activity Details</h1>
            <p>Detailed information about this activity</p>
        </div>
        <a href="{{ route('admin.activity-logs.index') }}" style="background:#e2e8f0;color:#2d3748;padding:10px 20px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;">
            ← Back to Logs
        </a>
    </div>
</div>

<!-- Main Activity Card -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:28px;">
        <h2 style="font-size:24px;font-weight:700;color:white;margin:0 0 8px 0;">{{ $activity->description }}</h2>
        <div style="color:rgba(255,255,255,0.9);font-size:14px;">{{ $activity->created_at->format('F d, Y \a\t h:i A') }}</div>
    </div>

    <!-- Basic Information -->
    <div style="padding:28px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">📌 Basic Information</h3>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;">
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Activity ID</div>
                <div style="font-size:18px;color:#1f2937;font-weight:600;">#{{ $activity->id }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Event Type</div>
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
                <span style="background:{{ $color['bg'] }};color:{{ $color['color'] }};padding:8px 16px;border-radius:12px;font-size:14px;font-weight:600;display:inline-block;">
                    {{ ucfirst($activity->event ?? 'N/A') }}
                </span>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Log Name</div>
                <div style="font-size:18px;color:#1f2937;font-weight:600;">{{ ucfirst($activity->log_name ?? 'default') }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Batch UUID</div>
                <div style="font-size:12px;color:#6b7280;font-family:monospace;">{{ $activity->batch_uuid ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- User Information -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0;">👤 User Information</h3>
    </div>
    
    <div style="padding:28px;">
        @if($activity->causer)
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;">
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">User Name</div>
                <div style="font-size:16px;color:#1f2937;font-weight:600;">{{ $activity->causer->name }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">User ID</div>
                <div style="font-size:16px;color:#1f2937;font-weight:600;">{{ $activity->causer->id }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Email</div>
                <div style="font-size:16px;color:#4299e1;font-weight:600;">{{ $activity->causer->email }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Role</div>
                <span style="background:#e0e7ff;color:#3730a3;padding:6px 12px;border-radius:8px;font-size:13px;font-weight:600;">
                    {{ $activity->causer->role->name ?? 'N/A' }}
                </span>
            </div>
        </div>
        @else
        <div style="text-align:center;padding:20px;color:#a0aec0;">
            <div style="font-size:14px;">Action performed by System</div>
        </div>
        @endif
    </div>
</div>

<!-- Subject Information -->
@if($activity->subject_type)
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0;">🎯 Subject Information</h3>
    </div>
    
    <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;">
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Subject Type</div>
                <div style="font-size:16px;color:#1f2937;font-weight:600;">{{ class_basename($activity->subject_type) }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Subject ID</div>
                <div style="font-size:16px;color:#1f2937;font-weight:600;">{{ $activity->subject_id }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Full Class Path</div>
                <div style="font-size:11px;color:#6b7280;font-family:monospace;">{{ $activity->subject_type }}</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Properties -->
@if($activity->properties && count($activity->properties) > 0)
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0;">📝 Properties</h3>
    </div>
    
    <div style="padding:28px;">
        @if(isset($activity->properties['attributes']) || isset($activity->properties['old']))
        <!-- Side by Side Comparison -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <!-- Old Values -->
            @if(isset($activity->properties['old']))
            <div>
                <div style="background:#fef3c7;color:#92400e;padding:10px 16px;border-radius:8px;font-weight:600;font-size:14px;margin-bottom:12px;">
                    🔴 Old Values
                </div>
                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;">
                    <pre style="font-family:monospace;font-size:12px;color:#4b5563;margin:0;overflow-x:auto;">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
            
            <!-- New Values -->
            @if(isset($activity->properties['attributes']))
            <div>
                <div style="background:#d1fae5;color:#065f46;padding:10px 16px;border-radius:8px;font-weight:600;font-size:14px;margin-bottom:12px;">
                    🟢 New Values
                </div>
                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;">
                    <pre style="font-family:monospace;font-size:12px;color:#4b5563;margin:0;overflow-x:auto;">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
        @else
        <!-- Full Properties -->
        <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:20px;">
            <pre style="font-family:monospace;font-size:12px;color:#4b5563;margin:0;overflow-x:auto;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Technical Details -->
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0;">⚙️ Technical Details</h3>
    </div>
    
    <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;">
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Created At</div>
                <div style="font-size:14px;color:#1f2937;">{{ $activity->created_at->format('M d, Y h:i:s A') }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Updated At</div>
                <div style="font-size:14px;color:#1f2937;">{{ $activity->updated_at->format('M d, Y h:i:s A') }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">Time Ago</div>
                <div style="font-size:14px;color:#1f2937;">{{ $activity->created_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Context -->
@if($activity->properties && isset($activity->properties['ip']) || isset($activity->properties['user_agent']))
<div style="background:white;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;margin-top:24px;">
    <div style="padding:24px 28px;border-bottom:2px solid #f3f4f6;">
        <h3 style="font-size:18px;font-weight:700;color:#1a202c;margin:0;">🌐 Request Context</h3>
    </div>
    
    <div style="padding:28px;">
        <div style="display:grid;gap:20px;">
            @if(isset($activity->properties['ip']))
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">IP Address</div>
                <div style="font-size:14px;color:#1f2937;font-family:monospace;">{{ $activity->properties['ip'] }}</div>
            </div>
            @endif
            
            @if(isset($activity->properties['user_agent']))
            <div>
                <div style="font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;margin-bottom:6px;">User Agent</div>
                <div style="font-size:12px;color:#6b7280;font-family:monospace;word-break:break-all;">{{ $activity->properties['user_agent'] }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@endsection
