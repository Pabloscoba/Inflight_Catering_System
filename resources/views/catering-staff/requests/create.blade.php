@extends('layouts.app')

@section('title', 'Create Catering Request')

@section('content')
</div>
<div style="padding:24px; max-width:900px; margin:8px auto 40px;">
    <h1 style="margin-bottom:18px; font-size:22px; color:#111827;">Create Catering Request</h1>

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

    <form method="POST" action="{{ route('catering-staff.requests.store') }}" style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 8px 24px rgba(15,23,42,0.06);">
        @csrf
        <div style="max-height: calc(100vh - 240px); overflow-y:auto; padding-right:8px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;align-items:start;">
            <div>
                <label for="flight_id" style="display:block;font-weight:600;margin-bottom:6px;">Flight *</label>
                <select name="flight_id" id="flight_id" class="form-control" required style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;background:white;font-size:14px;">
                    <option value="">-- Select Flight --</option>
                    @foreach(App\Models\Flight::where('status', 'scheduled')->orderBy('departure_time')->get() as $flight)
                        <option value="{{ $flight->id }}" data-departure="{{ optional($flight->departure_time)->format('Y-m-d\TH:i') }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>
                            {{ $flight->flight_number }} — {{ $flight->origin }}→{{ $flight->destination }} @if($flight->departure_time) — {{ $flight->departure_time->format('Y-m-d H:i') }}@endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="flight_schedule_display" style="display:block;font-weight:600;margin-bottom:6px;">Scheduled Flight Time</label>
                <input type="text" id="flight_schedule_display" class="form-control" readonly style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;background:#f8fafc;font-size:14px;color:#111827;" value="{{ old('flight_id') ? optional(App\Models\Flight::find(old('flight_id'))->departure_time)->format('Y-m-d H:i') : '' }}">
                <p style="margin-top:6px;color:#6b7280;font-size:13px;">The scheduled time is set by Flight Ops and will be used as the request time.</p>
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label for="requested_date" style="display:block;font-weight:600;margin-bottom:6px;">Request Time (from scheduled flight)</label>
            <input type="datetime-local" name="requested_date" id="requested_date" class="form-control" readonly value="{{ old('requested_date', '') }}" style="max-width:320px;padding:10px;border-radius:8px;border:1px solid #e5e7eb;background:#f8fafc;font-size:14px;color:#111827;">
            <p style="margin-top:6px;color:#6b7280;font-size:13px;">This value is populated from the flight schedule and cannot be changed here.</p>
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

        <div style="margin-top:10px;">
            <label for="notes" style="display:block;font-weight:600;margin-bottom:6px;">Notes (optional)</label>
            <textarea name="notes" id="notes" class="form-control" style="width:100%;min-height:140px;padding:10px;border-radius:8px;border:1px solid #e5e7eb;margin-bottom:12px;">{{ old('notes') }}</textarea>
        </div>

        <div style="margin-top:16px; padding-top:16px; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:12px;">
            <a href="{{ route('catering-staff.requests.index') }}" class="btn btn-secondary" style="background:#f3f4f6;color:#374151;padding:10px 14px;border-radius:8px;text-decoration:none;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="background:#2563eb;color:white;padding:10px 16px;border-radius:8px;border:none;font-weight:600;">Submit Request</button>
        </div>

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

    // Update scheduled flight display when flight selection changes
    const flightSelect = document.getElementById('flight_id');
    const scheduleDisplay = document.getElementById('flight_schedule_display');
    function updateScheduleDisplay() {
        const opt = flightSelect.options[flightSelect.selectedIndex];
        if (opt && opt.dataset && opt.dataset.departure) {
            const dt = opt.dataset.departure; // 'YYYY-MM-DDTHH:MM'
            const display = dt.replace('T', ' ');
            scheduleDisplay.value = display;
            // Also set the request datetime to the scheduled departure (readonly input)
            const requestedInput = document.getElementById('requested_date');
            if (requestedInput) {
                requestedInput.value = dt;
            }
        } else {
            scheduleDisplay.value = '';
            const requestedInput = document.getElementById('requested_date');
            if (requestedInput) requestedInput.value = '';
        }
    }
    flightSelect.addEventListener('change', updateScheduleDisplay);
    // Initialize on load (if preselected)
    updateScheduleDisplay();

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