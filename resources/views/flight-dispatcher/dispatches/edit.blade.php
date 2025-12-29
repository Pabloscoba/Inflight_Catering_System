@extends('layouts.app')

@section('title', 'Edit Dispatch Record')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>✏️ Edit Dispatch Record #{{ $dispatch->id }}</h1>
        <a href="{{ route('flight-dispatcher.dispatches.show', $dispatch) }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('flight-dispatcher.dispatches.update', $dispatch) }}">
        @csrf
        @method('PUT')

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">✈️ Flight Information</h3>
            <p><strong>Flight:</strong> {{ $dispatch->flight->flight_number }} - {{ $dispatch->flight->origin }} → {{ $dispatch->flight->destination }}</p>
            <p><strong>Departure:</strong> {{ $dispatch->flight->departure_time->format('Y-m-d H:i') }}</p>
        </div>

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">✅ Update Checklist Items</h3>
            
            <div style="display:grid;gap:20px">
                {{-- Fuel Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">⛽ Fuel Status</h4>
                    <select name="fuel_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending" {{ $dispatch->fuel_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $dispatch->fuel_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="insufficient" {{ $dispatch->fuel_status === 'insufficient' ? 'selected' : '' }}>Insufficient</option>
                    </select>
                    <textarea name="fuel_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2">{{ $dispatch->fuel_notes }}</textarea>
                </div>

                {{-- Crew Readiness --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">👥 Crew Readiness</h4>
                    <select name="crew_readiness" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending" {{ $dispatch->crew_readiness === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $dispatch->crew_readiness === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="not_ready" {{ $dispatch->crew_readiness === 'not_ready' ? 'selected' : '' }}>Not Ready</option>
                    </select>
                    <textarea name="crew_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2">{{ $dispatch->crew_notes }}</textarea>
                </div>

                {{-- Catering Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">🍽️ Catering Status</h4>
                    <select name="catering_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending" {{ $dispatch->catering_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $dispatch->catering_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="delayed" {{ $dispatch->catering_status === 'delayed' ? 'selected' : '' }}>Delayed</option>
                    </select>
                    <textarea name="catering_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2">{{ $dispatch->catering_notes }}</textarea>
                </div>

                {{-- Baggage Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">🧳 Baggage Status</h4>
                    <select name="baggage_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending" {{ $dispatch->baggage_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $dispatch->baggage_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="delayed" {{ $dispatch->baggage_status === 'delayed' ? 'selected' : '' }}>Delayed</option>
                    </select>
                    <textarea name="baggage_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2">{{ $dispatch->baggage_notes }}</textarea>
                </div>
            </div>
        </div>

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📝 Additional Information</h3>
            
            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Operational Notes</label>
                <textarea name="operational_notes" placeholder="Add operational notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="3">{{ $dispatch->operational_notes }}</textarea>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Delay Reason</label>
                <textarea name="delay_reason" placeholder="Describe any delays..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="3">{{ $dispatch->delay_reason }}</textarea>
            </div>

            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px">Dispatch Recommendation</label>
                <select name="dispatch_recommendation" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                    <option value="">-- None --</option>
                    <option value="clear_to_dispatch" {{ $dispatch->dispatch_recommendation === 'clear_to_dispatch' ? 'selected' : '' }}>Clear to Dispatch</option>
                    <option value="hold" {{ $dispatch->dispatch_recommendation === 'hold' ? 'selected' : '' }}>Hold</option>
                    <option value="delay" {{ $dispatch->dispatch_recommendation === 'delay' ? 'selected' : '' }}>Delay</option>
                </select>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" style="padding:12px 24px;background:#3b82f6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                💾 Update Dispatch Record
            </button>
            <a href="{{ route('flight-dispatcher.dispatches.show', $dispatch) }}" style="padding:12px 24px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600;display:inline-block">
                Cancel
            </a>
        </div>
    </form>

@endsection
