@extends('layouts.app')

@section('title', 'Edit Flight')

@section('content')
<div style="max-width:900px; margin:20px auto; padding:12px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <div>
            <h1 style="font-size:22px; margin:0;">Edit Flight: {{ $flight->flight_number }}</h1>
            <p style="color:#6b7280; margin:6px 0 0 0;">Update flight details</p>
        </div>
        <a href="{{ route('flight-operations-manager.flights.index') }}" style="background:#fff; border:1px solid #ddd; color:#333; padding:8px 14px; border-radius:8px; text-decoration:none;">Back to list</a>
    </div>

    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); padding:18px;">
        <form method="POST" action="{{ route('flight-operations-manager.flights.update', $flight) }}">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label style="font-weight:600; font-size:13px;">Flight Number</label>
                    <input name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
                <div>
                    <label style="font-weight:600; font-size:13px;">Airline</label>
                    <input name="airline" value="{{ old('airline', $flight->airline) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label style="font-weight:600; font-size:13px;">Origin</label>
                    <input name="origin" value="{{ old('origin', $flight->origin) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
                <div>
                    <label style="font-weight:600; font-size:13px;">Destination</label>
                    <input name="destination" value="{{ old('destination', $flight->destination) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label style="font-weight:600; font-size:13px;">Departure Time</label>
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time', \Carbon\Carbon::parse($flight->departure_time)->format('Y-m-d\TH:i')) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
                <div>
                    <label style="font-weight:600; font-size:13px;">Arrival Time</label>
                    <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time', \Carbon\Carbon::parse($flight->arrival_time)->format('Y-m-d\TH:i')) }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px;" />
                </div>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:14px;">
                <input type="hidden" name="status" value="{{ old('status', $flight->status) }}" />
                <button type="submit" style="background:#06b6d4; color:#fff; padding:10px 16px; border-radius:8px; border:none; font-weight:600;">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
