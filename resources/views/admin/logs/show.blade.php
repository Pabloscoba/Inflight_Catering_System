@extends('layouts.app')

@section('page-title', 'Audit Log Details')
@section('page-description', 'View detailed information about this activity')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-secondary { background: #64748b; color: white; }
    .btn-secondary:hover { background: #475569; }
    .detail-card { background: white; border-radius: 8px; padding: 24px; margin-bottom: 20px; border: 1px solid #ddd; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
    .detail-item { display: flex; flex-direction: column; gap: 6px; }
    .detail-label { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; }
    .detail-value { font-size: 15px; color: #1e293b; }
    .badge { padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
    .badge-created { background: #d1fae5; color: #065f46; }
    .badge-updated { background: #dbeafe; color: #1e40af; }
    .badge-deleted { background: #fee2e2; color: #991b1b; }
    .model-badge { padding: 6px 12px; background: #f1f5f9; border-radius: 4px; font-size: 14px; color: #475569; font-family: monospace; display: inline-block; }
    .changes-section { margin-top: 30px; }
    .changes-title { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px; }
    .changes-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin-bottom: 12px; }
    .change-item { display: flex; gap: 30px; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
    .change-item:last-child { border-bottom: none; }
    .change-field { flex: 0 0 150px; font-weight: 600; color: #475569; }
    .change-values { flex: 1; display: flex; gap: 20px; }
    .change-old, .change-new { flex: 1; }
    .change-label { font-size: 12px; color: #64748b; margin-bottom: 4px; }
    .change-value { padding: 8px 12px; background: white; border-radius: 4px; font-family: monospace; font-size: 13px; }
    .change-old .change-value { border-left: 3px solid #dc2626; }
    .change-new .change-value { border-left: 3px solid #059669; }
    .no-changes { text-align: center; padding: 40px; color: #94a3b8; }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">← Back to Audit Logs</a>
</div>

<div class="detail-card">
    <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 24px; color: #1e293b;">Activity Information</h2>
    
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">Log ID</span>
            <span class="detail-value">#{{ $log->id }}</span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Event Type</span>
            <span class="badge badge-{{ $log->event }}">{{ ucfirst($log->event) }}</span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Performed By</span>
            <span class="detail-value">
                @if($log->causer)
                    {{ $log->causer->name }} ({{ $log->causer->email }})
                @else
                    System
                @endif
            </span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Date & Time</span>
            <span class="detail-value">{{ $log->created_at->format('F d, Y \a\t H:i A') }}</span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Model Type</span>
            <span class="model-badge">{{ class_basename($log->subject_type) }}</span>
        </div>

        <div class="detail-item">
            <span class="detail-label">Model ID</span>
            <span class="detail-value">#{{ $log->subject_id ?? 'N/A' }}</span>
        </div>

        @if($log->description)
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span class="detail-label">Description</span>
                <span class="detail-value">{{ $log->description }}</span>
            </div>
        @endif
    </div>

    <!-- Changes Section -->
    <div class="changes-section">
        <h3 class="changes-title">Changed Attributes</h3>
        
        @if($log->properties && $log->properties->has('attributes'))
            <div class="changes-box">
                @php
                    $attributes = $log->properties->get('attributes', []);
                    $old = $log->properties->get('old', []);
                @endphp

                @if(count($attributes) > 0)
                    @foreach($attributes as $key => $newValue)
                        <div class="change-item">
                            <div class="change-field">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                            <div class="change-values">
                                @if(isset($old[$key]))
                                    <div class="change-old">
                                        <div class="change-label">Old Value</div>
                                        <div class="change-value">{{ is_array($old[$key]) ? json_encode($old[$key]) : ($old[$key] ?? 'null') }}</div>
                                    </div>
                                @endif
                                <div class="change-new">
                                    <div class="change-label">{{ isset($old[$key]) ? 'New Value' : 'Value' }}</div>
                                    <div class="change-value">{{ is_array($newValue) ? json_encode($newValue) : ($newValue ?? 'null') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-changes">No attribute changes recorded</div>
                @endif
            </div>
        @else
            <div class="no-changes">No changes recorded for this activity</div>
        @endif
    </div>

    <!-- Raw JSON Data (for debugging) -->
    @if(config('app.debug'))
        <div style="margin-top: 30px;">
            <details>
                <summary style="cursor: pointer; font-weight: 600; color: #475569; margin-bottom: 10px;">Show Raw JSON Data</summary>
                <pre style="background: #1e293b; color: #e2e8f0; padding: 16px; border-radius: 6px; overflow-x: auto; font-size: 12px;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
            </details>
        </div>
    @endif
</div>
@endsection
