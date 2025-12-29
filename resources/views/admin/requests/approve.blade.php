@extends('layouts.app')

@section('page-title', 'Approve Request #' . $request->id)
@section('page-description', 'Review and approve or reject product request')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { font-size: 28px; color: #1e293b; }
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-danger { background: #dc2626; color: white; }
    
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px; }
    .info-item label { display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 4px; }
    .info-item .value { color: #1e293b; font-size: 15px; font-weight: 500; }
    
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; font-size: 14px; }
    .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
    .form-group textarea { resize: vertical; min-height: 100px; font-family: inherit; }
    .error { color: #dc2626; font-size: 13px; margin-top: 4px; }
    
    .form-actions { display: flex; gap: 12px; margin-top: 30px; }
    .stock-warning { color: #dc2626; font-size: 13px; margin-top: 4px; font-weight: 500; }
    .stock-ok { color: #059669; font-size: 13px; margin-top: 4px; }
    .divider { height: 1px; background: #e2e8f0; margin: 30px 0; }
</style>

<div class="header">
    <h1>Approve Request #{{ $request->id }}</h1>
    <a href="{{ route('admin.requests.pending') }}" class="btn btn-secondary">← Back</a>
</div>

            <div class="card">
                <h3 style="margin-bottom: 20px; color: #1e293b;">Request Details</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Requester</label>
                        <div class="value">{{ $request->requester->name }}</div>
                    </div>
                    <div class="info-item">
                        <label>Request Date</label>
                        <div class="value">{{ $request->requested_date->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <label>Flight Number</label>
                        <div class="value">{{ $request->flight->flight_number }}</div>
                    </div>
                    <div class="info-item">
                        <label>Route</label>
                        <div class="value">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                    </div>
                    <div class="info-item">
                        <label>Departure Time</label>
                        <div class="value">{{ $request->flight->departure_time->format('d M Y H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <label>Aircraft</label>
                        <div class="value">{{ $request->flight->aircraft_type }}</div>
                    </div>
                </div>

                @if($request->notes)
                    <div class="info-item">
                        <label>Request Notes</label>
                        <div class="value">{{ $request->notes }}</div>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.requests.approve', $request) }}">
                @csrf
                <div class="card">
                    <h3 style="margin-bottom: 20px; color: #1e293b;">Requested Items - Review & Approve Quantities</h3>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Requested Qty</th>
                                <th>Available Stock</th>
                                <th>Approve Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request->items as $item)
                                <tr>
                                    <td><strong>{{ $item->product->name }}</strong></td>
                                    <td>{{ $item->product->category->name }}</td>
                                    <td>{{ $item->quantity_requested }}</td>
                                    <td>
                                        <strong style="color: {{ $item->product->quantity_in_stock >= $item->quantity_requested ? '#059669' : '#dc2626' }};">
                                            {{ $item->product->quantity_in_stock }}
                                        </strong>
                                    </td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="items[{{ $item->id }}][quantity_approved]" 
                                            min="0" 
                                            max="{{ $item->product->quantity_in_stock }}"
                                            value="{{ min($item->quantity_requested, $item->product->quantity_in_stock) }}"
                                            style="width: 120px; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;"
                                            onchange="validateStock(this, {{ $item->product->quantity_in_stock }}, {{ $item->quantity_requested }})"
                                        >
                                        @if($item->product->quantity_in_stock < $item->quantity_requested)
                                            <div class="stock-warning">⚠ Insufficient stock (short by {{ $item->quantity_requested - $item->product->quantity_in_stock }})</div>
                                        @else
                                            <div class="stock-ok">✓ Stock available</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Approve this request? Stock will be automatically issued.');">✓ Approve Request</button>
                    <a href="{{ route('admin.requests.pending') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

            <div class="divider"></div>

            <form method="POST" action="{{ route('admin.requests.reject', $request) }}">
                @csrf
                <div class="card">
                    <h3 style="margin-bottom: 20px; color: #dc2626;">Reject Request</h3>
                    
                    <div class="form-group">
                        <label>Rejection Reason *</label>
                        <textarea name="rejection_reason" required placeholder="Explain why this request is being rejected..."></textarea>
                        @error('rejection_reason')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this request? This cannot be undone.');">✗ Reject Request</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id + '-submenu');
            submenu.classList.toggle('active');
        }

        function validateStock(input, availableStock, requestedQty) {
            const value = parseInt(input.value);
            
            if (value < 0) {
                input.value = 0;
                return;
            }
            
            if (value > availableStock) {
                if (typeof Toast !== 'undefined') {
                    Toast.error('Approved quantity cannot exceed available stock (' + availableStock + ')');
                }
                input.value = availableStock;
            }
        }
    </script>
@endsection
