@extends('layouts.app')

@section('title', 'Flight Operations Manager Dashboard')

@section('content')
<div style="padding:0 12px 12px; max-width:1400px; margin:20px auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h1 style="font-size:26px; font-weight:700; color:#1f2937; margin:0;">✈️ Flight Operations Dashboard</h1>
            <p style="color:#6b7280; font-size:14px; margin:6px 0 0 0;">Manage flight schedules and operations</p>
        </div>
        <a href="{{ route('flight-operations-manager.flights.create') }}" 
           style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px; box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:all 0.3s;">
            <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Flight
        </a>
    </div>

    <!-- Statistics Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:28px;">
        <!-- Total Flights -->
        <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:12px; padding:20px; color:white; box-shadow:0 4px 12px rgba(102,126,234,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="background:rgba(255,255,255,0.2); padding:10px; border-radius:10px;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div style="font-size:32px; font-weight:700; margin-bottom:4px;">{{ $totalFlights }}</div>
            <div style="font-size:14px; opacity:0.9;">Total Flights</div>
        </div>

        <!-- Scheduled Flights -->
        <div style="background:linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius:12px; padding:20px; color:white; box-shadow:0 4px 12px rgba(6,182,212,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="background:rgba(255,255,255,0.2); padding:10px; border-radius:10px;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div style="font-size:32px; font-weight:700; margin-bottom:4px;">{{ $scheduledFlights }}</div>
            <div style="font-size:14px; opacity:0.9;">Scheduled Flights</div>
        </div>

        <!-- Today's Flights -->
        <div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius:12px; padding:20px; color:white; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="background:rgba(255,255,255,0.2); padding:10px; border-radius:10px;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div style="font-size:32px; font-weight:700; margin-bottom:4px;">{{ $todayFlights }}</div>
            <div style="font-size:14px; opacity:0.9;">Today's Flights</div>
        </div>

        <!-- Flights with Requests -->
        <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius:12px; padding:20px; color:white; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="background:rgba(255,255,255,0.2); padding:10px; border-radius:10px;">
                    <svg style="width:24px; height:24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div style="font-size:32px; font-weight:700; margin-bottom:4px;">{{ $flightsWithRequests->count() }}</div>
            <div style="font-size:14px; opacity:0.9;">With Catering Requests</div>
        </div>
    </div>

    <!-- Upcoming Flights -->
    <div style="background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); overflow:hidden; margin-bottom:28px;">
        <div style="padding:20px 24px; border-bottom:2px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="font-size:18px; font-weight:700; color:#1a1a1a; margin:0;">📅 Upcoming Flights (Next 7 Days)</h3>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">{{ $upcomingFlights->count() }} flights scheduled</p>
            </div>
            <a href="{{ route('flight-operations-manager.flights.index') }}" 
               style="color:#667eea; font-size:13px; font-weight:600; text-decoration:none;">
                View All →
            </a>
        </div>
        
        @if($upcomingFlights->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Flight #</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Airline</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Route</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Departure</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Status</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Requests</th>
                        <th style="padding:12px 24px; text-align:center; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingFlights as $flight)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:14px 24px;">
                            <span style="font-weight:600; color:#1f2937;">{{ $flight->flight_number }}</span>
                        </td>
                        <td style="padding:14px 24px; color:#4b5563;">{{ $flight->airline }}</td>
                        <td style="padding:14px 24px;">
                            <span style="color:#4b5563;">{{ $flight->origin }} → {{ $flight->destination }}</span>
                        </td>
                        <td style="padding:14px 24px; color:#4b5563; font-size:13px;">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }}
                        </td>
                        <td style="padding:14px 24px;">
                            @if($flight->status === 'scheduled')
                                <span style="background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">Scheduled</span>
                            @elseif($flight->status === 'boarding')
                                <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">Boarding</span>
                            @elseif($flight->status === 'departed')
                                <span style="background:#d1fae5; color:#065f46; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">Departed</span>
                            @else
                                <span style="background:#f3f4f6; color:#4b5563; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">{{ ucfirst($flight->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:14px 24px;">
                            <span style="background:#f3f4f6; color:#4b5563; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">
                                {{ $flight->requests->count() }} request(s)
                            </span>
                        </td>
                        <td style="padding:14px 24px; text-align:center;">
                            <div style="display:flex; gap:8px; justify-content:center;">
                                <a href="{{ route('flight-operations-manager.flights.show', $flight) }}" 
                                   style="color:#667eea; font-size:13px; font-weight:600; text-decoration:none;">
                                    View
                                </a>
                                <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" 
                                   style="color:#06b6d4; font-size:13px; font-weight:600; text-decoration:none;">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding:40px; text-align:center; color:#9ca3af;">
            <svg style="width:48px; height:48px; margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <p style="font-size:14px; margin:0;">No upcoming flights scheduled for the next 7 days</p>
        </div>
        @endif
    </div>

    <!-- Recent Flights -->
    <div style="background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); overflow:hidden;">
        <div style="padding:20px 24px; border-bottom:2px solid #f3f4f6;">
            <h3 style="font-size:18px; font-weight:700; color:#1a1a1a; margin:0;">🕒 Recently Added Flights</h3>
            <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">Last 10 flights added to the system</p>
        </div>
        
        @if($recentFlights->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Flight #</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Route</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Departure</th>
                        <th style="padding:12px 24px; text-align:left; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Status</th>
                        <th style="padding:12px 24px; text-align:center; font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentFlights as $flight)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:14px 24px;">
                            <span style="font-weight:600; color:#1f2937;">{{ $flight->flight_number }}</span>
                        </td>
                        <td style="padding:14px 24px; color:#4b5563;">
                            {{ $flight->origin }} → {{ $flight->destination }}
                        </td>
                        <td style="padding:14px 24px; color:#4b5563; font-size:13px;">
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }}
                        </td>
                        <td style="padding:14px 24px;">
                            @if($flight->status === 'scheduled')
                                <span style="background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">Scheduled</span>
                            @else
                                <span style="background:#f3f4f6; color:#4b5563; padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600;">{{ ucfirst($flight->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:14px 24px; text-align:center;">
                            <div style="display:flex; gap:8px; justify-content:center;">
                                <a href="{{ route('flight-operations-manager.flights.show', $flight) }}" 
                                   style="color:#667eea; font-size:13px; font-weight:600; text-decoration:none;">
                                    View
                                </a>
                                <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" 
                                   style="color:#06b6d4; font-size:13px; font-weight:600; text-decoration:none;">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding:40px; text-align:center; color:#9ca3af;">
            <p style="font-size:14px; margin:0;">No flights in the system yet</p>
        </div>
        @endif
    </div>
</div>
@endsection
