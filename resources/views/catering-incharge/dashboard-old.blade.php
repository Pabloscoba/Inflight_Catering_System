@extends('layouts.app')

@section('title', 'Catering Incharge Dashboard')

@section('content')
<div class="content-header">
    <h1>Catering Incharge Dashboard</h1>
    <p>Manage catering operations and staff requests</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-details">
            <div class="stat-value">{{ $pendingRequests }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="stat-details">
            <div class="stat-value">{{ $approvedToday }}</div>
            <div class="stat-label">Approved Today</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div class="stat-details">
            <div class="stat-value">{{ $rejectedToday }}</div>
            <div class="stat-label">Rejected Today</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div class="stat-details">
            <div class="stat-value">{{ $cateringStaff }}</div>
            <div class="stat-label">Catering Staff</div>
        </div>
    </div>
</div>

<!-- Pending Staff Requests -->
<div class="card" style="margin-top: 30px;">
    <div class="card-header">
        <h3>Staff Requests Needing Approval</h3>
        <a href="{{ route('admin.requests.pending') }}" class="btn btn-link">View All →</a>
    </div>
    <div class="table-container">
        @if($pendingStaffRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Flight</th>
                    <th>Staff Member</th>
                    <th>Requested Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingStaffRequests as $request)
                <tr>
                    <td>#{{ $request->id }}</td>
                    <td>{{ $request->flight->flight_number }}</td>
                    <td>{{ $request->requester->name }}</td>
                    <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.requests.approve-form', $request) }}" class="btn btn-sm btn-success">Approve</a>
                        <form method="POST" action="{{ route('admin.requests.reject', $request) }}" style="display: inline;">
                            @csrf
                            <button class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; padding: 40px; color: #6c757d;">No pending requests</p>
        @endif
    </div>
</div>

<!-- Upcoming Flights -->
<div class="card" style="margin-top: 30px;">
    <div class="card-header">
        <h3>Upcoming Flights (Next 3 Days)</h3>
        <a href="{{ route('admin.flights.index') }}" class="btn btn-link">View All →</a>
    </div>
    <div class="table-container">
        @if($upcomingFlights->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Flight #</th>
                    <th>Route</th>
                    <th>Departure</th>
                    <th>Requests</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upcomingFlights as $flight)
                <tr>
                    <td>{{ $flight->flight_number }}</td>
                    <td>{{ $flight->origin }} → {{ $flight->destination }}</td>
                    <td>{{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }}</td>
                    <td><span class="badge badge-info">{{ $flight->requests->count() }}</span></td>
                    <td>
                        <a href="{{ route('admin.requests.create') }}?flight_id={{ $flight->id }}" class="btn btn-sm btn-primary">Create Request</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; padding: 40px; color: #6c757d;">No upcoming flights</p>
        @endif
    </div>
</div>
@endsection
