@extends('layouts.app')

@section('title', 'Create Return')

@section('content')
<div style="max-width:900px;margin:0 auto;">
    <div style="margin-bottom:32px;">
        <a href="{{ route('cabin-crew.returns.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:#667eea;text-decoration:none;margin-bottom:16px;">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Returns
        </a>
        <h1 style="font-size:32px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">↩️ Return Items</h1>
        <p style="color:#718096;margin:0;">Select items to return from Flight {{ $request->flight->flight_number }}</p>
    </div>

    <!-- Flight Info -->
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:24px;margin-bottom:24px;color:white;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
            <div>
                <div style="font-size:13px;opacity:0.9;margin-bottom:4px;">Flight Number</div>
                <div style="font-size:20px;font-weight:700;">{{ $request->flight->flight_number }}</div>
            </div>
            <div>
                <div style="font-size:13px;opacity:0.9;margin-bottom:4px;">Route</div>
                <div style="font-size:18px;font-weight:600;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
            </div>
            <div>
                <div style="font-size:13px;opacity:0.9;margin-bottom:4px;">Departure</div>
                <div style="font-size:16px;font-weight:600;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Return Form -->
    <form action="{{ route('cabin-crew.returns.store', $request) }}" method="POST" id="returnForm">
        @csrf
        
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">Select Items to Return</h2>
            <p style="color:#718096;margin:0 0 24px 0;font-size:14px;">Choose products and specify quantities, condition, and reason</p>

            <div id="returnItems" style="display:grid;gap:16px;">
                <!-- Items will be added here dynamically -->
            </div>

            <button type="button" onclick="addReturnItem()" style="margin-top:16px;display:inline-flex;align-items:center;gap:8px;background:#e0f2fe;color:#0369a1;padding:10px 16px;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Item to Return
            </button>
        </div>

        <!-- Submit Buttons -->
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('cabin-crew.returns.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:#e5e7eb;color:#374151;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
                Cancel
            </a>
            <button type="submit" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);color:white;padding:12px 32px;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Submit Return
            </button>
        </div>
    </form>
</div>

<script>
let itemIndex = 0;
const products = @json($products);

function addReturnItem() {
    const container = document.getElementById('returnItems');
    const itemHtml = `
        <div class="return-item" style="background:#f7fafc;border-radius:12px;padding:20px;border:2px solid #e2e8f0;" data-index="${itemIndex}">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:16px;">
                <h3 style="font-size:16px;font-weight:700;color:#1a202c;margin:0;">Item #${itemIndex + 1}</h3>
                <button type="button" onclick="removeReturnItem(${itemIndex})" style="background:#fee2e2;color:#dc2626;border:none;padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                    Remove
                </button>
            </div>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">Product *</label>
                    <select name="returns[${itemIndex}][product_id]" required onchange="updateMaxQuantity(${itemIndex})" style="width:100%;padding:10px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;">
                        <option value="">-- Select Product --</option>
                        ${products.map(p => `<option value="${p.id}" data-max="${p.max_return}">${p.name} (Max: ${p.max_return})</option>`).join('')}
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">
                        Quantity Returning * <span id="max-${itemIndex}" style="font-size:12px;color:#9ca3af;font-weight:400;"></span>
                    </label>
                    <input type="number" name="returns[${itemIndex}][quantity_returned]" required min="1" style="width:100%;padding:10px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;">
                </div>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">Condition *</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                    <label style="background:white;border:2px solid #e2e8f0;border-radius:8px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <input type="radio" name="returns[${itemIndex}][condition]" value="good" required>
                        <span style="font-size:14px;font-weight:600;color:#059669;">✅ Good</span>
                    </label>
                    <label style="background:white;border:2px solid #e2e8f0;border-radius:8px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <input type="radio" name="returns[${itemIndex}][condition]" value="damaged" required>
                        <span style="font-size:14px;font-weight:600;color:#dc2626;">⚠️ Damaged</span>
                    </label>
                    <label style="background:white;border:2px solid #e2e8f0;border-radius:8px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <input type="radio" name="returns[${itemIndex}][condition]" value="expired" required>
                        <span style="font-size:14px;font-weight:600;color:#d97706;">⏰ Expired</span>
                    </label>
                </div>
            </div>
            
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;font-size:14px;">Reason (Optional)</label>
                <textarea name="returns[${itemIndex}][reason]" rows="2" placeholder="Why are you returning this item?" style="width:100%;padding:10px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;resize:vertical;"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeReturnItem(index) {
    const item = document.querySelector(`[data-index="${index}"]`);
    if (item) item.remove();
}

function updateMaxQuantity(index) {
    const select = document.querySelector(`select[name="returns[${index}][product_id]"]`);
    const maxSpan = document.getElementById(`max-${index}`);
    const qtyInput = document.querySelector(`input[name="returns[${index}][quantity_returned]"]`);
    
    if (select.value) {
        const maxReturn = select.options[select.selectedIndex].getAttribute('data-max');
        maxSpan.textContent = `(Max: ${maxReturn})`;
        qtyInput.setAttribute('max', maxReturn);
    } else {
        maxSpan.textContent = '';
        qtyInput.removeAttribute('max');
    }
}

// Add first item by default
addReturnItem();
</script>
@endsection
