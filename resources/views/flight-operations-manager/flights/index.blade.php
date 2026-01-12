@extends('layouts.app')

@section('title', 'All Flights')

@section('content')
<div style="max-width:1200px; margin:20px auto; padding:12px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
        <div>
            <h1 style="font-size:22px; margin:0;">All Flights</h1>
            <p style="color:#6b7280; margin:6px 0 0 0;">Listing of flights (paginated)</p>
        </div>
        <a href="{{ route('flight-operations-manager.flights.create') }}" style="background:#667eea; color:#fff; padding:8px 14px; border-radius:8px; text-decoration:none; font-weight:600;">Add Flight</a>
    </div>

    @if($flights->count() > 0)
    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;">
                    <th style="padding:12px 16px; text-align:left;">Flight #</th>
                    <th style="padding:12px 16px; text-align:left;">Airline</th>
                    <th style="padding:12px 16px; text-align:left;">Route</th>
                    <th style="padding:12px 16px; text-align:left;">Departure</th>
                    <th style="padding:12px 16px; text-align:left;">Status</th>
                    <th style="padding:12px 16px; text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flights as $flight)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:12px 16px;">{{ $flight->flight_number }}</td>
                    <td style="padding:12px 16px;">{{ $flight->airline }}</td>
                    <td style="padding:12px 16px;">{{ $flight->origin }} → {{ $flight->destination }}</td>
                    <td style="padding:12px 16px;">{{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }}</td>
                    <td style="padding:12px 16px;">{{ ucfirst($flight->status) }}</td>
                    <td style="padding:12px 16px; text-align:center;">
                        <a href="{{ route('flight-operations-manager.flights.show', $flight) }}" style="margin-right:8px; color:#667eea;">View</a>
                        <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" style="margin-right:8px; color:#06b6d4;">Edit</a>
                        <form method="POST" action="{{ route('flight-operations-manager.flights.destroy', $flight) }}" style="display:inline;" onsubmit="return confirm('Delete flight {{ $flight->flight_number }} ? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:transparent;border:none;color:#ef4444;font-weight:600;cursor:pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:12px;">
        {{ $flights->links() }}
    </div>

    @else
    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); padding:28px; text-align:center; color:#9ca3af;">
        <p style="margin:0;">No flights found.</p>
    </div>
    @endif
</div>
@endsection
