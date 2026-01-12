@extends('layouts.app')

@section('title', 'Add New Flight')

@section('content')
<div style="padding:12px; max-width:720px; margin:20px auto;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <h1 style="font-size:22px; font-weight:700; margin:0; color:#1f2937;">✈️ Add New Flight</h1>
        <a href="{{ route('flight-operations-manager.dashboard') }}" style="display:inline-flex; align-items:center; padding:8px 16px; background:#6b7280; color:#fff; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none;">
            <svg style="width:14px; height:14px; margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;padding:12px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
            <strong style="display:block; margin-bottom:6px;">⚠️ Please correct the following errors:</strong>
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $err)
                    <li style="margin:3px 0;">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:#fff; border-radius:10px; padding:24px; box-shadow:0 2px 4px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('flight-operations-manager.flights.store') }}">
            @csrf

            <!-- Flight Number -->
            <div style="margin-bottom:16px;">
                <label for="flight_number" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                    Flight Number <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" 
                       name="flight_number" 
                       id="flight_number" 
                       value="{{ old('flight_number') }}"
                       style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; @error('flight_number') border-color:#dc2626; @enderror"
                       placeholder="e.g., TZ101, AA123"
                       required
                       maxlength="20">
                @error('flight_number')
                    <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Airline -->
            <div style="margin-bottom:16px;">
                <label for="airline" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                    Airline <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" 
                       name="airline" 
                       id="airline" 
                       value="Air Tanzania"
                       style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; @error('airline') border-color:#dc2626; @enderror"
                       placeholder="Air Tanzania, KLM, etc."
                       required
                       maxlength="100">
                @error('airline')
                    <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Origin and Destination -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label for="origin" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        🛫 Origin Airport <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" 
                           name="origin" 
                           id="origin" 
                           value="{{ old('origin') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; text-transform:uppercase; @error('origin') border-color:#dc2626; @enderror"
                           placeholder="e.g., DAR, ARSHA"
                           required
                           maxlength="10">
                    @error('origin')
                        <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="destination" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        🛬 Destination Airport <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" 
                           name="destination" 
                           id="destination" 
                           value="{{ old('destination') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; text-transform:uppercase; @error('destination') border-color:#dc2626; @enderror"
                           placeholder="e.g., NBO, KGL"
                           required
                           maxlength="10">
                    @error('destination')
                        <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Departure and Arrival Times -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label for="departure_time" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        🕐 Departure Time <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="departure_time" 
                           id="departure_time" 
                           value="{{ old('departure_time') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; @error('departure_time') border-color:#dc2626; @enderror"
                           required>
                    @error('departure_time')
                        <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_time" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        🕐 Arrival Time <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="arrival_time" 
                           id="arrival_time" 
                           value="{{ old('arrival_time') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; @error('arrival_time') border-color:#dc2626; @enderror"
                           required>
                    @error('arrival_time')
                        <p style="margin-top:4px; font-size:12px; color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Aircraft Type and Passenger Count -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label for="aircraft_type" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        ✈️ Aircraft Type
                    </label>
                    <input type="text" 
                           name="aircraft_type" 
                           id="aircraft_type" 
                           value="{{ old('aircraft_type') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px;"
                           placeholder="e.g., Boeing 737, Airbus A320"
                           maxlength="50">
                    <p style="margin-top:4px; font-size:12px; color:#6b7280;">Optional</p>
                </div>

                <div>
                    <label for="passenger_count" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                        👥 Passenger Count
                    </label>
                    <input type="number" 
                           name="passenger_count" 
                           id="passenger_count" 
                           value="{{ old('passenger_count') }}"
                           style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px;"
                           placeholder="e.g., 150"
                           min="1">
                    <p style="margin-top:4px; font-size:12px; color:#6b7280;">Optional</p>
                </div>
            </div>

            <!-- Route -->
            <div style="margin-bottom:20px;">
                <label for="route" style="display:block; font-weight:600; margin-bottom:6px; font-size:13px; color:#374151;">
                    🗺️ Route Details
                </label>
                <input type="text" 
                       name="route" 
                       id="route" 
                       value="{{ old('route') }}"
                       style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px;"
                       placeholder="e.g., DAR-JRO-DAR (optional)"
                       maxlength="200">
                <p style="margin-top:4px; font-size:12px; color:#6b7280;">Optional - Full route if multi-stop flight</p>
            </div>

            <!-- Submit Buttons -->
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-top:24px; padding-top:16px; border-top:1px solid #e5e7eb;">
                <a href="{{ route('flight-operations-manager.dashboard') }}" 
                   style="display:inline-flex; align-items:center; padding:9px 20px; background:#f3f4f6; color:#4b5563; font-weight:600; border-radius:6px; text-decoration:none; font-size:14px;">
                    Cancel
                </a>
                <button type="submit" 
                        style="display:inline-flex; align-items:center; padding:9px 20px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; font-weight:600; border-radius:6px; border:none; cursor:pointer; font-size:14px;">
                    <svg style="width:16px; height:16px; margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Flight
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
