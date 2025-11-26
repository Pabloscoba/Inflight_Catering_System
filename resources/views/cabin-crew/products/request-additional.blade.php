@extends('layouts.app')

@section('title', 'Request Additional Products')

@section('content')
<div class="content-header">
    <h1>Request Additional Products</h1>
    <p>Request #{{ $requestModel->id }} | Flight: {{ $requestModel->flight->flight_number }}</p>
</div>

<div style="background:white;border-radius:16px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,0.08);max-width:700px;margin:0 auto;">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 8px 0;">➕ Request More Products</h2>
    <p style="color:#718096;font-size:14px;margin:0 0 28px 0;">Request additional products from Catering Staff for ongoing flight route</p>
    
    <form action="{{ route('cabin-crew.products.store-additional', $requestModel) }}" method="POST">
        @csrf
        
        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:10px;">
                Select Product *
            </label>
            <select name="product_id" required 
                    style="width:100%;padding:12px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;background:white;">
                <option value="">-- Choose a product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} ({{ $product->category->name ?? 'N/A' }})
                        @if($product->unit) - {{ $product->unit }} @endif
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <div style="color:#e53e3e;font-size:13px;margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:10px;">
                Quantity Needed *
            </label>
            <input type="number" name="quantity_requested" min="1" value="{{ old('quantity_requested') }}" required
                   style="width:100%;padding:12px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;"
                   placeholder="Enter quantity needed">
            @error('quantity_requested')
                <div style="color:#e53e3e;font-size:13px;margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-bottom:28px;">
            <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:10px;">
                Reason for Request *
            </label>
            <textarea name="reason" rows="4" required
                      style="width:100%;padding:12px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;"
                      placeholder="Explain why you need additional products (e.g., higher passenger demand, defects, etc.)">{{ old('reason') }}</textarea>
            @error('reason')
                <div style="color:#e53e3e;font-size:13px;margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="background:#ebf8ff;border:1px solid #bee3f8;border-radius:8px;padding:16px;margin-bottom:24px;">
            <div style="display:flex;gap:12px;align-items:start;">
                <div style="font-size:20px;">ℹ️</div>
                <div style="font-size:13px;color:#2c5282;">
                    <strong>Note:</strong> Your request will be sent to the Catering Staff for approval. Once approved, the products will be added to your inventory for this flight.
                </div>
            </div>
        </div>
        
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <a href="{{ route('cabin-crew.products.view', $requestModel) }}" 
               style="background:#e2e8f0;color:#2d3748;border:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;display:inline-block;">
                Cancel
            </a>
            <button type="submit" 
                    style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                ➕ Submit Request
            </button>
        </div>
    </form>
</div>
@endsection
