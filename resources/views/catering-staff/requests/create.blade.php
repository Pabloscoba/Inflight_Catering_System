@extends('layouts.app')

@section('title', 'Create Catering Request')

@section('content')
</div>
<div style="padding:20px; max-width:900px; margin:-60px auto 40px;">
    <h1 style="margin-bottom:20px; font-size:24px; color:#1f2937;">Create Catering Request</h1>

    @if($errors->any())
        <div style="background:#ffe9ea;border:1px solid #ffc4c7;padding:12px;border-radius:8px;margin-bottom:16px;color:#842029;">
            <strong>There were some problems with your input:</strong>
            <ul style="margin-top:8px;margin-bottom:0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('catering-staff.requests.store') }}" style="background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
            <div>
                <label for="flight_id" style="display:block;font-weight:600;margin-bottom:6px;">Flight *</label>
                <select name="flight_id" id="flight_id" class="form-control" required style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;background:white;">
                    <option value="">-- Select Flight --</option>
                    @foreach(App\Models\Flight::where('status', 'scheduled')->orderBy('flight_number')->get() as $flight)
                        <option value="{{ $flight->id }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>
                            {{ $flight->flight_number }} — {{ $flight->origin }}→{{ $flight->destination }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="flight_datetime" style="display:block;font-weight:600;margin-bottom:6px;">Flight Date & Time *</label>
                <input type="datetime-local" name="flight_datetime" id="flight_datetime" class="form-control" required value="{{ old('flight_datetime', now()->format('Y-m-d\TH:i')) }}" min="{{ now()->format('Y-m-d\TH:i') }}" style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label for="requested_date" style="display:block;font-weight:600;margin-bottom:6px;">Request Date & Time *</label>
            <input type="datetime-local" name="requested_date" id="requested_date" class="form-control" required value="{{ old('requested_date', now()->format('Y-m-d\TH:i')) }}" min="{{ now()->format('Y-m-d\TH:i') }}" style="max-width:300px;padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
        </div>

        <div style="margin-top:16px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">Items</label>
            <div id="items-container">
                <div class="item-row" style="display:grid;grid-template-columns:2fr 180px 110px auto;gap:8px;margin-bottom:8px;align-items:center;">
                    <select name="items[0][product_id]" class="form-control product-select" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
                        <option value="">-- Select product --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $p->quantity_in_stock <= 0 ? 'disabled' : '' }} style="{{ $p->quantity_in_stock <= 0 ? 'color:#9ca3af;text-decoration:line-through;' : '' }}">
                            {{ $p->name }} ({{ $p->quantity_in_stock > 0 ? $p->quantity_in_stock . ' available' : 'OUT OF STOCK' }})
                        </option>
                        @endforeach
                    </select>
                    <select name="items[0][meal_type]" class="form-control meal-type-select" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
                        <option value="">-- Meal Type --</option>
                        <option value="breakfast">🍳 Breakfast</option>
                        <option value="lunch">🍽️ Lunch</option>
                        <option value="dinner">🌙 Dinner</option>
                        <option value="snack">🍪 Snack</option>
                        <option value="VIP_meal">👑 VIP Meal</option>
                        <option value="special_meal">⭐ Special</option>
                        <option value="non_meal">📦 Non-Meal</option>
                    </select>
                    <input type="number" name="items[0][quantity]" class="form-control" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;" min="1" value="1" placeholder="Qty">
                    <button type="button" class="btn btn-danger remove-item" style="background:#ef4444;color:white;border-radius:8px;padding:8px 10px;border:none;">Remove</button>
                </div>
            </div>
            <div style="margin-top:10px;">
                <button type="button" id="add-item" class="btn" style="background:#374151;color:white;border-radius:8px;padding:10px 14px;border:none;">+ Add item</button>
            </div>
        </div>

        <div style="margin-top:18px;">
            <label for="notes" style="display:block;font-weight:600;margin-bottom:6px;">Notes (optional)</label>
            <textarea name="notes" id="notes" class="form-control" style="width:100%;min-height:100px;padding:10px;border-radius:8px;border:1px solid #e5e7eb;">{{ old('notes') }}</textarea>
        </div>

        <div style="margin-top:18px;display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary" style="background:#2563eb;color:white;padding:10px 16px;border-radius:8px;border:none;font-weight:600;">Submit Request</button>
            <a href="{{ route('catering-staff.requests.index') }}" class="btn btn-secondary" style="background:#f3f4f6;color:#374151;padding:10px 14px;border-radius:8px;text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Pre-rendered product options to avoid repeated blade loops in JS
    var productOptions = `
        <option value="">-- Select product --</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" {{ $p->quantity_in_stock <= 0 ? 'disabled' : '' }} style="{{ $p->quantity_in_stock <= 0 ? 'color:#9ca3af;text-decoration:line-through;' : '' }}">
                {{ addslashes($p->name) }} ({{ $p->quantity_in_stock > 0 ? $p->quantity_in_stock . ' available' : 'OUT OF STOCK' }})
            </option>
        @endforeach
    `;

    // Set minimum date-time to now
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.getElementById('requested_date').setAttribute('min', minDateTime);

    let idx = 1;
    document.getElementById('add-item').addEventListener('click', function(){
        const container = document.getElementById('items-container');
        const div = document.createElement('div');
        div.className = 'item-row';
        div.style = 'display:grid;grid-template-columns:2fr 180px 110px auto;gap:8px;margin-bottom:8px;align-items:center;';
        div.innerHTML = `
            <select name="items[${idx}][product_id]" class="form-control product-select" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;">${productOptions}</select>
            <select name="items[${idx}][meal_type]" class="form-control meal-type-select" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
                <option value="">-- Meal Type --</option>
                <option value="breakfast">🍳 Breakfast</option>
                <option value="lunch">🍽️ Lunch</option>
                <option value="dinner">🌙 Dinner</option>
                <option value="snack">🍪 Snack</option>
                <option value="VIP_meal">👑 VIP Meal</option>
                <option value="special_meal">⭐ Special</option>
                <option value="non_meal">📦 Non-Meal</option>
            </select>
            <input type="number" name="items[${idx}][quantity]" class="form-control" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;" min="1" value="1" placeholder="Qty">
            <button type="button" class="btn btn-danger remove-item" style="background:#ef4444;color:white;border-radius:8px;padding:8px 10px;border:none;">Remove</button>
        `;
        container.appendChild(div);
        idx++;
    });

    document.addEventListener('click', function(e){
        if (e.target && e.target.classList.contains('remove-item')) {
            const row = e.target.closest('.item-row');
            if (row) row.remove();
        }
    });

    // Validate form before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const requestedDate = document.getElementById('requested_date').value;
        const todayDate = new Date().toISOString().split('T')[0];
        
        // Check if date is in the past
        if (requestedDate < todayDate) {
            e.preventDefault();
            if (typeof Toast !== 'undefined') {
                Toast.error('❌ Tarehe haiwezi kuwa iliyopita. Chagua tarehe ya leo au ijayo.');
            }
            document.getElementById('requested_date').focus();
            return false;
        }

        // Check if at least one product is selected
        const productSelects = document.querySelectorAll('.product-select');
        let hasValidProduct = false;
        let hasOutOfStockProduct = false;
        
        productSelects.forEach(select => {
            if (select.value) {
                hasValidProduct = true;
                // Check if selected option is disabled (out of stock)
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption.disabled) {
                    hasOutOfStockProduct = true;
                }
            }
        });

        if (!hasValidProduct) {
            e.preventDefault();
            if (typeof Toast !== 'undefined') {
                Toast.warning('❌ Tafadhali chagua angalau product moja.');
            }
            return false;
        }

        if (hasOutOfStockProduct) {
            e.preventDefault();
            if (typeof Toast !== 'undefined') {
                Toast.error('❌ Umechagua product ambaya haipo stock. Tafadhali chagua products ambazo zipo stock.');
            }
            return false;
        }
    });

    // Prevent selecting disabled (out of stock) options
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption.disabled && selectedOption.value) {
                if (typeof Toast !== 'undefined') {
                    Toast.warning('⚠️ Product hii haipo stock. Tafadhali chagua product nyingine.');
                }
                e.target.value = '';
            }
        }
    });
</script>
@endsection