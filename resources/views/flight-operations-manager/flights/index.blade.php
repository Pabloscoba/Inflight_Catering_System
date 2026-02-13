@extends('layouts.app')

@section('title', 'All Flights - Flight Operations')

@section('content')
<style>
    .flight-ops-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }
    
    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .header-title h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .header-title p {
        margin: 0;
        opacity: 0.9;
        font-size: 15px;
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .btn-primary {
        background: white;
        color: #667eea;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border-left: 4px solid #667eea;
    }
    
    .stat-card h3 {
        font-size: 13px;
        color: #6b7280;
        margin: 0 0 8px 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card .value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .filters-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    
    .filters-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 16px;
        align-items: end;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    
    .form-control {
        padding: 10px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-filter {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-filter:hover {
        background: #5568d3;
    }
    
    .btn-reset {
        padding: 10px 20px;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-reset:hover {
        background: #e5e7eb;
    }
    
    .flights-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    
    .flights-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .flights-table thead {
        background: linear-gradient(to right, #f9fafb, #f3f4f6);
    }
    
    .flights-table th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .flights-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
    }
    
    .flights-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .flights-table td {
        padding: 16px;
        font-size: 14px;
        color: #1f2937;
    }
    
    .flight-number {
        font-weight: 700;
        color: #667eea;
        font-size: 15px;
    }
    
    .route-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .route-badge {
        padding: 4px 10px;
        background: #f3f4f6;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }
    
    .route-arrow {
        color: #667eea;
        font-weight: 700;
    }
    
    .datetime-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .date-text {
        font-weight: 600;
        color: #1f2937;
    }
    
    .time-text {
        font-size: 13px;
        color: #6b7280;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-scheduled {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-departed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-delayed {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-completed {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    .status-arrived {
        background: #dcfce7;
        color: #15803d;
    }
    
    .status-boarding {
        background: #fef3c7;
        color: #a16207;
    }
    
    .requests-badge {
        background: #ede9fe;
        color: #6b21a8;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .actions-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .btn-view {
        background: #eff6ff;
        color: #1e40af;
    }
    
    .btn-view:hover {
        background: #dbeafe;
    }
    
    .btn-edit {
        background: #ecfeff;
        color: #0e7490;
    }
    
    .btn-edit:hover {
        background: #cffafe;
    }
    
    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }
    
    .btn-delete:hover {
        background: #fee2e2;
    }
    
    .pagination-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        opacity: 0.3;
    }
    
    .empty-state h3 {
        font-size: 20px;
        color: #374151;
        margin: 0 0 8px 0;
    }
    
    .empty-state p {
        color: #6b7280;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .filters-form {
            grid-template-columns: 1fr;
        }
        
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="flight-ops-container">
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-content">
            <div class="header-title">
                <h1>
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3L18 7.586V21l-4-3-4 3-4-3V7.586L5 3z"></path>
                    </svg>
                    All Flights
                </h1>
                <p>Manage and monitor all flight operations</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('flight-operations-manager.flights.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Flight
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #667eea;">
            <h3>Total Flights</h3>
            <p class="value">{{ $flights->total() }}</p>
        </div>
        <div class="stat-card" style="border-left-color: #06b6d4;">
            <h3>Scheduled</h3>
            <p class="value">{{ $flights->where('status', 'scheduled')->count() }}</p>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h3>Departed</h3>
            <p class="value">{{ $flights->where('status', 'departed')->count() }}</p>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h3>With Requests</h3>
            <p class="value">{{ $flights->where('requests_count', '>', 0)->count() }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('flight-operations-manager.flights.index') }}" class="filters-form">
            <div class="form-group">
                <label for="q">Search Flights</label>
                <input 
                    type="text" 
                    id="q" 
                    name="q" 
                    class="form-control" 
                    placeholder="Flight number, airline, origin, destination..." 
                    value="{{ $q ?? '' }}"
                >
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Active Flights</option>
                    <option value="scheduled" {{ ($status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="boarding" {{ ($status ?? '') == 'boarding' ? 'selected' : '' }}>Boarding</option>
                    <option value="departed" {{ ($status ?? '') == 'departed' ? 'selected' : '' }}>Departed</option>
                    <option value="delayed" {{ ($status ?? '') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                    <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="arrived" {{ ($status ?? '') == 'arrived' ? 'selected' : '' }}>⚠️ Arrived (Hidden)</option>
                    <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>📦 Completed (Archived)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="per_page">Per Page</label>
                <select id="per_page" name="per_page" class="form-control">
                    <option value="10" {{ ($perPage ?? 20) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ ($perPage ?? 20) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ ($perPage ?? 20) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($perPage ?? 20) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-filter">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display: inline; vertical-align: middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
                <a href="{{ route('flight-operations-manager.flights.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Flights Table -->
    @if($flights->count() > 0)
    <div class="flights-table-container">
        <table class="flights-table">
            <thead>
                <tr>
                    <th>FLIGHT</th>
                    <th>AIRLINE</th>
                    <th>ROUTE</th>
                    <th>DEPARTURE</th>
                    <th>ARRIVAL</th>
                    <th>STATUS</th>
                    <th>REQUESTS</th>
                    <th style="text-align: center;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flights as $flight)
                <tr>
                    <td>
                        <span class="flight-number">{{ $flight->flight_number }}</span>
                    </td>
                    <td>
                        <strong>{{ $flight->airline }}</strong>
                    </td>
                    <td>
                        <div class="route-info">
                            <span class="route-badge">{{ $flight->origin }}</span>
                            <span class="route-arrow">→</span>
                            <span class="route-badge">{{ $flight->destination }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="datetime-info">
                            <span class="date-text">{{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y') }}</span>
                            <span class="time-text">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</span>
                        </div>
                    </td>
                    <td>
                        @if($flight->arrival_time)
                        <div class="datetime-info">
                            <span class="date-text">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('M d, Y') }}</span>
                            <span class="time-text">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</span>
                        </div>
                        @else
                        <span style="color: #9ca3af;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $flight->status }}">
                            {{ ucfirst($flight->status) }}
                        </span>
                    </td>
                    <td>
                        @if($flight->requests_count > 0)
                        <span class="requests-badge">{{ $flight->requests_count }} {{ Str::plural('request', $flight->requests_count) }}</span>
                        @else
                        <span style="color: #9ca3af; font-size: 13px;">No requests</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions-group" style="justify-content: center;">
                            <a href="{{ route('flight-operations-manager.flights.show', $flight) }}" class="btn-action btn-view">View</a>
                            <a href="{{ route('flight-operations-manager.flights.edit', $flight) }}" class="btn-action btn-edit">Edit</a>
                            <form method="POST" action="{{ route('flight-operations-manager.flights.destroy', $flight) }}" style="display:inline;" class="delete-form" data-flight="{{ $flight->flight_number }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-action btn-delete" onclick="confirmDelete(this)">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-section">
        <div style="color: #6b7280; font-size: 14px;">
            Showing <strong>{{ $flights->firstItem() }}</strong> to <strong>{{ $flights->lastItem() }}</strong> of <strong>{{ $flights->total() }}</strong> flights
        </div>
        <div>
            {{ $flights->links() }}
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3>No Flights Found</h3>
        <p>{{ $q || $status ? 'Try adjusting your filters' : 'Get started by adding your first flight' }}</p>
        @if(!$q && !$status)
        <a href="{{ route('flight-operations-manager.flights.create') }}" class="btn-primary" style="margin-top: 20px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Your First Flight
        </a>
        @endif
    </div>
    @endif
</div>

<script>
// Delete confirmation with better UX
function confirmDelete(button) {
    const form = button.closest('form');
    const flightNumber = form.dataset.flight;
    
    // Create custom confirmation dialog
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.2s;
    `;
    
    const dialog = document.createElement('div');
    dialog.style.cssText = `
        background: white;
        border-radius: 16px;
        padding: 32px;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s;
    `;
    
    dialog.innerHTML = `
        <div style="text-align: center;">
            <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #1f2937; margin: 0 0 12px 0;">Delete Flight ${flightNumber}?</h3>
            <p style="color: #6b7280; margin: 0 0 24px 0; line-height: 1.6;">This action cannot be undone. All associated data will be permanently deleted.</p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button onclick="this.closest('.confirm-overlay').remove()" style="
                    padding: 12px 24px;
                    background: #f3f4f6;
                    color: #374151;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                " onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="document.querySelector('.delete-form[data-flight=&quot;${flightNumber}&quot;]').submit()" style="
                    padding: 12px 24px;
                    background: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                " onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    Delete Flight
                </button>
            </div>
        </div>
    `;
    
    overlay.className = 'confirm-overlay';
    overlay.appendChild(dialog);
    document.body.appendChild(overlay);
    
    // Close on overlay click
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.remove();
        }
    });
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
@endsection
