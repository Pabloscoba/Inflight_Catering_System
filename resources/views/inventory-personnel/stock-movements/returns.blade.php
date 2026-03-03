@extends('layouts.app')

@section('page-title', 'Record Returns')
@section('page-description', 'Process items returned from flight operations')

@section('content')
@section('content')
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">Record Stock Returns</h1>
        <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Process and restock items returned from flight
            operations</p>
    </div>

    <div
        style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 12px; margin-bottom: 24px; color: #1e40af; display: flex; align-items: flex-start; gap: 12px;">
        <span style="font-size: 20px;">💡</span>
        <p style="margin: 0; font-size: 14px; line-height: 1.5;">Items marked in <strong>Good Condition</strong> will be
            automatically added back to the Main Inventory stock. Damaged items will be recorded for auditing purposes but
            <strong>will not</strong> be restocked.</p>
    </div>

    <div class="card-atcl" style="padding: 32px; max-width: 800px;">
        <form method="POST" action="{{ route('inventory-personnel.stock-movements.store-returns') }}">
            @csrf

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Product <span style="color: #dc2626;">*</span></label>
                <select name="product_id" class="input-atcl" required>
                    <option value="">Select a product to return</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Main Store: {{ $product->quantity_in_stock }} {{ $product->unit_of_measure }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Returned Quantity <span style="color: #dc2626;">*</span></label>
                <input type="number" name="quantity" class="input-atcl" value="{{ old('quantity') }}" min="1" required
                    placeholder="Enter quantity returned">
                @error('quantity')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl" style="margin-bottom: 12px;">Item Condition <span
                        style="color: #dc2626;">*</span></label>
                <div style="display: flex; gap: 16px;">
                    <label style="flex: 1; position: relative;">
                        <input type="radio" name="condition" value="good" {{ old('condition') == 'good' ? 'checked' : '' }}
                            required style="position: absolute; opacity: 0; width: 0; height: 0;">
                        <div class="condition-btn"
                            style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                            <span style="font-size: 20px;">✅</span>
                            <span style="font-weight: 600; font-size: 14px;">Good / Re-stock</span>
                        </div>
                    </label>
                    <label style="flex: 1; position: relative;">
                        <input type="radio" name="condition" value="damaged" {{ old('condition') == 'damaged' ? 'checked' : '' }} required style="position: absolute; opacity: 0; width: 0; height: 0;">
                        <div class="condition-btn"
                            style="padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                            <span style="font-size: 20px;">❌</span>
                            <span style="font-weight: 600; font-size: 14px;">Damaged / Waste</span>
                        </div>
                    </label>
                </div>
                <style>
                    input[type="radio"]:checked+.condition-btn {
                        border-color: #1e3a8a !important;
                        background-color: #eff6ff !important;
                        color: #1e3a8a !important;
                    }
                </style>
                @error('condition')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Reference Number (Flight/Return Note) <span
                        style="color: #dc2626;">*</span></label>
                <input type="text" name="reference_number" class="input-atcl" value="{{ old('reference_number') }}" required
                    placeholder="e.g., AT202 Return, RET-789, etc.">
                @error('reference_number')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Movement Date <span style="color: #dc2626;">*</span></label>
                <input type="date" name="movement_date" class="input-atcl" value="{{ old('movement_date', date('Y-m-d')) }}"
                    required>
                @error('movement_date')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 32px;">
                <label class="label-atcl">Additional Notes</label>
                <textarea name="notes" class="input-atcl" style="min-height: 100px;"
                    placeholder="Describe any details or reasons for the return...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div
                style="display: flex; gap: 16px; align-items: center; justify-content: flex-end; padding-top: 24px; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('inventory-personnel.stock-movements.index') }}"
                    class="btn-atcl btn-atcl-secondary">Cancel</a>
                <button type="submit" class="btn-atcl btn-atcl-primary"
                    style="min-width: 200px; background: #0891b2; border-color: #0891b2;">Process Return</button>
            </div>
        </form>
    </div>
@endsection