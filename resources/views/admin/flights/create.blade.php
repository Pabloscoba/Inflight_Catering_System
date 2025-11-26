@extends('layouts.app')

@section('page-title', 'Add Flight')
@section('page-description', 'Create a new flight schedule')

@section('content')
<style>
    .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
    .form-group label span { color: #dc2626; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #0b1a68; box-shadow: 0 0 0 3px rgba(11,26,104,0.1); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .error { color: #dc2626; font-size: 13px; margin-top: 6px; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 14px; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
</style>

<div class="card">
                <form method="POST" action="{{ route('admin.flights.store') }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label>Flight Number <span>*</span></label>
                            <input type="text" name="flight_number" value="{{ old('flight_number') }}" required placeholder="e.g., AB123">
                            @error('flight_number')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Airline <span>*</span></label>
                            <input type="text" name="airline" value="{{ old('airline') }}" required placeholder="e.g., Air Tanzania">
                            @error('airline')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Departure Time <span>*</span></label>
                            <input type="datetime-local" name="departure_time" value="{{ old('departure_time') }}" required>
                            @error('departure_time')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Arrival Time</label>
                            <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time') }}">
                            @error('arrival_time')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Origin <span>*</span></label>
                            <input type="text" name="origin" value="{{ old('origin') }}" required placeholder="Departure airport/city">
                            @error('origin')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Destination <span>*</span></label>
                            <input type="text" name="destination" value="{{ old('destination') }}" required placeholder="Arrival airport/city">
                            @error('destination')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Aircraft Type</label>
                            <input type="text" name="aircraft_type" value="{{ old('aircraft_type') }}" placeholder="e.g., Boeing 737">
                            @error('aircraft_type')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Passenger Capacity</label>
                            <input type="number" name="passenger_capacity" value="{{ old('passenger_capacity') }}" min="1" placeholder="Number of seats">
                            @error('passenger_capacity')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" required>
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="boarding" {{ old('status') == 'boarding' ? 'selected' : '' }}>Boarding</option>
                            <option value="departed" {{ old('status') == 'departed' ? 'selected' : '' }}>Departed</option>
                            <option value="arrived" {{ old('status') == 'arrived' ? 'selected' : '' }}>Arrived</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" placeholder="Additional notes or remarks">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Flight</button>
                    </div>
                </form>
            </div>
@endsection
