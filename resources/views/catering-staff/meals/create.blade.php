@extends('layouts.app')

@section('title', 'Add New Meal')

@section('content')
    <div class="content-header">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h1>Add New Meal</h1>
                <p>Create a new meal with complete details</p>
            </div>
            <a href="{{ route('catering-staff.meals.index') }}"
                style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to Meals
            </a>
        </div>
    </div>

    @if($errors->any())
        <div
            style="background:#fee2e2;border:1px solid #fecaca;padding:16px;border-radius:8px;margin-bottom:24px;color:#991b1b;">
            <strong>Please correct the following errors:</strong>
            <ul style="margin-top:8px;margin-bottom:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('catering-staff.meals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Basic Information -->
        <div class="card-atcl" style="margin-bottom: 24px;">
            <h3 class="card-atcl-header">📋 Basic Information</h3>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:20px;">
                <div>
                    <label class="label-atcl">Meal Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-atcl"
                        placeholder="e.g., Chicken Caesar Salad">
                    @error('name')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required class="input-atcl"
                        placeholder="e.g., MEAL-001">
                    @error('sku')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:20px;">
                <div>
                    <label class="label-atcl">Category *</label>
                    <select name="category_id" required class="input-atcl">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Meal Type *</label>
                    <select name="meal_type" required class="input-atcl">
                        <option value="">-- Select Meal Type --</option>
                        <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>🍳 Breakfast
                        </option>
                        <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>🍽️ Lunch</option>
                        <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>🌙 Dinner</option>
                        <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>🍪 Snack</option>
                        <option value="VIP_meal" {{ old('meal_type') == 'VIP_meal' ? 'selected' : '' }}>👑 VIP Meal</option>
                        <option value="special_meal" {{ old('meal_type') == 'special_meal' ? 'selected' : '' }}>⭐ Special Meal
                        </option>
                    </select>
                    @error('meal_type')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="label-atcl">Description</label>
                <textarea name="description" rows="3" class="input-atcl"
                    style="height:100px;padding-top:12px;resize:vertical;"
                    placeholder="Brief description of the meal">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="label-atcl">Meal Photo</label>
                <input type="file" name="photo" accept="image/*" class="input-atcl" style="padding-top:8px;">
                <div style="color:#6b7280;font-size:12px;margin-top:4px;">Recommended: JPG, PNG (Max 2MB)</div>
                @error('photo')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Recipe & Ingredients -->
        <div class="card-atcl" style="margin-bottom: 24px;">
            <h3 class="card-atcl-header">🥘 Recipe & Ingredients</h3>

            <div style="margin-bottom:20px;">
                <label class="label-atcl">Ingredients</label>
                <textarea name="ingredients" rows="4" class="input-atcl"
                    style="height:120px;padding-top:12px;resize:vertical;"
                    placeholder="List all ingredients (e.g., Chicken breast, Lettuce, Caesar dressing, Croutons)">{{ old('ingredients') }}</textarea>
                @error('ingredients')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label class="label-atcl">Allergen Information</label>
                <textarea name="allergen_info" rows="2" class="input-atcl"
                    style="height:80px;padding-top:12px;resize:vertical;"
                    placeholder="e.g., Contains: Dairy, Gluten, Nuts">{{ old('allergen_info') }}</textarea>
                @error('allergen_info')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
                <div>
                    <label class="label-atcl">Portion Size</label>
                    <input type="text" name="portion_size" value="{{ old('portion_size') }}" class="input-atcl"
                        placeholder="e.g., 250g, 1 serving">
                    @error('portion_size')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Nutritional Info</label>
                    <input type="text" name="nutritional_info" value="{{ old('nutritional_info') }}" class="input-atcl"
                        placeholder="e.g., 350 cal, 25g protein">
                    @error('nutritional_info')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Menu Planning -->
        <div class="card-atcl" style="margin-bottom: 24px;">
            <h3 class="card-atcl-header">📅 Menu Planning</h3>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px;">
                <div>
                    <label class="label-atcl">Season</label>
                    <input type="text" name="season" value="{{ old('season') }}" class="input-atcl"
                        placeholder="e.g., Summer, Winter">
                    @error('season')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Route</label>
                    <input type="text" name="route" value="{{ old('route') }}" class="input-atcl"
                        placeholder="e.g., DAR-JRO, DAR-ZNZ">
                    @error('route')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Menu Version</label>
                    <input type="text" name="menu_version" value="{{ old('menu_version') }}" class="input-atcl"
                        placeholder="e.g., v1.0, Q1-2025">
                    @error('menu_version')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
                <div>
                    <label class="label-atcl">Effective Start Date</label>
                    <input type="date" name="effective_start_date" value="{{ old('effective_start_date') }}"
                        class="input-atcl">
                    @error('effective_start_date')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-atcl">Effective End Date</label>
                    <input type="date" name="effective_end_date" value="{{ old('effective_end_date') }}" class="input-atcl">
                    @error('effective_end_date')
                        <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Special Meal Options -->
        <div class="card-atcl" style="margin-bottom: 24px;">
            <h3 class="card-atcl-header">⭐ Special Meal Options</h3>

            <div style="margin-bottom:20px;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="is_special_meal" value="1" {{ old('is_special_meal') ? 'checked' : '' }}
                        style="width:20px;height:20px;cursor:pointer;accent-color:#1e3a8a;">
                    <span class="label-atcl" style="margin-bottom:0;">Mark as Special Meal</span>
                </label>
                <div style="color:#6b7280;font-size:12px;margin-top:4px;margin-left:30px;">Check this for meals with special
                    requirements or dietary restrictions</div>
            </div>

            <div>
                <label class="label-atcl">Special Requirements</label>
                <textarea name="special_requirements" rows="3" class="input-atcl"
                    style="height:100px;padding-top:12px;resize:vertical;"
                    placeholder="e.g., Halal certified, Kosher, Vegetarian, Gluten-free">{{ old('special_requirements') }}</textarea>
                @error('special_requirements')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Preparation Instructions -->
        <div class="card-atcl" style="margin-bottom: 24px;">
            <h3 class="card-atcl-header">📝 Preparation Instructions</h3>

            <div>
                <textarea name="preparation_instructions" rows="6" class="input-atcl"
                    style="height:150px;padding-top:12px;resize:vertical;"
                    placeholder="Step-by-step preparation instructions...">{{ old('preparation_instructions') }}</textarea>
                @error('preparation_instructions')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div
            style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;padding-top:24px;border-top:1px solid #f3f4f6;">
            <a href="{{ route('catering-staff.meals.index') }}" class="btn-atcl btn-atcl-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-atcl btn-atcl-primary">
                ✓ Create Meal
            </button>
        </div>
    </form>
@endsection