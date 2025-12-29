@extends('layouts.app')

@section('title', 'Create Dispatch Record')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>➕ Create Dispatch Record</h1>
        <a href="{{ route('flight-dispatcher.dispatches.index') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('flight-dispatcher.dispatches.store') }}">
        @csrf

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">✈️ Flight Selection</h3>
            
            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Select Flight</label>
                <select name="flight_id" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                    <option value="">-- Select Flight --</option>
                    @foreach($flights as $flight)
                        <option value="{{ $flight->id }}">
                            {{ $flight->flight_number }} - {{ $flight->origin }} → {{ $flight->destination }} 
                            ({{ $flight->departure_time->format('Y-m-d H:i') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px">Related Request (Optional)</label>
                <select name="request_id" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                    <option value="">-- None --</option>
                    @foreach($requests as $request)
                        <option value="{{ $request->id }}">
                            Request #{{ $request->id }} - {{ $request->flight->flight_number }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">✅ Checklist Items</h3>
            
            <div style="display:grid;gap:20px">
                {{-- Fuel Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">⛽ Fuel Status</h4>
                    <select name="fuel_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="insufficient">Insufficient</option>
                    </select>
                    <textarea name="fuel_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2"></textarea>
                </div>

                {{-- Crew Readiness --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">👥 Crew Readiness</h4>
                    <select name="crew_readiness" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="not_ready">Not Ready</option>
                    </select>
                    <textarea name="crew_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2"></textarea>
                </div>

                {{-- Catering Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">🍽️ Catering Status</h4>
                    <select name="catering_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="delayed">Delayed</option>
                    </select>
                    <textarea name="catering_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2"></textarea>
                </div>

                {{-- Baggage Status --}}
                <div style="padding:16px;background:#f9fafb;border-radius:8px">
                    <h4 style="margin:0 0 12px;font-size:16px">🧳 Baggage Status</h4>
                    <select name="baggage_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:8px">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="delayed">Delayed</option>
                    </select>
                    <textarea name="baggage_notes" placeholder="Add notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="2"></textarea>
                </div>
            </div>
        </div>

        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📝 Additional Notes</h3>
            
            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Operational Notes</label>
                <textarea name="operational_notes" placeholder="Add operational notes..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="3"></textarea>
            </div>

            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px">Delay Reason (if applicable)</label>
                <textarea name="delay_reason" placeholder="Describe any delays..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="3"></textarea>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" style="padding:12px 24px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                ✅ Create Dispatch Record
            </button>
            <a href="{{ route('flight-dispatcher.dispatches.index') }}" style="padding:12px 24px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600;display:inline-block">
                Cancel
            </a>
        </div>
    </form>

@endsection
