@extends('layouts.app')

@section('page-title', auth()->user()->hasRole('Admin') ? 'Flights Management' : 'Flight Schedule')
@section('page-description', auth()->user()->hasRole('Admin') ? 'Manage and track all flight schedules' : 'View all flight schedules')

@section('content')
@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <!-- Title is handled by @yield('page-title') in topbar -->
        </div>
        @can('manage flights')
            <a href="{{ route('admin.flights.create') }}" class="btn-atcl btn-atcl-primary">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Flight
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card-atcl" style="padding: 20px; margin-bottom: 24px;">
        <form method="GET"
            action="{{ request()->routeIs('flights.schedule') ? route('flights.schedule') : route('admin.flights.index') }}">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; align-items: end;">
                <div>
                    <label class="label-atcl">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-atcl"
                        placeholder="Flight #, airline, route...">
                </div>
                <div>
                    <label class="label-atcl">Status</label>
                    <select name="status" class="input-atcl">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="boarding" {{ request('status') == 'boarding' ? 'selected' : '' }}>Boarding</option>
                        <option value="departed" {{ request('status') == 'departed' ? 'selected' : '' }}>Departed</option>
                        <option value="arrived" {{ request('status') == 'arrived' ? 'selected' : '' }}>Arrived</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="label-atcl">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-atcl">
                </div>
                <div>
                    <label class="label-atcl">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-atcl">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-atcl btn-atcl-primary" style="flex: 1;">Apply</button>
                    <a href="{{ route('admin.flights.index') }}" class="btn-atcl btn-atcl-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card-atcl" style="padding: 0; overflow: hidden;">
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead {
                background: #f8fafc;
            }

            th {
                padding: 14px;
                text-align: left;
                font-weight: 600;
                color: #475569;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            td {
                padding: 14px;
                border-top: 1px solid #f1f5f9;
                color: #334155;
            }

            tr:hover {
                background: #f8fafc;
            }

            .badge {
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
            }

            .pagination {
                display: flex;
                gap: 8px;
                justify-content: center;
                padding: 20px;
            }

            .pagination a,
            .pagination span {
                padding: 8px 12px;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                text-decoration: none;
                color: #475569;
            }

            .pagination .active {
                background: #1e3a8a;
                color: white;
                border-color: #1e3a8a;
            }

            .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #64748b;
            }
        </style>
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
                                <span class="badge"
                                    style="background: {{ $flight->getStatusBackground() }}; color: {{ $flight->getStatusColor() }};">
                                    {{ ucfirst($flight->status) }}
                                </span>
                            </td>
                            <td>{{ $flight->passenger_capacity ?? '-' }}</td>
                            @can('manage flights')
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.flights.edit', $flight) }}" class="btn-atcl btn-atcl-secondary"
                                            style="height: 32px; padding: 0 12px; font-size: 12px;">Edit</a>
                                        <form method="POST" action="{{ route('admin.flights.destroy', $flight) }}"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this flight?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-atcl btn-atcl-danger"
                                                style="height: 32px; padding: 0 12px; font-size: 12px;">Delete</button>
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
                    <path
                        d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                </svg>
                <h3 style="margin-bottom: 8px; color: #475569;">No flights found</h3>
                <p>Start by adding your first flight.</p>
            </div>
        @endif
    </div>
@endsection