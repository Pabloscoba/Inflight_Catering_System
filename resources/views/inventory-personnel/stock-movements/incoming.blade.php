@extends('layouts.app')

@section('page-title', 'Record Incoming Stock')
@section('page-description', 'Add new inventory received from suppliers')

@section('content')
@section('content')
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">Record Incoming Stock</h1>
        <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Add new inventory received from suppliers to the main
            stock</p>
    </div>

    <div
        style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 12px; margin-bottom: 24px; color: #1e40af; display: flex; align-items: flex-start; gap: 12px;">
        <span style="font-size: 20px;">💡</span>
        <p style="margin: 0; font-size: 14px; line-height: 1.5;">This action will increase the product stock quantity in the
            <strong>Main Inventory</strong>. Please verify the physical items and quantities before recording to ensure
            accuracy.</p>
    </div>

    <div class="card-atcl" style="padding: 32px; max-width: 800px;">
        <form method="POST" action="{{ route('inventory-personnel.stock-movements.store-incoming') }}">
            @csrf

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Product <span style="color: #dc2626;">*</span></label>
                <select name="product_id" class="input-atcl" required>
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Current: {{ $product->quantity_in_stock }} {{ $product->unit_of_measure }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Quantity <span style="color: #dc2626;">*</span></label>
                <input type="number" name="quantity" class="input-atcl" value="{{ old('quantity') }}" min="1" required
                    placeholder="Enter quantity received">
                @error('quantity')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Reference Number / Invoice</label>
                <input type="text" name="reference_number" class="input-atcl" value="{{ old('reference_number') }}"
                    placeholder="Invoice number, PO number, etc.">
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
                    placeholder="Describe any details about this movement...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div
                style="display: flex; gap: 16px; align-items: center; justify-content: flex-end; padding-top: 24px; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('inventory-personnel.stock-movements.index') }}"
                    class="btn-atcl btn-atcl-secondary">Cancel</a>
                <button type="submit" class="btn-atcl btn-atcl-primary" style="min-width: 200px;">Record Stock
                    Entry</button>
            </div>
        </form>
    </div>
@endsection