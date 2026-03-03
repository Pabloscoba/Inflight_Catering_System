@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">Add New Product</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Define a new item for the inventory system</p>
        </div>
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
        <a href="{{ route($backRoute) }}" class="btn-atcl btn-atcl-secondary">← Back to Dashboard</a>
    </div>

    <div class="card-atcl" style="padding: 32px; max-width: 1000px; margin: 0 auto;">
        <div
            style="display: flex; gap: 16px; align-items: flex-start; padding: 16px; border-radius: 12px; background: #f0f9ff; border: 1px solid #bae6fd; margin-bottom: 24px;">
            <svg style="width: 24px; height: 24px; color: #0369a1; flex-shrink: 0; margin-top: 2px;" viewBox="0 0 24 24"
                fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <div style="font-weight: 700; color: #0c4a6e; font-size: 15px;">Products require supervisor approval</div>
                <div style="font-size: 14px; color: #0369a1; margin-top: 2px;">Items you create will be submitted for
                    Inventory Supervisor approval before they can be used in the system.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-personnel.products.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 32px;">
                <div style="grid-column: 1 / -1;">
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin-bottom: 16px; border-bottom: 2px solid #f3f4f6; pb: 8px;">
                        Product Details</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Product Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" class="input-atcl" value="{{ old('name') }}" required autofocus
                        placeholder="e.g. Cashew Nuts">
                    @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">SKU <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="sku" class="input-atcl" value="{{ old('sku') }}" required
                        placeholder="e.g. SNK-001">
                    <span style="font-size: 12px; color: #6b7280;">Unique product code</span>
                    @error('sku') <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Category <span style="color: #ef4444;">*</span></label>
                    <select name="category_id" id="category_id" class="input-atcl" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Product Type <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="type" id="product_type" class="input-atcl" value="{{ old('type') }}" required
                        readonly style="background-color: #f9fafb; cursor: not-allowed;">
                    <span style="font-size: 12px; color: #6b7280;">Auto-set based on category</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Meal Type (Optional)</label>
                    <select name="meal_type" class="input-atcl">
                        <option value="">-- Not a meal --</option>
                        <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                        <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                        <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>Snack</option>
                    </select>
                    @error('meal_type') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 32px;">
                <div style="grid-column: 1 / -1;">
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin-bottom: 16px; border-bottom: 2px solid #f3f4f6; pb: 8px;">
                        Stock & Pricing</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Unit of Measure <span style="color: #ef4444;">*</span></label>
                    <select name="unit_of_measure" class="input-atcl" required>
                        <option value="piece" {{ old('unit_of_measure') == 'piece' ? 'selected' : '' }}>Piece</option>
                        <option value="kg" {{ old('unit_of_measure') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="liter" {{ old('unit_of_measure') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="box" {{ old('unit_of_measure') == 'box' ? 'selected' : '' }}>Box</option>
                    </select>
                    @error('unit_of_measure') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Currency <span style="color: #ef4444;">*</span></label>
                    <select name="currency" class="input-atcl" required>
                        <option value="TZS" {{ old('currency', 'TZS') == 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling
                        </option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    </select>
                    @error('currency') <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Unit Price <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="unit_price" class="input-atcl" value="{{ old('unit_price') }}" step="0.01"
                        min="0" required>
                    @error('unit_price') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Initial Quantity <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="quantity_in_stock" class="input-atcl"
                        value="{{ old('quantity_in_stock', 0) }}" min="0" required>
                    @error('quantity_in_stock') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Reorder Level <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="reorder_level" class="input-atcl" value="{{ old('reorder_level', 10) }}"
                        min="0" required>
                    <span style="font-size: 12px; color: #6b7280;">Alert when stock reaches this level</span>
                    @error('reorder_level') <span
                    style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 32px;">
                <label class="label-atcl">Additional Information / Description</label>
                <textarea name="description" class="input-atcl"
                    style="min-height: 120px;">{{ old('description') }}</textarea>
                @error('description') <span style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div
                style="display: flex; gap: 16px; justify-content: flex-end; padding-top: 24px; border-top: 1px solid #f3f4f6;">
                <a href="{{ route($backRoute) }}" class="btn-atcl btn-atcl-secondary"
                    style="height: 48px; display: flex; align-items: center; padding: 0 32px;">Cancel</a>
                <button type="submit" class="btn-atcl btn-atcl-primary"
                    style="height: 48px; padding: 0 40px; font-size: 16px;">Create Product</button>
            </div>
        </form>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category_id');
            const productTypeInput = document.getElementById('product_type');

            // Function to update product type based on category
            function updateProductType() {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categorySlug = selectedOption.getAttribute('data-slug');

                if (categorySlug) {
                    let type = '';

                    // Map category slug to product type
                    switch (categorySlug) {
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