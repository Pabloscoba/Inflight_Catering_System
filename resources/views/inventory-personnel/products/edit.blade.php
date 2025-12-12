<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - {{ $product->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .btn-back { padding: 10px 20px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; }
        .btn-back:hover { background: #5a6268; }
        
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 30px; }
        
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group textarea { resize: vertical; min-height: 100px; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #0066cc; }
        .form-group .help-text { font-size: 12px; color: #6c757d; margin-top: 6px; }
        .form-group .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
        
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .checkbox-group input[type="checkbox"] { width: auto; height: 18px; cursor: pointer; }
        .checkbox-group label { margin: 0; cursor: pointer; font-weight: normal; }
        
        .form-actions { display: flex; gap: 12px; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .form-actions-right { display: flex; gap: 12px; }
        .btn { padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #28a745; color: #fff; }
        .btn-primary:hover { background: #218838; }
        .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #f8f9fa; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #c82333; }
        
        .info-box { padding: 14px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 6px; margin-bottom: 20px; }
        .info-box p { font-size: 13px; color: #0c5460; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Product</h1>
            <a href="{{ route('inventory-personnel.products.index') }}" class="btn-back">← Back to Products</a>
        </div>
        
        <div class="info-box">
            <p><strong>Product ID:</strong> {{ $product->id }} | <strong>SKU:</strong> {{ $product->sku }} | <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}</p>
        </div>
        
        <div class="card">
            <form method="POST" action="{{ route('inventory-personnel.products.update', $product) }}">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required autofocus>
                        @error('name')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>SKU (Stock Keeping Unit) <span class="required">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        <div class="help-text">Unique product code</div>
                        @error('sku')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category_id" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Unit of Measure <span class="required">*</span></label>
                        <select name="unit_of_measure" required>
                            <option value="piece" {{ old('unit_of_measure', $product->unit_of_measure) == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="kg" {{ old('unit_of_measure', $product->unit_of_measure) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="liter" {{ old('unit_of_measure', $product->unit_of_measure) == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="box" {{ old('unit_of_measure', $product->unit_of_measure) == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="pack" {{ old('unit_of_measure', $product->unit_of_measure) == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="bottle" {{ old('unit_of_measure', $product->unit_of_measure) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                            <option value="can" {{ old('unit_of_measure', $product->unit_of_measure) == 'can' ? 'selected' : '' }}>Can</option>
                        </select>
                        @error('unit_of_measure')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Currency <span class="required">*</span></label>
                        <select name="currency" required>
                            <option value="TZS" {{ old('currency', $product->currency ?? 'TZS') == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                            <option value="USD" {{ old('currency', $product->currency ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency', $product->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency', $product->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="KES" {{ old('currency', $product->currency ?? '') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="UGX" {{ old('currency', $product->currency ?? '') == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                        </select>
                        @error('currency')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Unit Price <span class="required">*</span></label>
                        <input type="number" name="unit_price" value="{{ old('unit_price', $product->unit_price) }}" step="0.01" min="0" required>
                        <div class="help-text">Price per unit in selected currency</div>
                        @error('unit_price')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Current Stock Quantity <span class="required">*</span></label>
                        <input type="number" name="quantity_in_stock" value="{{ old('quantity_in_stock', $product->quantity_in_stock) }}" min="0" required>
                        @error('quantity_in_stock')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Reorder Level <span class="required">*</span></label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', $product->reorder_level) }}" min="0" required>
                        <div class="help-text">Alert when stock reaches this level</div>
                        @error('reorder_level')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label for="is_active">Product is Active</label>
                        </div>
                        <div class="help-text">Inactive products won't be available for requests</div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <div>
                        <!-- Delete button will be outside this form -->
                    </div>
                    
                    <div class="form-actions-right">
                        <a href="{{ route('inventory-personnel.products.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </div>
            </form>
            
            <!-- Separate Delete Form Outside Main Form -->
            <form method="POST" action="{{ route('inventory-personnel.products.destroy', $product) }}" onsubmit="return confirm('Delete this product? This action cannot be undone.');" style="margin-top: 20px;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Product</button>
            </form>
        </div>
    </div>
</body>
</html>
