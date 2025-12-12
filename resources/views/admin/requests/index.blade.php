@extends('layouts.app')

@section('page-title', 'All Requests')
@section('page-description', 'Manage flight catering requests')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-sm { padding: 6px 12px; font-size: 13px; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #dc2626; color: white; }
    .filters { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #475569; }
    .filter-group input, .filter-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #dc2626; }
    .pagination { display: flex; gap: 8px; justify-content: center; padding: 20px; }
    .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; }
    .pagination .active { background: #0b1a68; color: white; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .actions { display: flex; gap: 8px; }
</style>

@if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="filters">
                <form method="GET" action="{{ route('admin.requests.index') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Search Requester</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name...">
                        </div>
                        <div class="filter-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Flight</label>
                            <select name="flight_id">
                                <option value="">All Flights</option>
                                @foreach($flights as $flight)
                                    <option value="{{ $flight->id }}" {{ request('flight_id') == $flight->id ? 'selected' : '' }}>{{ $flight->flight_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                @if($requests->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 200px;">Flight & Route</th>
                                <th>Requester & Role</th>
                                <th>Items & Products</th>
                                <th>Date & Time</th>
                                <th style="width: 150px;">Status & Stage</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: #667eea; font-size: 15px;">#{{ $req->id }}</div>
                                        @php
                                            $requestType = 'Product';
                                            if ($req->items->first()?->meal_type) {
                                                $requestType = 'Meal';
                                            }
                                        @endphp
                                        <div style="font-size: 10px; background: {{ $requestType === 'Meal' ? '#fef3c7' : '#dbeafe' }}; color: {{ $requestType === 'Meal' ? '#92400e' : '#1e40af' }}; padding: 2px 6px; border-radius: 4px; margin-top: 4px; text-align: center; font-weight: 600;">
                                            {{ $requestType }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1a202c; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                            <svg style="width: 14px; height: 14px; color: #667eea;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            {{ $req->flight->flight_number }}
                                        </div>
                                        <div style="color: #6b7280; font-size: 12px; display: flex; align-items: center; gap: 4px; margin-bottom: 3px;">
                                            <span style="font-weight: 600;">{{ $req->flight->origin }}</span>
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                            <span style="font-weight: 600;">{{ $req->flight->destination }}</span>
                                        </div>
                                        <div style="font-size: 11px; color: #9ca3af;">
                                            📅 {{ \Carbon\Carbon::parse($req->flight->departure_time)->format('M d, H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #1a202c; font-size: 14px; margin-bottom: 3px;">{{ $req->requester->name }}</div>
                                        <div style="font-size: 11px; background: #f3f4f6; color: #4b5563; padding: 2px 8px; border-radius: 4px; display: inline-block; font-weight: 600;">
                                            {{ $req->requester->roles->pluck('name')->first() ?? 'Staff' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1a202c; font-size: 15px; margin-bottom: 6px;">
                                            {{ $req->items->count() }} Products ({{ $req->getTotalItemsCount() }} qty)
                                        </div>
                                        <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">
                                            @php
                                                $topProducts = $req->items->take(2);
                                                $remaining = $req->items->count() - 2;
                                            @endphp
                                            @foreach($topProducts as $item)
                                                <div style="background: #eff6ff; padding: 3px 8px; border-radius: 4px; margin-bottom: 2px; border-left: 2px solid #3b82f6;">
                                                    <span style="font-weight: 600; color: #1e40af;">{{ $item->product->name }}</span>
                                                    <span style="color: #6b7280;">× {{ $item->quantity_requested }}</span>
                                                    @if($item->meal_type)
                                                        <span style="background: #fef3c7; color: #92400e; padding: 1px 4px; border-radius: 3px; font-size: 9px; margin-left: 4px; font-weight: 600;">
                                                            🍽️ {{ ucfirst($item->meal_type) }}
                                                        </span>
                                                    @endif
                                                    @if($item->is_scheduled)
                                                        <span style="background: #d1fae5; color: #065f46; padding: 1px 4px; border-radius: 3px; font-size: 9px; margin-left: 4px; font-weight: 600;">
                                                            📅 {{ $item->scheduled_at ? \Carbon\Carbon::parse($item->scheduled_at)->format('M d, H:i') : 'Scheduled' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($remaining > 0)
                                                <div style="color: #9ca3af; font-weight: 600; font-size: 10px; margin-top: 3px;">
                                                    +{{ $remaining }} more...
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #1a202c; font-size: 13px; margin-bottom: 3px;">
                                            {{ $req->requested_date->format('d M Y') }}
                                        </div>
                                        <div style="font-size: 11px; color: #6b7280;">
                                            🕒 {{ $req->created_at->format('H:i') }}
                                        </div>
                                        <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">
                                            {{ $req->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: {{ $req->getStatusBackground() }}; color: {{ $req->getStatusColor() }}; display: block; margin-bottom: 6px; text-align: center;">
                                            {{ str_replace('_', ' ', ucwords($req->status)) }}
                                        </span>
                                        @php
                                            $stages = [
                                                'pending_inventory' => ['icon' => '📋', 'text' => 'Inventory Review', 'color' => '#f59e0b'],
                                                'pending_supervisor' => ['icon' => '👨‍💼', 'text' => 'Supervisor Approval', 'color' => '#8b5cf6'],
                                                'supervisor_approved' => ['icon' => '✅', 'text' => 'Approved', 'color' => '#10b981'],
                                                'sent_to_security' => ['icon' => '🔒', 'text' => 'Security Check', 'color' => '#3b82f6'],
                                                'security_approved' => ['icon' => '✅', 'text' => 'Authenticated', 'color' => '#10b981'],
                                                'catering_approved' => ['icon' => '🍽️', 'text' => 'Ready for Pickup', 'color' => '#10b981'],
                                                'ready_for_dispatch' => ['icon' => '📦', 'text' => 'Ready to Dispatch', 'color' => '#6366f1'],
                                                'dispatched' => ['icon' => '🚚', 'text' => 'Dispatched', 'color' => '#8b5cf6'],
                                                'loaded' => ['icon' => '✈️', 'text' => 'Loaded on Aircraft', 'color' => '#0ea5e9'],
                                                'delivered' => ['icon' => '✅', 'text' => 'Delivered', 'color' => '#10b981'],
                                            ];
                                            $stage = $stages[$req->status] ?? ['icon' => '❓', 'text' => 'Unknown', 'color' => '#6b7280'];
                                            
                                            // Determine who performed the current action
                                            $actionBy = null;
                                            if (in_array($req->status, ['supervisor_approved', 'security_approved', 'catering_approved']) && $req->approver) {
                                                $actionBy = $req->approver->name;
                                            } elseif ($req->status === 'catering_approved' && $req->cateringApprover) {
                                                $actionBy = $req->cateringApprover->name;
                                            } elseif (in_array($req->status, ['ready_for_dispatch', 'dispatched']) && $req->securityDispatcher) {
                                                $actionBy = $req->securityDispatcher->name;
                                            } elseif ($req->status === 'loaded' && $req->rampAgent) {
                                                $actionBy = $req->rampAgent->name;
                                            } elseif ($req->status === 'flight_received' && $req->flightPurser) {
                                                $actionBy = $req->flightPurser->name;
                                            } elseif (in_array($req->status, ['delivered', 'served']) && $req->cabinCrew) {
                                                $actionBy = $req->cabinCrew->name;
                                            }
                                        @endphp
                                        <div style="font-size: 10px; color: {{ $stage['color'] }}; font-weight: 600; text-align: center;">
                                            {{ $stage['icon'] }} {{ $stage['text'] }}
                                        </div>
                                        @if($actionBy)
                                        <div style="font-size: 9px; color: #6b7280; text-align: center; margin-top: 4px;">
                                            👤 {{ $actionBy }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm btn-secondary" style="font-size: 12px;">
                                                👁️ View Full Details
                                            </a>
                                            @if($req->isPending())
                                                <a href="{{ route('admin.requests.approve-form', $req) }}" class="btn btn-sm btn-primary" style="font-size: 12px;">✓ Approve</a>
                                                <form method="POST" action="{{ route('admin.requests.destroy', $req) }}" style="display: inline;" onsubmit="return confirm('Delete this request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size: 12px;">🗑️ Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination">{{ $requests->links() }}</div>
                @else
                    <div class="empty-state">
                        <h3 style="margin-bottom: 8px; color: #475569;">No requests found</h3>
                        <p>Create your first request for a flight.</p>
                    </div>
                @endif
            </div>
@endsection
