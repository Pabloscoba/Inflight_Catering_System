@extends('layouts.app')

@section('title', 'Edit Meal - ' . $meal->name)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Edit Meal</h1>
            <p>Update meal information and details</p>
        </div>
        <a href="{{ route('catering-staff.meals.show', $meal) }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Details
        </a>
    </div>
</div>

@if($errors->any())
<div style="background:#fee2e2;border:1px solid #fecaca;padding:16px;border-radius:8px;margin-bottom:24px;color:#991b1b;">
    <strong>Please correct the following errors:</strong>
    <ul style="margin-top:8px;margin-bottom:0;padding-left:20px;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('catering-staff.meals.update', $meal) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">📋 Basic Information</h3>
        
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Meal Name *</label>
                <input type="text" name="name" value="{{ old('name', $meal->name) }}" required 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., Chicken Caesar Salad">
                @error('name')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $meal->sku) }}" required 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., MEAL-001">
                @error('sku')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Category *</label>
                <select name="category_id" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $meal->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Meal Type *</label>
                <select name="meal_type" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
                    <option value="">-- Select Meal Type --</option>
                    <option value="breakfast" {{ old('meal_type', $meal->meal_type) == 'breakfast' ? 'selected' : '' }}>🍳 Breakfast</option>
                    <option value="lunch" {{ old('meal_type', $meal->meal_type) == 'lunch' ? 'selected' : '' }}>🍽️ Lunch</option>
                    <option value="dinner" {{ old('meal_type', $meal->meal_type) == 'dinner' ? 'selected' : '' }}>🌙 Dinner</option>
                    <option value="snack" {{ old('meal_type', $meal->meal_type) == 'snack' ? 'selected' : '' }}>🍪 Snack</option>
                    <option value="VIP_meal" {{ old('meal_type', $meal->meal_type) == 'VIP_meal' ? 'selected' : '' }}>👑 VIP Meal</option>
                    <option value="special_meal" {{ old('meal_type', $meal->meal_type) == 'special_meal' ? 'selected' : '' }}>⭐ Special Meal</option>
                </select>
                @error('meal_type')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Description</label>
            <textarea name="description" rows="3" 
                      style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                      placeholder="Brief description of the meal">{{ old('description', $meal->description) }}</textarea>
            @error('description')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            @if($meal->photo)
                <div style="margin-bottom:12px;">
                    <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Current Photo</label>
                    <div style="display:inline-block;position:relative;">
                        <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" 
                             style="width:200px;height:150px;object-fit:cover;border-radius:8px;border:2px solid #e5e7eb;">
                    </div>
                </div>
            @endif
            
            <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">
                {{ $meal->photo ? 'Replace Photo (Optional)' : 'Meal Photo' }}
            </label>
            <input type="file" name="photo" accept="image/*" 
                   style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
            <div style="color:#6b7280;font-size:12px;margin-top:4px;">
                {{ $meal->photo ? 'Leave empty to keep current photo' : 'Recommended: JPG, PNG (Max 2MB)' }}
            </div>
            @error('photo')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <!-- Recipe & Ingredients -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">🥘 Recipe & Ingredients</h3>
        
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Ingredients</label>
            <textarea name="ingredients" rows="4" 
                      style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                      placeholder="List all ingredients (e.g., Chicken breast, Lettuce, Caesar dressing, Croutons)">{{ old('ingredients', $meal->ingredients) }}</textarea>
            @error('ingredients')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Allergen Information</label>
            <textarea name="allergen_info" rows="2" 
                      style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                      placeholder="e.g., Contains: Dairy, Gluten, Nuts">{{ old('allergen_info', $meal->allergen_info) }}</textarea>
            @error('allergen_info')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Portion Size</label>
                <input type="text" name="portion_size" value="{{ old('portion_size', $meal->portion_size) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., 250g, 1 serving">
                @error('portion_size')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Nutritional Info</label>
                <input type="text" name="nutritional_info" value="{{ old('nutritional_info', $meal->nutritional_info) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., 350 cal, 25g protein">
                @error('nutritional_info')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Menu Planning -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">📅 Menu Planning</h3>
        
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Season</label>
                <input type="text" name="season" value="{{ old('season', $meal->season) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., Summer, Winter">
                @error('season')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Route</label>
                <input type="text" name="route" value="{{ old('route', $meal->route) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., DAR-JRO, DAR-ZNZ">
                @error('route')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Menu Version</label>
                <input type="text" name="menu_version" value="{{ old('menu_version', $meal->menu_version) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                       placeholder="e.g., v1.0, Q1-2025">
                @error('menu_version')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;">
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Effective Start Date</label>
                <input type="date" name="effective_start_date" value="{{ old('effective_start_date', $meal->effective_start_date?->format('Y-m-d')) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
                @error('effective_start_date')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Effective End Date</label>
                <input type="date" name="effective_end_date" value="{{ old('effective_end_date', $meal->effective_end_date?->format('Y-m-d')) }}" 
                       style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;">
                @error('effective_end_date')
                    <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Special Meal Options -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">⭐ Special Meal Options</h3>
        
        <div style="margin-bottom:20px;">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                <input type="checkbox" name="is_special_meal" value="1" {{ old('is_special_meal', $meal->is_special_meal) ? 'checked' : '' }}
                       style="width:20px;height:20px;cursor:pointer;">
                <span style="font-weight:600;color:#374151;">Mark as Special Meal</span>
            </label>
            <div style="color:#6b7280;font-size:12px;margin-top:4px;margin-left:30px;">Check this for meals with special requirements or dietary restrictions</div>
        </div>
        
        <div>
            <label style="display:block;font-weight:600;margin-bottom:8px;color:#374151;">Special Requirements</label>
            <textarea name="special_requirements" rows="3" 
                      style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                      placeholder="e.g., Halal certified, Kosher, Vegetarian, Gluten-free">{{ old('special_requirements', $meal->special_requirements) }}</textarea>
            @error('special_requirements')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <!-- Preparation Instructions -->
    <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">📝 Preparation Instructions</h3>
        
        <div>
            <textarea name="preparation_instructions" rows="6" 
                      style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;"
                      placeholder="Step-by-step preparation instructions...">{{ old('preparation_instructions', $meal->preparation_instructions) }}</textarea>
            @error('preparation_instructions')
                <div style="color:#dc2626;font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <!-- Form Actions -->
    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('catering-staff.meals.show', $meal) }}" 
           style="background:#f3f4f6;color:#374151;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">
            Cancel
        </a>
        <button type="submit" 
                style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);color:white;border:none;padding:12px 32px;border-radius:8px;font-weight:600;cursor:pointer;">
            ✓ Update Meal
        </button>
    </div>
</form>
@endsection
