@extends('layouts.app')

@section('page-title', 'Request #' . $request->id)
@section('page-description', 'View detailed request information')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { font-size: 28px; color: #1e293b; }
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #dc2626; color: white; }
    .btn-sm { padding: 8px 16px; font-size: 14px; }
    
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px; }
    .badge { padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; display: inline-block; }
    
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 20px; }
    .info-item { padding: 16px; background: #f8fafc; border-radius: 8px; }
    .info-item label { display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px; text-transform: uppercase; }
    .info-item .value { color: #1e293b; font-size: 16px; font-weight: 500; }
    .info-item .value small { display: block; color: #64748b; font-size: 13px; font-weight: 400; margin-top: 4px; }
    
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    
    .approval-info { background: #f0fdf4; border-left: 4px solid #22c55e; padding: 16px; border-radius: 8px; margin-top: 20px; }
    .rejection-info { background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-top: 20px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .info-row:last-child { margin-bottom: 0; }
    .info-row label { color: #64748b; font-weight: 500; }
    .info-row .value { color: #1e293b; font-weight: 600; }
    
    .actions-bar { display: flex; gap: 12px; margin-top: 30px; }
    .divider { height: 1px; background: #e2e8f0; margin: 30px 0; }
</style>

<div class="header">
    <div>
        <h1>Request #{{ $request->id }}</h1>
        <span class="badge" style="background: {{ $request->getStatusBackground() }}; color: {{ $request->getStatusColor() }}; margin-top: 8px;">{{ ucfirst($request->status) }}</span>
    </div>
    <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">← Back to Requests</a>
</div>

<div class="card">
                <h3 style="margin-bottom: 20px; color: #1e293b;">Request Information</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Requester</label>
                        <div class="value">
                            {{ $request->requester->name }}
                            <small>{{ $request->requester->email }}</small>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Request Date</label>
                        <div class="value">
                            {{ $request->requested_date->format('d M Y') }}
                            <small>{{ $request->requested_date->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                @if($request->notes)
                    <div style="margin-top: 20px;">
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px; text-transform: uppercase;">Notes</label>
                        <div style="padding: 14px; background: #f8fafc; border-radius: 8px; color: #334155;">{{ $request->notes }}</div>
                    </div>
                @endif
            </div>

            <div class="card">
                <h3 style="margin-bottom: 20px; color: #1e293b;">Flight Details</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Flight Number</label>
                        <div class="value">{{ $request->flight->flight_number }}</div>
                    </div>
                    <div class="info-item">
                        <label>Airline</label>
                        <div class="value">{{ $request->flight->airline }}</div>
                    </div>
                    <div class="info-item">
                        <label>Route</label>
                        <div class="value">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                    </div>
                    <div class="info-item">
                        <label>Departure Time</label>
                        <div class="value">
                            {{ $request->flight->departure_time->format('d M Y H:i') }}
                            <small>{{ $request->flight->departure_time->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Aircraft Type</label>
                        <div class="value">{{ $request->flight->aircraft_type }}</div>
                    </div>
                    <div class="info-item">
                        <label>Passenger Capacity</label>
                        <div class="value">{{ $request->flight->passenger_capacity }} passengers</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-bottom: 20px; color: #1e293b;">Requested Items</h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity Requested</th>
                            <th>Scheduled</th>
                            @if($request->isApproved() || $request->isRejected())
                                <th>Quantity Approved</th>
                                <th>Fulfillment</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request->items as $item)
                            <tr>
                                <td><strong>{{ $item->product->name }}</strong></td>
                                <td>{{ $item->product->category->name }}</td>
                                <td>{{ $item->quantity_requested }}</td>
                                <td>
                                    @if($item->is_scheduled)
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Scheduled
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                            Not Scheduled
                                        </span>
                                    @endif
                                </td>
                                @if($request->isApproved() || $request->isRejected())
                                    <td>
                                        <strong style="color: {{ $item->quantity_approved > 0 ? '#059669' : '#64748b' }};">
                                            {{ $item->quantity_approved ?? 0 }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($item->isFullyApproved())
                                            <span style="color: #059669; font-weight: 500;">✓ Fully Approved</span>
                                        @elseif($item->isPartiallyApproved())
                                            <span style="color: #f59e0b; font-weight: 500;">⚠ Partial ({{ $item->getApprovalPercentage() }}%)</span>
                                        @else
                                            <span style="color: #64748b;">✗ Not Approved</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 16px; padding: 14px; background: #f8fafc; border-radius: 8px;">
                    <strong style="color: #1e293b;">Total Items:</strong> {{ $request->items->count() }} products | 
                    <strong style="color: #1e293b;">Total Quantity Requested:</strong> {{ $request->getTotalItemsCount() }}
                    @if($request->isApproved())
                        | <strong style="color: #1e293b;">Total Quantity Approved:</strong> {{ $request->items->sum('quantity_approved') }}
                    @endif
                </div>
            </div>

            @if($request->isApproved())
                <div class="approval-info">
                    <h4 style="color: #166534; margin-bottom: 12px;">✓ Approval Information</h4>
                    <div class="info-row">
                        <label>Approved By:</label>
                        <span class="value">{{ $request->approver->name }}</span>
                    </div>
                    <div class="info-row">
                        <label>Approval Date:</label>
                        <span class="value">{{ $request->approved_date->format('d M Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <label>Stock Movement Reference:</label>
                        <span class="value">REQ-{{ $request->id }} / {{ $request->flight->flight_number }}</span>
                    </div>
                </div>
            @endif

            @if($request->isRejected())
                <div class="rejection-info">
                    <h4 style="color: #991b1b; margin-bottom: 12px;">✗ Rejection Information</h4>
                    <div class="info-row">
                        <label>Rejected By:</label>
                        <span class="value">{{ $request->approver->name ?? 'N/A' }}</span>
                    </div>
                    @if($request->rejection_reason)
                        <div style="margin-top: 12px;">
                            <label style="display: block; margin-bottom: 6px;">Reason:</label>
                            <div style="padding: 10px; background: white; border-radius: 6px; color: #1e293b;">{{ $request->rejection_reason }}</div>
                        </div>
                    @endif
                </div>
            @endif

            @if($request->isPending())
                <div class="divider"></div>
                <div class="actions-bar">
                    <a href="{{ route('admin.requests.approve-form', $request) }}" class="btn btn-primary">✓ Approve Request</a>
                    <form method="POST" action="{{ route('admin.requests.destroy', $request) }}" style="display: inline;" onsubmit="return confirm('Delete this request? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Request</button>
                    </form>
                </div>
            @endif
@endsection
