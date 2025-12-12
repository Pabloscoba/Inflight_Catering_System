@extends('layouts.app')

@section('title', 'Create Catering Request')

@section('content')
</div>
<div style="padding:24px; max-width:980px; margin:0 auto;">
    <h1 style="margin-bottom:16px;">Create Catering Request</h1>

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
        <div style="display:grid;grid-template-columns:1fr 240px;gap:16px;align-items:end;">
            <div>
                <label for="flight_id" style="display:block;font-weight:600;margin-bottom:6px;">Flight</label>
                <select name="flight_id" id="flight_id" class="form-control" required style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;background:white;">
                    <option value="">-- Select Flight --</option>
                    @foreach(App\Models\Flight::where('departure_time', '>=', now())->orderBy('departure_time')->limit(30)->get() as $flight)
                        <option value="{{ $flight->id }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>{{ $flight->flight_number }} — {{ $flight->origin }}→{{ $flight->destination }} ({{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, Y H:i') }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="requested_date" style="display:block;font-weight:600;margin-bottom:6px;">Requested Date</label>
                <input type="date" name="requested_date" id="requested_date" class="form-control" required value="{{ old('requested_date', now()->toDateString()) }}" style="width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
            </div>
        </div>

        <div style="margin-top:16px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">Items</label>
            <div id="items-container">
                <div class="item-row" style="display:grid;grid-template-columns:2fr 180px 110px auto;gap:8px;margin-bottom:8px;align-items:center;">
                    <select name="items[0][product_id]" class="form-control product-select" style="padding:10px;border-radius:8px;border:1px solid #e5e7eb;">
                        <option value="">-- Select product --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->quantity_in_stock }} available)</option>
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
            <option value="{{ $p->id }}">{{ addslashes($p->name) }} ({{ $p->quantity_in_stock }} available)</option>
        @endforeach
    `;

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
</script>
@endsection