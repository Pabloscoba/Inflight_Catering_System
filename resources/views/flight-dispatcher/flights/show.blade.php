@extends('layouts.app')

@section('title', 'Flight Details')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>✈️ Flight {{ $flight->flight_number }}</h1>
        <div style="display:flex;gap:10px">
            <a href="{{ route('flight-dispatcher.flights.schedule') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ← Back to Schedule
            </a>
        </div>
    </div>

    {{-- Flight Information --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Flight Information</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Flight Number</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->flight_number }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Airline</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->airline }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Origin</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->origin }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Destination</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->destination }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Departure Time</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->departure_time->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Arrival Time</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->arrival_time ? $flight->arrival_time->format('Y-m-d H:i') : 'N/A' }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Aircraft Type</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->aircraft_type ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Passenger Capacity</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $flight->passenger_capacity ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Status</p>
                <p style="font-weight:600;margin:4px 0 0">
                    @if($flight->status === 'scheduled')
                        <span style="padding:4px 12px;border-radius:12px;background:#dbeafe;color:#1e40af;font-size:12px">Scheduled</span>
                    @elseif($flight->status === 'boarding')
                        <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Boarding</span>
                    @elseif($flight->status === 'delayed')
                        <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Delayed</span>
                    @elseif($flight->status === 'departed')
                        <span style="padding:4px 12px;border-radius:12px;background:#ede9fe;color:#5b21b6;font-size:12px">Departed</span>
                    @else
                        <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">{{ ucfirst($flight->status) }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Update Flight Status Form --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Update Flight Status</h3>
        <form method="POST" action="{{ route('flight-dispatcher.flights.update-status', $flight) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:16px;align-items:end">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Status</label>
                    <select name="status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                        <option value="scheduled" {{ $flight->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="boarding" {{ $flight->status === 'boarding' ? 'selected' : '' }}>Boarding</option>
                        <option value="delayed" {{ $flight->status === 'delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="departed" {{ $flight->status === 'departed' ? 'selected' : '' }}>Departed</option>
                        <option value="cancelled" {{ $flight->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Reason (optional)</label>
                    <input type="text" name="reason" placeholder="Reason for status change" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                </div>
                <button type="submit" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    {{-- Update Flight Times Form --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Update Estimated Times</h3>
        <form method="POST" action="{{ route('flight-dispatcher.flights.update-times', $flight) }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:16px;align-items:end">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Departure Time</label>
                    <input type="datetime-local" name="departure_time" value="{{ $flight->departure_time->format('Y-m-d\TH:i') }}" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Arrival Time</label>
                    <input type="datetime-local" name="arrival_time" value="{{ $flight->arrival_time ? $flight->arrival_time->format('Y-m-d\TH:i') : '' }}" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Reason (optional)</label>
                    <input type="text" name="reason" placeholder="Reason for time change" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                </div>
                <button type="submit" style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Update Times
                </button>
            </div>
        </form>
    </div>

    {{-- Dispatch Record (if exists) --}}
    @if($dispatch)
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Dispatch Record</h3>
        <a href="{{ route('flight-dispatcher.dispatches.show', $dispatch) }}" style="padding:10px 16px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-weight:600;display:inline-block">
            View Dispatch Record
        </a>
    </div>
    @endif

    {{-- Requests for this flight --}}
    @if($flight->requests->count() > 0)
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Catering Requests</h3>
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($flight->requests as $request)
                <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px">
                    <div style="display:flex;justify-content:space-between;align-items:start">
                        <div>
                            <p style="font-weight:600;margin:0">Request #{{ $request->id }}</p>
                            <p style="font-size:14px;color:#6b7280;margin:4px 0 0">{{ $request->items->count() }} items • Requester: {{ $request->requester->name }}</p>
                        </div>
                        <span style="padding:4px 12px;border-radius:12px;background:#dbeafe;color:#1e40af;font-size:12px">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Status Update History --}}
    @if($statusUpdates->count() > 0)
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Status Update History</h3>
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($statusUpdates as $update)
                <div style="border-left:4px solid #3b82f6;padding-left:16px">
                    <p style="font-weight:600;margin:0">{{ $update->old_status }} → {{ $update->new_status }}</p>
                    <p style="font-size:14px;color:#6b7280;margin:4px 0">
                        By {{ $update->updatedBy->name }} • {{ $update->created_at->format('Y-m-d H:i') }}
                    </p>
                    @if($update->reason)
                        <p style="font-size:14px;margin:8px 0 0;padding:8px;background:#f9fafb;border-radius:6px">{{ $update->reason }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

@endsection
