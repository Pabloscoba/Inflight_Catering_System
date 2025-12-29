@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<style>
    body { background: #f5f5f5; }
    .container { max-width: 980px; margin: 0 auto; padding: 32px 18px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    .header h1 { font-size: 26px; font-weight: 700; color: #222; margin: 0; }
    .btn-back { padding: 8px 14px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; }
    .btn-back:hover { background: #5a6268; }

    .card { background: #fff; border: 1px solid #e6e6e6; border-radius: 8px; padding: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
    .info-banner { display:flex; gap:10px; align-items:center; padding:10px 12px; border-radius:6px; background:#f8fafc; border:1px solid #e6f0ef; color:#065f46; margin-bottom:18px; }
    .info-banner strong { color:#064e3b; }
    fieldset { border: 1px solid #eef2f7; padding: 14px; border-radius: 6px; margin-bottom: 14px; }
    legend { font-weight: 700; padding: 0 8px; font-size: 13px; color: #0f172a; }
    .muted-note { font-size:13px; color:#6b7280; margin-top:8px; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 10px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; }
    .form-group label .required { color: #dc3545; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 14px; background:#fff; }
    .form-group textarea { resize: vertical; min-height: 100px; font-family: inherit; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.08); }
    .form-group .help-text { font-size: 12px; color: #6c757d; margin-top: 6px; }
    .form-group .error { color: #dc3545; font-size: 13px; margin-top: 6px; }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; padding-top: 18px; border-top: 1px solid #f1f1f1; }
    .btn { padding: 10px 18px; border-radius: 6px; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-primary { background: #0ea5a4; color: #fff; }
    .btn-primary:hover { background: #08928d; }
    .btn-secondary { background: #fff; color: #6c757d; border: 1px solid #e1e1e1; }
    .btn-secondary:hover { background: #f8f9fa; }
</style>

<div class="container">
    <div class="header">
        <h1>Add New Product</h1>
        @php
            $backRoute = 'inventory-personnel.products.index';
            if (auth()->user()->hasRole('Cabin Crew')) {
                $backRoute = 'cabin-crew.dashboard';
            } elseif (auth()->user()->hasRole('Catering Staff')) {
                $backRoute = 'catering-staff.dashboard';
            } elseif (auth()->user()->hasRole('Security Staff')) {
                $backRoute = 'security-staff.dashboard';
            } elseif (auth()->user()->hasRole('Ramp Dispatcher')) {
                $backRoute = 'ramp-dispatcher.dashboard';
            }
        @endphp
        <a href="{{ route($backRoute) }}" class="btn-back">← Back to Dashboard</a>
    </div>

    <div class="card">
            <div class="info-banner">
                <svg style="width:20px;height:20px;color:#065f46;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <div><strong>Products require supervisor approval</strong></div>
                    <div style="font-size:13px;color:#065f46;">Items you create will be submitted for Inventory Supervisor approval before use.</div>
                </div>
            </div>

            <form method="POST" action="{{ route('inventory-personnel.products.store') }}">
                @csrf
                
                <fieldset>
                    <legend>Product Details</legend>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Product Name <span class="required">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>SKU <span class="required">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku') }}" required>
                            <div class="help-text">Unique product code (e.g., SNK-001)</div>
                            @error('sku')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Category <span class="required">*</span></label>
                            <select name="category_id" id="category_id" required>
                                <option value="">-- Select Category --</option>
                                @if(isset($categories) && $categories->count())
                                    @if(isset($categories->first()->parent_id))
                                        @php $grouped = $categories->groupBy('parent_id'); @endphp
                                        @foreach($grouped as $parentId => $group)
                                            @if($parentId)
                                                @php $parent = $categories->firstWhere('id', $parentId); @endphp
                                                <optgroup label="{{ $parent ? $parent->name : 'Group' }}">
                                                    @foreach($group as $cat)
                                                        <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                @foreach($group as $cat)
                                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                            <div class="help-text">Choose the best category. You can add categories from settings if missing.</div>
                            @error('category_id')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Product Type <span class="required">*</span></label>
                            <input type="text" name="type" id="product_type" value="{{ old('type') }}" required readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                            <div class="help-text">Automatically set based on category selection</div>
                            @error('type')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Meal Type</label>
                            <select name="meal_type">
                                <option value="">-- Not a meal / Select meal type --</option>
                                <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                                <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                                <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                                <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>Snack</option>
                                <option value="VIP_meal" {{ old('meal_type') == 'VIP_meal' ? 'selected' : '' }}>VIP Meal</option>
                                <option value="special_meal" {{ old('meal_type') == 'special_meal' ? 'selected' : '' }}>Special Meal</option>
                            </select>
                            <div class="help-text">If this product is used as a meal, choose a meal type.</div>
                            @error('meal_type')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Stock & Pricing</legend>
                    <div class="form-grid">
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
                            <div class="help-text">This quantity will be added to main stock after supervisor approval</div>
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
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Additional</legend>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                
                <div class="form-actions">
                    <a href="{{ route($backRoute) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const productTypeInput = document.getElementById('product_type');
    
    // Function to update product type based on category
    function updateProductType() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categorySlug = selectedOption.getAttribute('data-slug');
        
        if (categorySlug) {
            let type = '';
            
            // Map category slug to product type
            switch(categorySlug) {
                case 'food':
                    type = 'Food';
                    break;
                case 'drinks':
                    type = 'Drink';
                    break;
                case 'bites':
                    type = 'Food';
                    break;
                case 'accessories':
                    type = 'Accessory';
                    break;
                default:
                    type = '';
            }
            
            productTypeInput.value = type;
        } else {
            productTypeInput.value = '';
        }
    }
    
    // Update type when category changes
    categorySelect.addEventListener('change', updateProductType);
    
    // Initialize type on page load if category is pre-selected
    if (categorySelect.value) {
        updateProductType();
    }
});
</script>
@endsection
