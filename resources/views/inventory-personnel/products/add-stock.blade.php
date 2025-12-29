@extends('layouts.app')

@section('title', 'Add Stock - ' . $product->name)

@section('content')
<style>
    body { background: #f5f5f5; }
    .container { max-width: 700px; margin: 0 auto; padding: 40px 20px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .header h1 { font-size: 28px; font-weight: 700; color: #000; margin: 0; }
    .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
    .btn-back:hover { background: #5a6268; }
    
    .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .product-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
    .product-info h3 { font-size: 20px; color: #212529; margin: 0 0 15px 0; font-weight: 600; }
    .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e9ecef; }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-weight: 600; color: #495057; }
    .info-value { color: #212529; }
    .stock-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    .stock-low { background: #ffeaa7; color: #d63031; }
    .stock-out { background: #fab1a0; color: #d63031; }
    .stock-good { background: #55efc4; color: #00b894; }
    
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 10px; }
    .form-group label .required { color: #dc3545; }
    .form-group input, .form-group textarea { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .form-group textarea { resize: vertical; min-height: 100px; font-family: inherit; }
    .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #0066cc; box-shadow: 0 0 0 3px rgba(0,102,204,0.1); }
    .form-group .help-text { font-size: 13px; color: #6c757d; margin-top: 6px; }
    .form-group .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
    
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 25px; border-top: 1px solid #e9ecef; }
    .btn { padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #28a745; color: #fff; }
    .btn-primary:hover { background: #218838; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(40,167,69,0.3); }
    .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #ddd; }
    .btn-secondary:hover { background: #f8f9fa; }
    
    .alert { padding: 14px 18px; border-radius: 6px; margin-bottom: 20px; }
    .alert-info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
</style>

<div class="container">
    <div class="header">
        <h1>➕ Add Stock</h1>
        <a href="{{ route('inventory-personnel.products.index') }}" class="btn-back">← Back to Products</a>
    </div>
    
    <div class="alert alert-info">
        <strong>📦 Direct Stock Addition</strong><br>
        Stock will be added directly to main inventory without creating incoming stock movement.
    </div>
    
    <div class="card">
        <div class="product-info">
            <h3>{{ $product->name }}</h3>
            <div class="info-row">
                <span class="info-label">SKU:</span>
                <span class="info-value">{{ $product->sku }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Category:</span>
                <span class="info-value">{{ $product->category->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Current Stock:</span>
                <span class="info-value">
                    <strong>{{ number_format($product->quantity_in_stock) }}</strong> {{ $product->unit_of_measure }}
                    @if($product->quantity_in_stock == 0)
                        <span class="stock-badge stock-out">Out of Stock</span>
                    @elseif($product->quantity_in_stock <= $product->reorder_level)
                        <span class="stock-badge stock-low">Low Stock</span>
                    @else
                        <span class="stock-badge stock-good">Good Stock</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Reorder Level:</span>
                <span class="info-value">{{ number_format($product->reorder_level) }} {{ $product->unit_of_measure }}</span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('inventory-personnel.products.add-stock.store', $product) }}">
            @csrf
            
            <div class="form-group">
                <label>Quantity to Add <span class="required">*</span></label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required autofocus>
                <div class="help-text">Enter the number of units to add to main stock</div>
                @error('quantity')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Notes (Optional)</label>
                <textarea name="notes" placeholder="e.g., Received from supplier XYZ, Invoice #12345">{{ old('notes') }}</textarea>
                <div class="help-text">Add any relevant notes about this stock addition</div>
                @error('notes')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-actions">
                <a href="{{ route('inventory-personnel.products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">✓ Add Stock to Main Inventory</button>
            </div>
        </form>
    </div>
</div>
@endsection
