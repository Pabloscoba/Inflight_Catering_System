<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
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
        
        .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .btn { padding: 12px 24px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #28a745; color: #fff; }
        .btn-primary:hover { background: #218838; }
        .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New Product</h1>
            <a href="{{ route('inventory-personnel.products.index') }}" class="btn-back">← Back to Products</a>
        </div>
        
        <div class="card">
            <form method="POST" action="{{ route('inventory-personnel.products.store') }}">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>SKU (Stock Keeping Unit) <span class="required">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required>
                        <div class="help-text">Unique product code (e.g., SNK-001)</div>
                        @error('sku')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category_id" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="piece" {{ old('unit_of_measure') == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="kg" {{ old('unit_of_measure') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="liter" {{ old('unit_of_measure') == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="box" {{ old('unit_of_measure') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="pack" {{ old('unit_of_measure') == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="bottle" {{ old('unit_of_measure') == 'bottle' ? 'selected' : '' }}>Bottle</option>
                            <option value="can" {{ old('unit_of_measure') == 'can' ? 'selected' : '' }}>Can</option>
                        </select>
                        @error('unit_of_measure')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Currency <span class="required">*</span></label>
                        <select name="currency" required>
                            <option value="TZS" {{ old('currency', 'TZS') == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                            <option value="UGX" {{ old('currency') == 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                        </select>
                        @error('currency')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Unit Price <span class="required">*</span></label>
                        <input type="number" name="unit_price" value="{{ old('unit_price') }}" step="0.01" min="0" required>
                        <div class="help-text">Price per unit in selected currency</div>
                        @error('unit_price')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Initial Stock Quantity <span class="required">*</span></label>
                        <input type="number" name="quantity_in_stock" value="{{ old('quantity_in_stock', 0) }}" min="0" required>
                        @error('quantity_in_stock')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Reorder Level <span class="required">*</span></label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}" min="0" required>
                        <div class="help-text">Alert when stock reaches this level</div>
                        @error('reorder_level')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active">Product is Active</label>
                        </div>
                        <div class="help-text">Inactive products won't be available for requests</div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('inventory-personnel.products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
