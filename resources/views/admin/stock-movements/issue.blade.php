@extends('layouts.app')

@section('page-title', 'Issue Stock to Flight')
@section('page-description', 'Dispatch inventory for flight operations')

@section('content')
<style>
    .card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
    .form-group label span { color: #dc2626; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #0b1a68; box-shadow: 0 0 0 3px rgba(11,26,104,0.1); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .error { color: #dc2626; font-size: 13px; margin-top: 6px; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; font-size: 14px; }
    .btn-primary { background: #d97706; color: white; }
    .btn-primary:hover { background: #b45309; }
    .btn-secondary { background: #e2e8f0; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .warning-box { background: #fef3c7; border-left: 4px solid #d97706; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; color: #78350f; font-size: 14px; }
</style>

<div class="warning-box">
                ⚠️ This will decrease the product stock quantity. Make sure to verify the issued items before recording.
            </div>

            <div class="card">
                <form method="POST" action="{{ route('admin.stock-movements.store-issue') }}">
                    @csrf

                    <div class="form-group">
                        <label>Product <span>*</span></label>
                        <select name="product_id" required>
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Available: {{ $product->quantity_in_stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Quantity <span>*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required placeholder="Enter quantity to issue">
                        @error('quantity')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Reference Number <span>*</span></label>
                        <input type="text" name="reference_number" value="{{ old('reference_number') }}" required placeholder="Flight number, dispatch note, etc.">
                        @error('reference_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Movement Date <span>*</span></label>
                        <input type="date" name="movement_date" value="{{ old('movement_date', date('Y-m-d')) }}" required>
                        @error('movement_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" placeholder="Additional notes about this transaction">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.stock-movements.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Issue Stock</button>
                    </div>
                </form>
            </div>
@endsection
