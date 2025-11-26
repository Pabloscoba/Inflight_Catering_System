@extends('layouts.app')

@section('page-title', 'Edit Flight')
@section('page-description', 'Update flight details')

@section('content')
<style>
    .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .info-box { background: #dbeafe; border-left: 4px solid #0891b2; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; color: #0c4a6e; font-size: 14px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
    .form-group label span { color: #dc2626; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #0b1a68; box-shadow: 0 0 0 3px rgba(11,26,104,0.1); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .error { color: #dc2626; font-size: 13px; margin-top: 6px; }
    .form-actions { display: flex; gap: 12px; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 14px; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .btn-danger { background: #dc2626; color: white; }
    .btn-danger:hover { background: #b91c1c; }
    .btn-group { display: flex; gap: 12px; }
</style>

<div class="card">
                <div class="info-box">
                    <strong>Flight ID:</strong> {{ $flight->id }} | 
                    <strong>Created:</strong> {{ $flight->created_at->format('d M Y') }}
                </div>

                <form method="POST" action="{{ route('admin.flights.update', $flight) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label>Flight Number <span>*</span></label>
                            <input type="text" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" required>
                            @error('flight_number')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Airline <span>*</span></label>
                            <input type="text" name="airline" value="{{ old('airline', $flight->airline) }}" required>
                            @error('airline')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Departure Time <span>*</span></label>
                            <input type="datetime-local" name="departure_time" value="{{ old('departure_time', $flight->departure_time->format('Y-m-d\TH:i')) }}" required>
                            @error('departure_time')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Arrival Time</label>
                            <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time', $flight->arrival_time ? $flight->arrival_time->format('Y-m-d\TH:i') : '') }}">
                            @error('arrival_time')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Origin <span>*</span></label>
                            <input type="text" name="origin" value="{{ old('origin', $flight->origin) }}" required>
                            @error('origin')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Destination <span>*</span></label>
                            <input type="text" name="destination" value="{{ old('destination', $flight->destination) }}" required>
                            @error('destination')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Aircraft Type</label>
                            <input type="text" name="aircraft_type" value="{{ old('aircraft_type', $flight->aircraft_type) }}">
                            @error('aircraft_type')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Passenger Capacity</label>
                            <input type="number" name="passenger_capacity" value="{{ old('passenger_capacity', $flight->passenger_capacity) }}" min="1">
                            @error('passenger_capacity')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span>*</span></label>
                        <select name="status" required>
                            <option value="scheduled" {{ old('status', $flight->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="boarding" {{ old('status', $flight->status) == 'boarding' ? 'selected' : '' }}>Boarding</option>
                            <option value="departed" {{ old('status', $flight->status) == 'departed' ? 'selected' : '' }}>Departed</option>
                            <option value="arrived" {{ old('status', $flight->status) == 'arrived' ? 'selected' : '' }}>Arrived</option>
                            <option value="cancelled" {{ old('status', $flight->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes">{{ old('notes', $flight->notes) }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <form method="POST" action="{{ route('admin.flights.destroy', $flight) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this flight?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Flight</button>
                        </form>
                        <div class="btn-group">
                            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Flight</button>
                        </div>
                    </div>
                </form>
            </div>
@endsection
