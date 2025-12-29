@extends('layouts.app')

@section('title', 'Flight Schedule')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>📅 Flight Schedule</h1>
        <a href="{{ route('flight-dispatcher.dashboard') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Filters --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <form method="GET" action="{{ route('flight-dispatcher.flights.schedule') }}">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px;font-size:14px">Date</label>
                    <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                </div>
                
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px;font-size:14px">Status</label>
                    <select name="status" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="boarding" {{ request('status') === 'boarding' ? 'selected' : '' }}>Boarding</option>
                        <option value="delayed" {{ request('status') === 'delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="departed" {{ request('status') === 'departed' ? 'selected' : '' }}>Departed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px;font-size:14px">Airline</label>
                    <select name="airline" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                        <option value="all">All Airlines</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline }}" {{ request('airline') === $airline ? 'selected' : '' }}>{{ $airline }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="display:flex;align-items:end">
                    <button type="submit" style="width:100%;padding:10px;background:#3b82f6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                        🔍 Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Flights Table --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Flight #</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Airline</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Route</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Departure</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Arrival</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Aircraft</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Capacity</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Status</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                        <tr style="border-bottom:1px solid #e5e7eb">
                            <td style="padding:12px;font-weight:600">{{ $flight->flight_number }}</td>
                            <td style="padding:12px">{{ $flight->airline }}</td>
                            <td style="padding:12px">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span>{{ $flight->origin }}</span>
                                    <span style="color:#6b7280">→</span>
                                    <span>{{ $flight->destination }}</span>
                                </div>
                            </td>
                            <td style="padding:12px">{{ $flight->departure_time->format('Y-m-d H:i') }}</td>
                            <td style="padding:12px">{{ $flight->arrival_time ? $flight->arrival_time->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td style="padding:12px">{{ $flight->aircraft_type ?? 'N/A' }}</td>
                            <td style="padding:12px">{{ $flight->passenger_capacity ?? 'N/A' }}</td>
                            <td style="padding:12px">
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
                            </td>
                            <td style="padding:12px">
                                <a href="{{ route('flight-dispatcher.flights.show', $flight) }}" style="color:#3b82f6;text-decoration:none;font-weight:500">
                                    👁️ View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:24px;text-align:center;color:#6b7280">No flights found for the selected filters</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px">
            {{ $flights->appends(request()->query())->links() }}
        </div>
    </div>

@endsection
