@extends('layouts.app')

@section('title', 'Dispatch Record Details')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>📋 Dispatch Record #{{ $dispatch->id }}</h1>
        <div style="display:flex;gap:10px">
            <a href="{{ route('flight-dispatcher.dispatches.edit', $dispatch) }}" style="padding:10px 16px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ✏️ Edit
            </a>
            <a href="{{ route('flight-dispatcher.dispatches.index') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ← Back
            </a>
        </div>
    </div>

    {{-- Flight Information --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">✈️ Flight Information</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Flight Number</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->flight->flight_number }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Route</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->flight->origin }} → {{ $dispatch->flight->destination }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Departure Time</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->flight->departure_time->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Status</p>
                <p style="font-weight:600;margin:4px 0 0">
                    @if($dispatch->overall_status === 'ready')
                        <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Ready</span>
                    @elseif($dispatch->overall_status === 'in_progress')
                        <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">In Progress</span>
                    @else
                        <span style="padding:4px 12px;border-radius:12px;background:#f3f4f6;color:#374151;font-size:12px">{{ ucfirst($dispatch->overall_status) }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Progress Overview --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📊 Completion Progress</h3>
        <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;font-size:14px;color:#6b7280;margin-bottom:8px">
                <span>Overall Completion</span>
                <span>{{ $dispatch->getCompletionPercentage() }}%</span>
            </div>
            <div style="width:100%;background:#e5e7eb;border-radius:999px;height:12px;overflow:hidden">
                <div style="background:#3b82f6;height:100%;border-radius:999px;width:{{ $dispatch->getCompletionPercentage() }}%"></div>
            </div>
        </div>
    </div>

    {{-- Checklist Items --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:24px">
        
        {{-- Fuel Status --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                <h4 style="margin:0;font-size:16px;font-weight:600">⛽ Fuel Status</h4>
                @if($dispatch->fuel_status === 'confirmed')
                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Confirmed</span>
                @elseif($dispatch->fuel_status === 'insufficient')
                    <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">Insufficient</span>
                @else
                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Pending</span>
                @endif
            </div>
            @if($dispatch->fuel_confirmed_at)
                <p style="font-size:14px;color:#6b7280;margin:0">Confirmed: {{ $dispatch->fuel_confirmed_at->format('Y-m-d H:i') }}</p>
            @endif
            @if($dispatch->fuel_notes)
                <p style="font-size:14px;margin:8px 0 0;padding:8px;background:#f9fafb;border-radius:6px">{{ $dispatch->fuel_notes }}</p>
            @endif
        </div>

        {{-- Crew Readiness --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                <h4 style="margin:0;font-size:16px;font-weight:600">👥 Crew Readiness</h4>
                @if($dispatch->crew_readiness === 'confirmed')
                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Confirmed</span>
                @elseif($dispatch->crew_readiness === 'not_ready')
                    <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">Not Ready</span>
                @else
                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Pending</span>
                @endif
            </div>
            @if($dispatch->crew_confirmed_at)
                <p style="font-size:14px;color:#6b7280;margin:0">Confirmed: {{ $dispatch->crew_confirmed_at->format('Y-m-d H:i') }}</p>
            @endif
            @if($dispatch->crew_notes)
                <p style="font-size:14px;margin:8px 0 0;padding:8px;background:#f9fafb;border-radius:6px">{{ $dispatch->crew_notes }}</p>
            @endif
        </div>

        {{-- Catering Status --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                <h4 style="margin:0;font-size:16px;font-weight:600">🍽️ Catering Status</h4>
                @if($dispatch->catering_status === 'confirmed')
                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Confirmed</span>
                @elseif($dispatch->catering_status === 'delayed')
                    <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">Delayed</span>
                @else
                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Pending</span>
                @endif
            </div>
            @if($dispatch->catering_confirmed_at)
                <p style="font-size:14px;color:#6b7280;margin:0">Confirmed: {{ $dispatch->catering_confirmed_at->format('Y-m-d H:i') }}</p>
            @endif
            @if($dispatch->catering_notes)
                <p style="font-size:14px;margin:8px 0 0;padding:8px;background:#f9fafb;border-radius:6px">{{ $dispatch->catering_notes }}</p>
            @endif
        </div>

        {{-- Baggage Status --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                <h4 style="margin:0;font-size:16px;font-weight:600">🧳 Baggage Status</h4>
                @if($dispatch->baggage_status === 'confirmed')
                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Confirmed</span>
                @elseif($dispatch->baggage_status === 'delayed')
                    <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">Delayed</span>
                @else
                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Pending</span>
                @endif
            </div>
            @if($dispatch->baggage_confirmed_at)
                <p style="font-size:14px;color:#6b7280;margin:0">Confirmed: {{ $dispatch->baggage_confirmed_at->format('Y-m-d H:i') }}</p>
            @endif
            @if($dispatch->baggage_notes)
                <p style="font-size:14px;margin:8px 0 0;padding:8px;background:#f9fafb;border-radius:6px">{{ $dispatch->baggage_notes }}</p>
            @endif
        </div>
    </div>

    {{-- Operational Notes --}}
    @if($dispatch->operational_notes || $dispatch->delay_reason)
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📝 Notes & Reports</h3>
        
        @if($dispatch->operational_notes)
        <div style="margin-bottom:16px">
            <p style="color:#6b7280;font-size:14px;margin:0 0 8px">Operational Notes</p>
            <p style="padding:12px;background:#f9fafb;border-radius:6px;margin:0">{{ $dispatch->operational_notes }}</p>
        </div>
        @endif

        @if($dispatch->delay_reason)
        <div>
            <p style="color:#6b7280;font-size:14px;margin:0 0 8px">Delay Reason</p>
            <p style="padding:12px;background:#fef3c7;border-left:4px solid #f59e0b;border-radius:6px;margin:0">{{ $dispatch->delay_reason }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Dispatcher Info --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">👤 Dispatcher Information</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Dispatcher</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->dispatcher->name }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Created At</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Last Updated</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $dispatch->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

@endsection
