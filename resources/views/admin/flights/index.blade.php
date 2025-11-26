@extends('layouts.app')

@section('page-title', auth()->user()->hasRole('Admin') ? 'Flights Management' : 'Flight Schedule')
@section('page-description', auth()->user()->hasRole('Admin') ? 'Manage and track all flight schedules' : 'View all flight schedules')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { font-size: 28px; color: #1e293b; }
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-danger { background: #dc2626; color: white; }
    .btn-danger:hover { background: #b91c1c; }
    
    /* Filters */
    .filters { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #475569; }
    .filter-group input, .filter-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
    
    /* Table */
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    
    /* Badges */
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    
    /* Pagination */
    .pagination { display: flex; gap: 8px; justify-content: center; padding: 20px; }
    .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; }
    .pagination .active { background: #0b1a68; color: white; border-color: #0b1a68; }
    
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .actions { display: flex; gap: 8px; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
</style>

<div class="header">
    <h1 style="font-size: 30px; color: white;">{{ auth()->user()->hasRole('Admin') ? 'Flights Management' : 'Flight Schedule' }}</h1>
    @can('manage flights')
    <a href="{{ route('admin.flights.create') }}" class="btn btn-primary">+ Add Flight</a>
    @endcan
</div>

<!-- Filters -->
                <div class="filters">
                <form method="GET" action="{{ request()->routeIs('flights.schedule') ? route('flights.schedule') : route('admin.flights.index') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Flight number, airline, route...">
                        </div>
                        <div class="filter-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">All Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="boarding" {{ request('status') == 'boarding' ? 'selected' : '' }}>Boarding</option>
                                <option value="departed" {{ request('status') == 'departed' ? 'selected' : '' }}>Departed</option>
                                <option value="arrived" {{ request('status') == 'arrived' ? 'selected' : '' }}>Arrived</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="filter-group">
                            <label>To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="card">
                @if($flights->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Flight Number</th>
                                <th>Airline</th>
                                <th>Route</th>
                                <th>Departure</th>
                                <th>Status</th>
                                <th>Capacity</th>
                                @can('manage flights')
                                <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($flights as $flight)
                                <tr>
                                    <td><strong>{{ $flight->flight_number }}</strong></td>
                                    <td>{{ $flight->airline }}</td>
                                    <td>{{ $flight->origin }} → {{ $flight->destination }}</td>
                                    <td>{{ $flight->departure_time->format('d M Y, H:i') }}</td>
                                    <td>
                                        <span class="badge" style="background: {{ $flight->getStatusBackground() }}; color: {{ $flight->getStatusColor() }};">
                                            {{ ucfirst($flight->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $flight->passenger_capacity ?? '-' }}</td>
                                    @can('manage flights')
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.flights.edit', $flight) }}" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="POST" action="{{ route('admin.flights.destroy', $flight) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this flight?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="pagination">
                        {{ $flights->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <svg width="64" height="64" fill="#cbd5e1" viewBox="0 0 24 24" style="margin: 0 auto 20px;">
                            <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                        </svg>
                        <h3 style="margin-bottom: 8px; color: #475569;">No flights found</h3>
                        <p>Start by adding your first flight.</p>
                    </div>
                @endif
            </div>
@endsection
