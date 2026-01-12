@extends('layouts.app')

@section('title', 'Flight Details')

@section('content')
<div style="max-width:900px; margin:20px auto; padding:12px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <div>
            <h1 style="font-size:22px; margin:0;">Flight: {{ $flight->flight_number }}</h1>
            <p style="color:#6b7280; margin:6px 0 0 0;">Details and requests for the flight</p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" style="background:#06b6d4; color:#fff; padding:8px 14px; border-radius:8px; text-decoration:none;">Edit</a>
            <form method="POST" action="{{ route('flight-operations-manager.flights.destroy', $flight) }}" onsubmit="return confirm('Delete flight {{ $flight->flight_number }} ? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:#ef4444; color:#fff; padding:8px 14px; border-radius:8px; border:none; font-weight:600;">Delete</button>
            </form>
            <a href="{{ route('flight-operations-manager.flights.index') }}" style="background:#fff; border:1px solid #ddd; color:#333; padding:8px 14px; border-radius:8px; text-decoration:none;">Back</a>
        </div>
    </div>

    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); padding:18px;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div>
                <h3 style="margin:0 0 8px 0;">Route</h3>
                <p style="color:#4b5563; margin:0 0 12px 0;">{{ $flight->origin }} → {{ $flight->destination }}</p>

                <h3 style="margin:0 0 8px 0;">Departure</h3>
                <p style="color:#4b5563; margin:0 0 12px 0;">{{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }}</p>

                <h3 style="margin:0 0 8px 0;">Arrival</h3>
                <p style="color:#4b5563; margin:0 0 12px 0;">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('M d, Y H:i') }}</p>
            </div>

            <div>
                <h3 style="margin:0 0 8px 0;">Airline</h3>
                <p style="color:#4b5563; margin:0 0 12px 0;">{{ $flight->airline }}</p>

                <h3 style="margin:0 0 8px 0;">Status</h3>
                <p style="color:#4b5563; margin:0 0 12px 0;">{{ ucfirst($flight->status) }}</p>
            </div>
        </div>

        <div style="margin-top:18px;">
            <h4 style="margin:0 0 8px 0;">Catering Requests ({{ $flight->requests->count() }})</h4>
            @if($flight->requests->count() > 0)
                <ul style="margin-top:8px; color:#4b5563;">
                    @foreach($flight->requests as $req)
                        <li>#{{ $req->id }} — {{ $req->requester->name ?? 'Unknown' }} — {{ ucfirst($req->status) }}</li>
                    @endforeach
                </ul>
            @else
                <p style="color:#9ca3af; margin:8px 0 0 0;">No requests for this flight.</p>
            @endif
        </div>
    </div>
</div>
@endsection
