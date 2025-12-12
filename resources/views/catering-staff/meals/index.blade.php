@extends('layouts.app')

@section('title', 'Meal Management')

@section('content')
<div class="content-header">
    <h1>Meal Management</h1>
    <p>Define meal types, recipes, ingredients, and menus</p>
</div>

<!-- Filter Section -->
<div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
    <form method="GET" action="{{ route('catering-staff.meals.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <!-- Meal Type Filter -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Meal Type</label>
                <select name="meal_type" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    <option value="">All Types</option>
                    <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                    <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                    <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>Snack</option>
                    <option value="VIP_meal" {{ request('meal_type') == 'VIP_meal' ? 'selected' : '' }}>VIP Meal</option>
                    <option value="special_meal" {{ request('meal_type') == 'special_meal' ? 'selected' : '' }}>Special Meal</option>
                </select>
            </div>

            <!-- Season Filter -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Season</label>
                <select name="season" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    <option value="">All Seasons</option>
                    <option value="all-year" {{ request('season') == 'all-year' ? 'selected' : '' }}>All Year</option>
                    <option value="summer" {{ request('season') == 'summer' ? 'selected' : '' }}>Summer</option>
                    <option value="winter" {{ request('season') == 'winter' ? 'selected' : '' }}>Winter</option>
                    <option value="festive" {{ request('season') == 'festive' ? 'selected' : '' }}>Festive Season</option>
                </select>
            </div>

            <!-- Route Filter -->
            <div>
                <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Route</label>
                <input type="text" name="route" value="{{ request('route') }}" placeholder="e.g., DAR-JRO" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
            </div>

            <!-- Active Menu Filter -->
            <div style="display: flex; align-items: end;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="active_menu" value="1" {{ request('active_menu') ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <span style="font-size: 14px; font-weight: 600; color: #374151;">Active Menus Only</span>
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 16px;">
            <button type="submit" style="padding: 10px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Apply Filters
            </button>
            <a href="{{ route('catering-staff.meals.index') }}" style="padding: 10px 24px; background: #e5e7eb; color: #374151; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">
                Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Actions Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin: 0;">
            {{ $meals->total() }} Meals Found
        </h2>
    </div>
    <a href="{{ route('catering-staff.meals.create') }}" style="display: flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);">
        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add New Meal
    </a>
</div>

<!-- Meals Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
    @forelse($meals as $meal)
        <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
            
            <!-- Meal Photo -->
            @if($meal->photo)
                <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" style="width: 100%; height: 200px; object-fit: cover;">
            @else
                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 80px; height: 80px; color: white; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            @endif

            <!-- Meal Info -->
            <div style="padding: 20px;">
                <!-- Badges Row -->
                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 12px; flex-wrap: wrap;">
                    <!-- Meal Type Badge -->
                    @php
                        $mealTypeColors = [
                            'breakfast' => ['bg' => '#fef3c7', 'text' => '#d97706'],
                            'lunch' => ['bg' => '#dbeafe', 'text' => '#2563eb'],
                            'dinner' => ['bg' => '#e0e7ff', 'text' => '#4f46e5'],
                            'snack' => ['bg' => '#fce7f3', 'text' => '#db2777'],
                            'VIP_meal' => ['bg' => '#f3e8ff', 'text' => '#7c3aed'],
                            'special_meal' => ['bg' => '#d1fae5', 'text' => '#059669'],
                        ];
                        $color = $mealTypeColors[$meal->meal_type] ?? ['bg' => '#e5e7eb', 'text' => '#6b7280'];
                    @endphp
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                        {{ str_replace('_', ' ', ucwords($meal->meal_type)) }}
                    </span>

                    <!-- Approval Status Badge -->
                    @php
                        $status = $meal->status ?? 'approved';
                        $statusConfig = [
                            'pending' => ['bg' => '#fef3c7', 'text' => '#d97706', 'label' => '⏳ Pending Approval'],
                            'approved' => ['bg' => '#d1fae5', 'text' => '#059669', 'label' => '✓ Approved'],
                            'rejected' => ['bg' => '#fee2e2', 'text' => '#dc2626', 'label' => '✗ Rejected'],
                        ];
                        $statusStyle = $statusConfig[$status] ?? $statusConfig['approved'];
                    @endphp
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};">
                        {{ $statusStyle['label'] }}
                    </span>
                </div>

                <!-- Meal Name -->
                <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0;">{{ $meal->name }}</h3>
                
                <!-- Meal Description -->
                @if($meal->description)
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0; line-height: 1.5;">
                        {{ Str::limit($meal->description, 100) }}
                    </p>
                @endif

                <!-- Meal Details -->
                <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                    @if($meal->portion_size)
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span>Portion: {{ $meal->portion_size }}</span>
                        </div>
                    @endif

                    @if($meal->season)
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>{{ ucfirst($meal->season) }}</span>
                        </div>
                    @endif

                    @if($meal->route)
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $meal->route }}</span>
                        </div>
                    @endif

                    @if($meal->is_special_meal)
                        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #7c3aed;">
                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Special Meal
                        </span>
                    @endif
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <a href="{{ route('catering-staff.meals.show', $meal) }}" style="flex: 1; padding: 8px; text-align: center; background: #f3f4f6; color: #374151; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">
                        View Details
                    </a>
                    <a href="{{ route('catering-staff.meals.edit', $meal) }}" style="flex: 1; padding: 8px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600;">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 64px 24px; background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 style="font-size: 18px; font-weight: 600; color: #374151; margin: 0 0 8px 0;">No meals found</h3>
            <p style="font-size: 14px; color: #6b7280; margin: 0 0 24px 0;">Start by adding your first meal to the system</p>
            <a href="{{ route('catering-staff.meals.create') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; text-decoration: none; font-weight: 600;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Meal
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($meals->hasPages())
    <div style="margin-top: 32px;">
        {{ $meals->links() }}
    </div>
@endif

@endsection
