@extends('layouts.app')

@section('title', 'Meal Details - ' . $meal->name)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>{{ $meal->name }}</h1>
            <p>Complete meal details and information</p>
        </div>
        <div style="display:flex;gap:12px;">
            <a href="{{ route('catering-staff.meals.edit', $meal) }}" style="display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Meal
            </a>
            <a href="{{ route('catering-staff.meals.index') }}" style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Meals
            </a>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 350px;gap:24px;">
    <!-- Main Content -->
    <div>
        <!-- Meal Photo & Basic Info -->
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            @if($meal->photo)
                <div style="margin-bottom:24px;border-radius:12px;overflow:hidden;max-height:400px;">
                    <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" style="width:100%;height:auto;object-fit:cover;">
                </div>
            @else
                <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;height:300px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
                    <svg style="width:80px;height:80px;color:white;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            
            <div style="display:flex;gap:12px;margin-bottom:20px;">
                @php
                    $mealBadges = [
                        'breakfast' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '🍳', 'label' => 'Breakfast'],
                        'lunch' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '🍽️', 'label' => 'Lunch'],
                        'dinner' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '🌙', 'label' => 'Dinner'],
                        'snack' => ['bg' => '#fce7f3', 'color' => '#9f1239', 'icon' => '🍪', 'label' => 'Snack'],
                        'VIP_meal' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'icon' => '👑', 'label' => 'VIP Meal'],
                        'special_meal' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '⭐', 'label' => 'Special Meal'],
                    ];
                    $badge = $mealBadges[$meal->meal_type] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'Unknown'];
                @endphp
                <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;display:inline-flex;align-items:center;gap:8px;">
                    <span style="font-size:18px;">{{ $badge['icon'] }}</span>
                    {{ $badge['label'] }}
                </span>
                
                @if($meal->is_special_meal)
                    <span style="background:#fef3c7;color:#92400e;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;">
                        ⭐ Special Meal
                    </span>
                @endif
                
                <span style="background:#eff6ff;color:#1e40af;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;">
                    {{ $meal->category->name ?? 'N/A' }}
                </span>
            </div>
            
            @if($meal->description)
                <p style="color:#4b5563;line-height:1.6;margin-bottom:20px;">{{ $meal->description }}</p>
            @endif
            
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;padding-top:20px;border-top:1px solid #f3f4f6;">
                <div>
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">SKU</div>
                    <code style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-size:13px;color:#4b5563;">{{ $meal->sku }}</code>
                </div>
                <div>
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Status</div>
                    <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:8px;font-size:12px;font-weight:600;">
                        {{ ucfirst($meal->status ?? 'active') }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Recipe & Ingredients -->
        @if($meal->ingredients || $meal->allergen_info || $meal->portion_size || $meal->nutritional_info)
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">🥘 Recipe & Nutrition</h3>
            
            @if($meal->ingredients)
                <div style="margin-bottom:20px;">
                    <div style="font-weight:600;color:#374151;margin-bottom:8px;">Ingredients</div>
                    <p style="color:#4b5563;line-height:1.6;white-space:pre-wrap;">{{ $meal->ingredients }}</p>
                </div>
            @endif
            
            @if($meal->allergen_info)
                <div style="margin-bottom:20px;background:#fef2f2;border-left:4px solid #ef4444;padding:16px;border-radius:8px;">
                    <div style="font-weight:600;color:#991b1b;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                        <svg style="width:20px;height:20px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Allergen Information
                    </div>
                    <p style="color:#991b1b;line-height:1.6;">{{ $meal->allergen_info }}</p>
                </div>
            @endif
            
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                @if($meal->portion_size)
                    <div>
                        <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Portion Size</div>
                        <div style="font-weight:600;color:#1f2937;">{{ $meal->portion_size }}</div>
                    </div>
                @endif
                
                @if($meal->nutritional_info)
                    <div>
                        <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Nutritional Info</div>
                        <div style="font-weight:600;color:#1f2937;">{{ $meal->nutritional_info }}</div>
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Preparation Instructions -->
        @if($meal->preparation_instructions)
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">📝 Preparation Instructions</h3>
            <p style="color:#4b5563;line-height:1.8;white-space:pre-wrap;">{{ $meal->preparation_instructions }}</p>
        </div>
        @endif
        
        <!-- Special Requirements -->
        @if($meal->special_requirements)
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 20px 0;border-bottom:2px solid #f3f4f6;padding-bottom:12px;">⭐ Special Requirements</h3>
            <p style="color:#4b5563;line-height:1.6;">{{ $meal->special_requirements }}</p>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div>
        <!-- Menu Planning -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">📅 Menu Planning</h3>
            
            @if($meal->season)
                <div style="margin-bottom:16px;">
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Season</div>
                    <div style="font-weight:600;color:#1f2937;">{{ $meal->season }}</div>
                </div>
            @endif
            
            @if($meal->route)
                <div style="margin-bottom:16px;">
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Route</div>
                    <div style="font-weight:600;color:#1f2937;">{{ $meal->route }}</div>
                </div>
            @endif
            
            @if($meal->menu_version)
                <div style="margin-bottom:16px;">
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Menu Version</div>
                    <div style="font-weight:600;color:#1f2937;">{{ $meal->menu_version }}</div>
                </div>
            @endif
            
            @if($meal->effective_start_date || $meal->effective_end_date)
                <div style="padding-top:16px;border-top:1px solid #f3f4f6;">
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:8px;">Effective Period</div>
                    @if($meal->effective_start_date)
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <svg style="width:16px;height:16px;color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span style="font-size:13px;color:#4b5563;">From: {{ $meal->effective_start_date->format('M d, Y') }}</span>
                        </div>
                    @endif
                    @if($meal->effective_end_date)
                        <div style="display:flex;align-items:center;gap:8px;">
                            <svg style="width:16px;height:16px;color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span style="font-size:13px;color:#4b5563;">Until: {{ $meal->effective_end_date->format('M d, Y') }}</span>
                        </div>
                    @endif
                    
                    @php
                        $today = now()->toDateString();
                        $isActive = (!$meal->effective_start_date || $meal->effective_start_date->toDateString() <= $today) 
                                 && (!$meal->effective_end_date || $meal->effective_end_date->toDateString() >= $today);
                    @endphp
                    
                    <div style="margin-top:12px;">
                        @if($isActive)
                            <span style="background:#d1fae5;color:#065f46;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                                <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Active Menu
                            </span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Audit Information -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="font-size:16px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">📊 Audit Trail</h3>
            
            <div style="margin-bottom:16px;">
                <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Created</div>
                <div style="font-size:13px;color:#4b5563;">{{ $meal->created_at->format('M d, Y') }}</div>
                <div style="font-size:11px;color:#9ca3af;">{{ $meal->created_at->diffForHumans() }}</div>
            </div>
            
            <div>
                <div style="font-size:12px;color:#9ca3af;margin-bottom:4px;">Last Updated</div>
                <div style="font-size:13px;color:#4b5563;">{{ $meal->updated_at->format('M d, Y') }}</div>
                <div style="font-size:11px;color:#9ca3af;">{{ $meal->updated_at->diffForHumans() }}</div>
            </div>
        </div>
        
        <!-- Actions -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-top:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#1a1a1a;margin:0 0 16px 0;">⚡ Actions</h3>
            
            <form action="{{ route('catering-staff.meals.destroy', $meal) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this meal? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" style="width:100%;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px;border-radius:8px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Meal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
