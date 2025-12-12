@extends('layouts.app')

@section('title', 'Available Meals')

@section('content')
<div class="content-header">
    <div>
        <h1>Available Meals</h1>
        <p>View meal details to provide better service to passengers</p>
    </div>
</div>

<!-- Filter Section -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
    <form method="GET" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;align-items:end;">
        <!-- Meal Type Filter -->
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:14px;">Meal Type</label>
            <select name="meal_type" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
                <option value="">All Types</option>
                <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>🍳 Breakfast</option>
                <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>🍽️ Lunch</option>
                <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>🌙 Dinner</option>
                <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>🍪 Snack</option>
                <option value="VIP_meal" {{ request('meal_type') == 'VIP_meal' ? 'selected' : '' }}>👑 VIP Meal</option>
                <option value="special_meal" {{ request('meal_type') == 'special_meal' ? 'selected' : '' }}>⭐ Special Meal</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:14px;">Status</label>
            <select name="status" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
                <option value="">All Status</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="authenticated" {{ request('status') == 'authenticated' ? 'selected' : '' }}>Authenticated</option>
                <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
            </select>
        </div>

        <!-- Route Filter -->
        <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:14px;">Route</label>
            <input type="text" name="route" value="{{ request('route') }}" placeholder="e.g., DAR-NBO"
                   style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
        </div>

        <!-- Search & Filter Buttons -->
        <div style="display:flex;gap:8px;">
            <button type="submit" style="flex:1;background:#2563eb;color:white;padding:10px 20px;border-radius:8px;border:none;font-weight:600;cursor:pointer;">
                Filter
            </button>
            <a href="{{ route('cabin-crew.meals.index') }}" 
               style="flex:1;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;text-align:center;font-weight:600;display:inline-block;">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Meals Grid -->
@if($meals->isEmpty())
    <div style="background:white;border-radius:16px;padding:48px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg style="width:64px;height:64px;color:#9ca3af;margin:0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 style="color:#374151;margin-bottom:8px;">No Meals Found</h3>
        <p style="color:#6b7280;">Try adjusting your filters.</p>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
        @foreach($meals as $meal)
        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:transform 0.2s,box-shadow 0.2s;">
            <!-- Meal Photo -->
            @if($meal->photo)
                <div style="height:200px;overflow:hidden;">
                    <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" 
                         style="width:100%;height:100%;object-fit:cover;">
                </div>
            @else
                <div style="height:200px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:60px;height:60px;color:white;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            @endif

            <!-- Meal Content -->
            <div style="padding:20px;">
                <!-- Meal Name & Badge -->
                <div style="margin-bottom:12px;">
                    <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:8px;">{{ $meal->name }}</h3>
                    @php
                        $mealTypeBadges = [
                            'breakfast' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => '🍳'],
                            'lunch' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '🍽️'],
                            'dinner' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'icon' => '🌙'],
                            'snack' => ['bg' => '#fce7f3', 'text' => '#9f1239', 'icon' => '🍪'],
                            'VIP_meal' => ['bg' => '#f3e8ff', 'text' => '#6b21a8', 'icon' => '👑'],
                            'special_meal' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => '⭐'],
                        ];
                        $badge = $mealTypeBadges[$meal->meal_type] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => '📦'];
                    @endphp
                    <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;display:inline-block;">
                        {{ $badge['icon'] }} {{ ucfirst(str_replace('_', ' ', $meal->meal_type)) }}
                    </span>
                </div>

                <!-- Description -->
                @if($meal->description)
                    <p style="color:#6b7280;font-size:14px;line-height:1.5;margin-bottom:16px;">
                        {{ Str::limit($meal->description, 100) }}
                    </p>
                @endif

                <!-- Key Info -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:12px;background:#f9fafb;border-radius:8px;">
                    @if($meal->portion_size)
                    <div>
                        <div style="font-size:12px;color:#6b7280;">Portion</div>
                        <div style="font-weight:600;color:#111827;font-size:14px;">{{ $meal->portion_size }}</div>
                    </div>
                    @endif
                    @if($meal->route)
                    <div>
                        <div style="font-size:12px;color:#6b7280;">Route</div>
                        <div style="font-weight:600;color:#111827;font-size:14px;">{{ $meal->route }}</div>
                    </div>
                    @endif
                </div>

                <!-- Allergen Warning -->
                @if($meal->allergen_info)
                <div style="background:#fef3c7;border-left:3px solid #f59e0b;padding:10px;border-radius:6px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#92400e;font-weight:600;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Allergens: {{ Str::limit($meal->allergen_info, 50) }}
                    </div>
                </div>
                @endif

                <!-- Special Meal Badge -->
                @if($meal->is_special_meal)
                <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:10px;border-radius:8px;text-align:center;margin-bottom:16px;">
                    <span style="font-weight:600;font-size:13px;">⭐ Special Meal</span>
                </div>
                @endif

                <!-- View Details Button -->
                <a href="{{ route('cabin-crew.meals.show', $meal) }}" 
                   style="display:block;background:#2563eb;color:white;padding:12px;border-radius:8px;text-align:center;text-decoration:none;font-weight:600;font-size:14px;">
                    View Full Details
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top:32px;">
        {{ $meals->links() }}
    </div>
@endif
@endsection
