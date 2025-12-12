@extends('layouts.app')

@section('content')
<style>
    .served-container { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }
    .page-header { margin-bottom: 32px; }
    .page-header h1 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px; }
    .page-header p { color: #6b7280; font-size: 15px; }
    .flight-info { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 24px; margin-bottom: 32px; }
    .flight-info h2 { font-size: 24px; font-weight: 700; margin: 0 0 16px; }
    .flight-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .flight-detail { display: flex; flex-direction: column; }
    .flight-detail-label { font-size: 12px; opacity: 0.9; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .flight-detail-value { font-size: 18px; font-weight: 600; }
    .alert { padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: start; gap: 12px; }
    .alert-info { background: #dbeafe; border: 1px solid #3b82f6; color: #1e40af; }
    .alert-warning { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; }
    .alert svg { width: 24px; height: 24px; flex-shrink: 0; }
    .products-card { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px; }
    .products-card h3 { font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0 0 24px; }
    .products-table { width: 100%; border-collapse: collapse; }
    .products-table thead { background: #f9fafb; }
    .products-table th { padding: 16px; text-align: left; font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e5e7eb; }
    .products-table td { padding: 20px 16px; border-bottom: 1px solid #f3f4f6; }
    .product-name { font-size: 15px; font-weight: 600; color: #111827; margin-bottom: 4px; }
    .product-desc { font-size: 13px; color: #6b7280; }
    .quantity-badge { display: inline-block; padding: 6px 12px; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 14px; font-weight: 600; }
    .usage-input-group { display: flex; flex-direction: column; gap: 8px; max-width: 200px; }
    .usage-input-group label { font-size: 12px; font-weight: 600; color: #374151; }
    .usage-input { width: 100%; padding: 10px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; font-weight: 600; transition: all 0.2s; }
    .usage-input:focus { outline: none; border-color: #3b82f6; background: #eff6ff; }
    .usage-input.has-error { border-color: #ef4444; background: #fef2f2; }
    .input-helper { font-size: 11px; color: #6b7280; margin-top: 4px; }
    .input-error { font-size: 11px; color: #dc2626; margin-top: 4px; font-weight: 600; }
    .notes-textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 80px; font-family: inherit; }
    .notes-textarea:focus { outline: none; border-color: #3b82f6; background: #eff6ff; }
    .actions-bar { display: flex; justify-content: space-between; align-items: center; padding-top: 24px; border-top: 2px solid #e5e7eb; }
    .btn { padding: 14px 28px; border-radius: 10px; font-size: 15px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
    .btn-primary { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4); }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .btn-secondary { background: white; color: #6b7280; border: 2px solid #e5e7eb; }
    .btn-secondary:hover { background: #f9fafb; border-color: #d1d5db; }
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .summary-item { background: #f9fafb; padding: 16px; border-radius: 12px; border: 2px solid #e5e7eb; }
    .summary-item-label { font-size: 12px; color: #6b7280; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-item-value { font-size: 24px; font-weight: 700; color: #1a1a1a; }
</style>

<div class="served-container">
    <div class="page-header">
        <h1>📋 Mark Products as Served to Customers</h1>
        <p>Record actual usage for Request #{{ $request->id }}</p>
    </div>

    <!-- Flight Information -->
    <div class="flight-info">
        <h2>✈️ {{ $request->flight->flight_number }}</h2>
        <div class="flight-details">
            <div class="flight-detail">
                <div class="flight-detail-label">Route</div>
                <div class="flight-detail-value">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
            </div>
            <div class="flight-detail">
                <div class="flight-detail-label">Departure</div>
                <div class="flight-detail-value">{{ $request->flight->departure_time->format('M d, Y H:i') }}</div>
            </div>
            <div class="flight-detail">
                <div class="flight-detail-label">Capacity</div>
                <div class="flight-detail-value">{{ $request->flight->capacity }} passengers</div>
            </div>
            <div class="flight-detail">
                <div class="flight-detail-label">Status</div>
                <div class="flight-detail-value">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-warning">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <!-- Important Notice -->
    <div class="alert alert-info">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <div style="font-weight: 600; margin-bottom: 4px;">Important: Record Actual Usage</div>
            <div style="font-size: 14px;">Enter the exact quantity served to customers for each product. This helps track inventory accurately and identify any unused or damaged items.</div>
        </div>
    </div>

    <form action="{{ route('cabin-crew.served.submit', $request) }}" method="POST" id="servedForm">
        @csrf
        
        <!-- Products List -->
        <div class="products-card">
            <h3>🍽️ Products Usage Tracking</h3>
            
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Product</th>
                        <th style="width: 15%; text-align: center;">Approved</th>
                        <th style="width: 25%;">Actual Usage</th>
                        <th style="width: 20%;">Notes (Optional)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($request->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->name }}</div>
                            @if($item->product->description)
                            <div class="product-desc">{{ $item->product->description }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="quantity-badge">{{ $item->quantity_approved ?? $item->quantity_requested }}</span>
                        </td>
                        <td>
                            <div class="usage-input-group">
                                <label for="usage_{{ $item->id }}">Quantity Served *</label>
                                <input 
                                    type="number" 
                                    id="usage_{{ $item->id }}"
                                    name="items[{{ $item->id }}][quantity_used]" 
                                    class="usage-input"
                                    min="0"
                                    max="{{ $item->quantity_approved ?? $item->quantity_requested }}"
                                    value="{{ old('items.'.$item->id.'.quantity_used', $item->quantity_approved ?? $item->quantity_requested) }}"
                                    required
                                    data-max="{{ $item->quantity_approved ?? $item->quantity_requested }}"
                                    onchange="validateUsage(this)">
                                <div class="input-helper">Max: {{ $item->quantity_approved ?? $item->quantity_requested }}</div>
                                @error('items.'.$item->id.'.quantity_used')
                                <div class="input-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <input 
                                type="text" 
                                name="items[{{ $item->id }}][usage_notes]" 
                                placeholder="e.g., 2 damaged"
                                style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;"
                                value="{{ old('items.'.$item->id.'.usage_notes') }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="products-card">
            <h3>📊 Usage Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-item-label">Total Products</div>
                    <div class="summary-item-value">{{ $request->items->count() }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-item-label">Total Approved Qty</div>
                    <div class="summary-item-value" id="totalApproved">{{ $request->items->sum(function($item) { return $item->quantity_approved ?? $item->quantity_requested; }) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-item-label">Total Served Qty</div>
                    <div class="summary-item-value" id="totalServed">-</div>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">
                    General Notes / Comments (Optional)
                </label>
                <textarea 
                    name="general_notes" 
                    class="notes-textarea"
                    placeholder="Any general observations about the service, passenger feedback, or issues encountered...">{{ old('general_notes') }}</textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-bar">
            <a href="{{ route('cabin-crew.dashboard') }}" class="btn btn-secondary">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cancel
            </a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mark as Served to Customers
            </button>
        </div>
    </form>
</div>

<script>
function validateUsage(input) {
    const max = parseInt(input.getAttribute('data-max'));
    const value = parseInt(input.value);
    
    if (value > max) {
        input.classList.add('has-error');
        input.value = max;
    } else if (value < 0) {
        input.classList.add('has-error');
        input.value = 0;
    } else {
        input.classList.remove('has-error');
    }
    
    updateTotalServed();
}

function updateTotalServed() {
    let total = 0;
    document.querySelectorAll('.usage-input').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    document.getElementById('totalServed').textContent = total;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotalServed();
    
    // Add input event listeners
    document.querySelectorAll('.usage-input').forEach(input => {
        input.addEventListener('input', function() {
            validateUsage(this);
        });
    });
    
    // Form validation before submit
    document.getElementById('servedForm').addEventListener('submit', function(e) {
        let isValid = true;
        document.querySelectorAll('.usage-input').forEach(input => {
            const max = parseInt(input.getAttribute('data-max'));
            const value = parseInt(input.value);
            if (value > max || value < 0) {
                isValid = false;
                input.classList.add('has-error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please correct the highlighted fields before submitting.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').textContent = 'Processing...';
    });
});
</script>
@endsection
