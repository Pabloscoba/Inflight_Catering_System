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
    <div style="background:white; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.08); overflow:hidden;">
        <div style="padding:24px; border-bottom:2px solid #f3f4f6; background:linear-gradient(to right, #f9fafb, white);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="font-size:20px; font-weight:700; color:#1a1a1a; margin:0; display:flex; align-items:center; gap:10px;">
                        <span style="background:linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-size:24px;">🕒</span>
                        Recently Added Flights
                    </h3>
                    <p style="font-size:13px; color:#6b7280; margin:6px 0 0 0;">Latest flights added to your system - Active flights only</p>
                </div>
                <a href="{{ route('flight-operations-manager.flights.index') }}" 
                   style="background:#eff6ff; color:#1e40af; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all 0.2s;"
                   onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    View All Flights →
                </a>
            </div>
        </div>
        
        @if($recentFlights->count() > 0)
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:linear-gradient(to right, #f9fafb, #f3f4f6);">
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Flight</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Airline</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Route</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Departure</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Aircraft</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                        <th style="padding:14px 24px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Added</th>
                        <th style="padding:14px 24px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentFlights as $flight)
                    <tr style="border-bottom:1px solid #f3f4f6; transition:all 0.2s;" 
                        onmouseover="this.style.background='#f9fafb'" 
                        onmouseout="this.style.background='white'">
                        <td style="padding:16px 24px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:40px; height:40px; background:linear-gradient(135deg, #667eea, #764ba2); border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:14px;">
                                    ✈️
                                </div>
                                <div>
                                    <div style="font-weight:700; color:#1f2937; font-size:15px;">{{ $flight->flight_number }}</div>
                                    <div style="font-size:11px; color:#9ca3af;">ID: #{{ $flight->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:16px 24px;">
                            <div style="font-weight:600; color:#374151;">{{ $flight->airline }}</div>
                        </td>
                        <td style="padding:16px 24px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="padding:4px 10px; background:#f3f4f6; border-radius:6px; font-weight:600; font-size:12px; color:#1f2937;">{{ $flight->origin }}</span>
                                <svg width="16" height="16" fill="none" stroke="#667eea" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span style="padding:4px 10px; background:#f3f4f6; border-radius:6px; font-weight:600; font-size:12px; color:#1f2937;">{{ $flight->destination }}</span>
                            </div>
                        </td>
                        <td style="padding:16px 24px;">
                            <div style="font-weight:600; color:#1f2937; font-size:13px;">{{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y') }}</div>
                            <div style="font-size:12px; color:#6b7280; margin-top:2px;">🕐 {{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</div>
                        </td>
                        <td style="padding:16px 24px;">
                            @if($flight->aircraft_type)
                                <div style="font-size:12px; color:#4b5563; font-weight:500;">{{ $flight->aircraft_type }}</div>
                                @if($flight->passenger_capacity)
                                    <div style="font-size:11px; color:#9ca3af; margin-top:2px;">👥 {{ $flight->passenger_capacity }} seats</div>
                                @endif
                            @else
                                <span style="color:#9ca3af; font-size:12px;">—</span>
                            @endif
                        </td>
                        <td style="padding:16px 24px;">
                            @php
                                $statusColors = [
                                    'scheduled' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'boarding' => ['bg' => '#fef3c7', 'text' => '#a16207'],
                                    'departed' => ['bg' => '#f3e8ff', 'text' => '#7c3aed'],
                                    'delayed' => ['bg' => '#fed7aa', 'text' => '#c2410c'],
                                    'cancelled' => ['bg' => '#fee2e2', 'text' => '#dc2626'],
                                ];
                                $color = $statusColors[$flight->status] ?? ['bg' => '#f3f4f6', 'text' => '#4b5563'];
                            @endphp
                            <span style="background:{{ $color['bg'] }}; color:{{ $color['text'] }}; padding:6px 12px; border-radius:16px; font-size:11px; font-weight:700; display:inline-block; text-transform:uppercase; letter-spacing:0.5px;">
                                {{ ucfirst($flight->status) }}
                            </span>
                        </td>
                        <td style="padding:16px 24px;">
                            <div style="font-size:12px; color:#6b7280;">{{ $flight->created_at->diffForHumans() }}</div>
                            <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ $flight->created_at->format('M d, H:i') }}</div>
                        </td>
                        <td style="padding:16px 24px;">
                            <div style="display:flex; gap:8px; justify-content:center;">
                                <a href="{{ route('flight-operations-manager.flights.show', $flight) }}" 
                                   style="background:#eff6ff; color:#1e40af; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; text-decoration:none; transition:all 0.2s;"
                                   onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                    View
                                </a>
                                <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" 
                                   style="background:#ecfeff; color:#0e7490; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; text-decoration:none; transition:all 0.2s;"
                                   onmouseover="this.style.background='#cffafe'" onmouseout="this.style.background='#ecfeff'">
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
        <div style="padding:60px 40px; text-align:center;">
            <div style="width:80px; height:80px; background:#f3f4f6; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:36px;">
                ✈️
            </div>
            <h4 style="font-size:18px; font-weight:700; color:#374151; margin:0 0 8px 0;">No Active Flights</h4>
            <p style="font-size:14px; color:#6b7280; margin:0 0 20px 0;">Get started by adding your first flight to the system</p>
            <a href="{{ route('flight-operations-manager.flights.create') }}" 
               style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg, #667eea, #764ba2); color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px;">
                <svg style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Your First Flight
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
