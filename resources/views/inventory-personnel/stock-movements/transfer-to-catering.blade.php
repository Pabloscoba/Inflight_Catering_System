<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Stock to Catering - {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: #f3f4f6; min-height: 100vh; padding: 40px 20px; }
        
        .container { max-width: 900px; margin: 0 auto; }
        
        .header { background: white; border-radius: 20px 20px 0 0; padding: 32px 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .header-title { display: flex; align-items: center; gap: 16px; }
        .header-icon { width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); }
        .header-icon svg { width: 32px; height: 32px; color: white; }
        .header-text h1 { font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }
        .header-text p { font-size: 14px; color: #6c757d; margin: 0; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f8f9fa; color: #495057; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all 0.3s; border: 2px solid #e9ecef; }
        .btn-back:hover { background: #e9ecef; transform: translateY(-2px); }
        .btn-back svg { width: 16px; height: 16px; }
        
        .card-body { background: white; padding: 40px; border-radius: 0 0 20px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        
        .info-banner { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 2px solid #0ea5e9; border-radius: 16px; padding: 20px 24px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 16px; }
        .info-banner svg { width: 24px; height: 24px; color: #0369a1; flex-shrink: 0; margin-top: 2px; }
        .info-banner-text { flex: 1; }
        .info-banner-text strong { display: block; font-size: 15px; color: #0c4a6e; margin-bottom: 6px; }
        .info-banner-text p { font-size: 14px; color: #075985; margin: 0; line-height: 1.6; }
        
        .form-section { margin-bottom: 32px; }
        .section-title { font-size: 18px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e9ecef; display: flex; align-items: center; gap: 10px; }
        .section-title svg { width: 20px; height: 20px; color: #3b82f6; }
        
        .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px; }
        .form-row.single { grid-template-columns: 1fr; }
        
        .form-group { position: relative; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 10px; }
        .form-group label .required { color: #ef4444; margin-left: 4px; }
        
        .form-control { width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 15px; color: #1f2937; transition: all 0.3s; background: #f9fafb; }
        .form-control:focus { outline: none; border-color: #3b82f6; background: white; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .form-control:hover { border-color: #d1d5db; }
        
        select.form-control { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; padding-right: 40px; }
        
        textarea.form-control { resize: vertical; min-height: 120px; font-family: inherit; line-height: 1.6; }
        
        .help-text { font-size: 13px; color: #6b7280; margin-top: 8px; display: flex; align-items: center; gap: 6px; }
        .help-text svg { width: 14px; height: 14px; flex-shrink: 0; }
        
        .stock-indicator { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; font-size: 13px; color: #166534; font-weight: 600; margin-top: 8px; }
        .stock-indicator.low { background: #fef3c7; border-color: #fcd34d; color: #92400e; }
        .stock-indicator.empty { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
        .stock-indicator svg { width: 16px; height: 16px; }
        
        .invalid-feedback { color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; align-items: center; gap: 6px; }
        .invalid-feedback svg { width: 14px; height: 14px; }
        
        .alert { padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
        .alert ul { margin: 0; padding-left: 20px; flex: 1; }
        
        .note-box { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #fbbf24; border-radius: 16px; padding: 20px 24px; margin-top: 32px; display: flex; gap: 16px; }
        .note-box svg { width: 24px; height: 24px; color: #d97706; flex-shrink: 0; margin-top: 2px; }
        .note-box-content strong { display: block; font-size: 15px; color: #92400e; margin-bottom: 6px; }
        .note-box-content p { font-size: 14px; color: #78350f; margin: 0; line-height: 1.6; }
        
        .form-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; padding-top: 32px; border-top: 2px solid #f3f4f6; }
        .btn { display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; border-radius: 12px; font-size: 15px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s; }
        .btn svg { width: 18px; height: 18px; }
        .btn-cancel { background: #f3f4f6; color: #4b5563; border: 2px solid #e5e7eb; }
        .btn-cancel:hover { background: #e5e7eb; transform: translateY(-2px); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4); }
        
        @media (max-width: 768px) {
            body { padding: 20px 10px; }
            .header { padding: 24px 20px; border-radius: 16px 16px 0 0; }
            .header-content { flex-direction: column; gap: 20px; text-align: center; }
            .header-title { flex-direction: column; }
            .card-body { padding: 24px 20px; border-radius: 0 0 16px 16px; }
            .form-row { grid-template-columns: 1fr; gap: 16px; }
            .form-actions { flex-direction: column-reverse; gap: 12px; width: 100%; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div class="header-title">
                    <div class="header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div class="header-text">
                        <h1>Transfer Stock to Catering</h1>
                        <p>Move stock from main inventory to catering department</p>
                    </div>
                </div>
                <a href="{{ route('inventory-personnel.stock-movements.index') }}" class="btn-back">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="info-banner">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="info-banner-text">
                    <strong>How Transfer Works</strong>
                    <p>Select a product from main inventory and specify the quantity you want to transfer to the catering department. The transfer will be reviewed and approved by the Inventory Supervisor before stock is moved.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inventory-personnel.stock-movements.store-transfer-to-catering') }}" method="POST">
                @csrf

                <div class="form-section">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Product Selection
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="product_id">Select Product <span class="required">*</span></label>
                            <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                <option value="">-- Choose a product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-stock="{{ $product->quantity_in_stock }}"
                                            data-name="{{ $product->name }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="stock-indicator" style="display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity to Transfer <span class="required">*</span></label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity') }}"
                                   min="1"
                                   placeholder="Enter quantity"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="quantity-help">Enter the number of units to transfer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Transfer Details
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="reference_number">Reference Number <span class="required">*</span></label>
                            <input type="text" 
                                   name="reference_number" 
                                   id="reference_number" 
                                   class="form-control @error('reference_number') is-invalid @enderror" 
                                   value="{{ old('reference_number', 'TRF-CAT-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}"
                                   required>
                            @error('reference_number')
                                <div class="invalid-feedback">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Unique identifier for this transfer
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="movement_date">Transfer Date <span class="required">*</span></label>
                            <input type="date" 
                                   name="movement_date" 
                                   id="movement_date" 
                                   class="form-control @error('movement_date') is-invalid @enderror" 
                                   value="{{ old('movement_date', date('Y-m-d')) }}"
                                   required>
                            @error('movement_date')
                                <div class="invalid-feedback">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Date of the transfer request
                            </div>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label for="notes">Notes/Purpose (Optional)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="e.g., Weekly stock replenishment for catering department&#10;Flight operations require additional meal supplies&#10;Urgent transfer for special event catering">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="help-text">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Add any relevant details about this transfer
                            </div>
                        </div>
                    </div>
                </div>

                <div class="note-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="note-box-content">
                        <strong>Important Notice</strong>
                        <p>This transfer request will be marked as <strong>pending</strong> and requires approval from the Inventory Supervisor. Once approved, the specified quantity will be automatically deducted from the main inventory and added to the catering mini stock. The catering staff will then be able to request these items for their operations.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('inventory-personnel.stock-movements.index') }}" class="btn btn-cancel">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Submit Transfer Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update stock indicator when product is selected
        document.getElementById('product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const availableStock = selectedOption.getAttribute('data-stock');
            const productName = selectedOption.getAttribute('data-name');
            const stockIndicator = document.getElementById('stock-indicator');
            const quantityHelp = document.getElementById('quantity-help');
            
            if (availableStock && productName) {
                const stock = parseInt(availableStock);
                let indicatorClass = 'stock-indicator';
                let icon = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                
                if (stock === 0) {
                    indicatorClass += ' empty';
                    icon = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                } else if (stock <= 10) {
                    indicatorClass += ' low';
                    icon = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                }
                
                stockIndicator.className = indicatorClass;
                stockIndicator.innerHTML = icon + 'Available in main inventory: ' + stock + ' units';
                stockIndicator.style.display = 'inline-flex';
                
                quantityHelp.textContent = 'Maximum transferable: ' + stock + ' units';
                
                // Update quantity max attribute
                document.getElementById('quantity').setAttribute('max', stock);
            } else {
                stockIndicator.style.display = 'none';
                quantityHelp.textContent = 'Enter the number of units to transfer';
                document.getElementById('quantity').removeAttribute('max');
            }
        });

        // Validate quantity doesn't exceed available stock
        document.getElementById('quantity').addEventListener('input', function() {
            const productSelect = document.getElementById('product_id');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const availableStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            const quantity = parseInt(this.value) || 0;
            const quantityHelp = document.getElementById('quantity-help');

            if (quantity > availableStock) {
                this.setCustomValidity('Quantity exceeds available stock');
                quantityHelp.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #ef4444;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span style="color: #ef4444;">⚠️ Insufficient stock! Available: ' + availableStock + ' units</span>';
            } else if (quantity > 0) {
                this.setCustomValidity('');
                quantityHelp.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #10b981;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span style="color: #10b981;">✓ Valid quantity (Remaining in main: ' + (availableStock - quantity) + ' units)</span>';
            } else {
                this.setCustomValidity('');
                quantityHelp.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Maximum transferable: ' + availableStock + ' units';
            }
        });

        // Trigger change event on page load if product is pre-selected
        if (document.getElementById('product_id').value) {
            document.getElementById('product_id').dispatchEvent(new Event('change'));
        }
    </script>
</body>
</html>
