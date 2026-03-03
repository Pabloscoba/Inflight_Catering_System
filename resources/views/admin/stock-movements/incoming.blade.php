@extends('layouts.app')

@section('page-title', 'Record Incoming Stock')
@section('page-description', 'Add new inventory received from suppliers')

@section('content')
    <div class="card-atcl">
        <div class="card-atcl-header">
            <span>📥 Record Incoming Stock</span>
        </div>

        <div
            style="background: #eff6ff; border-left: 4px solid #1e3a8a; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #1e3a8a; font-size: 14px; font-weight: 500;">
            💡 This will increase the product stock quantity. Make sure to verify the received items before recording.
        </div>

        <form method="POST" action="{{ route('admin.stock-movements.store-incoming') }}">
            @csrf

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Product <span style="color: #dc2626;">*</span></label>
                <select name="product_id" required class="input-atcl">
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Current Stock: {{ $product->quantity_in_stock }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label class="label-atcl">Quantity <span style="color: #dc2626;">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required class="input-atcl"
                        placeholder="Enter quantity received">
                    @error('quantity')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Movement Date <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="movement_date" value="{{ old('movement_date', date('Y-m-d')) }}" required
                        class="input-atcl">
                    @error('movement_date')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Reference Number</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}" class="input-atcl"
                    placeholder="Invoice number, PO number, etc.">
                @error('reference_number')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="label-atcl">Notes</label>
                <textarea name="notes" class="input-atcl" style="height: 100px; padding-top: 12px; resize: vertical;"
                    placeholder="Additional notes about this transaction">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #dc2626; font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div
                style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('admin.stock-movements.index') }}" class="btn-atcl btn-atcl-secondary">Cancel</a>
                <button type="submit" class="btn-atcl btn-atcl-primary">Record Incoming Stock</button>
            </div>
        </form>
    </div>
@endsection