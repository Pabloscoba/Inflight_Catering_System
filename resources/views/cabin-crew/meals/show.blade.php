@extends('layouts.app')

@section('title', $meal->name . ' - Meal Details')

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>{{ $meal->name }}</h1>
            <p>Complete meal information for passenger service</p>
        </div>
        <a href="{{ route('cabin-crew.meals.index') }}" 
           style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Meals
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">
    <!-- Main Content -->
    <div>
        <!-- Meal Photo & Header -->
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            @if($meal->photo)
                <div style="margin-bottom:24px;border-radius:12px;overflow:hidden;max-height:450px;">
                    <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" style="width:100%;height:auto;object-fit:cover;">
                </div>
            @else
                <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;height:350px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
                    <svg style="width:100px;height:100px;color:white;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            @endif

            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <h2 style="font-size:32px;font-weight:700;color:#111827;margin:0;">{{ $meal->name }}</h2>
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
                <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:10px 18px;border-radius:10px;font-size:16px;font-weight:600;">
                    {{ $badge['icon'] }} {{ ucfirst(str_replace('_', ' ', $meal->meal_type)) }}
                </span>
            </div>

            @if($meal->description)
                <p style="color:#6b7280;margin-bottom:28px;line-height:1.7;font-size:16px;">{{ $meal->description }}</p>
            @endif

            <!-- Key Info Grid -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:32px;padding:24px;background:#f9fafb;border-radius:12px;">
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">SKU</div>
                    <div style="font-weight:700;color:#111827;font-size:16px;">{{ $meal->sku }}</div>
                </div>
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Category</div>
                    <div style="font-weight:700;color:#111827;font-size:16px;">{{ $meal->category->name ?? 'N/A' }}</div>
                </div>
                @if($meal->portion_size)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:6px;">Portion Size</div>
                    <div style="font-weight:700;color:#111827;font-size:16px;">{{ $meal->portion_size }}</div>
                </div>
                @endif
            </div>

            <!-- Ingredients & Recipe -->
            @if($meal->ingredients)
            <div style="margin-bottom:32px;">
                <h3 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                    <svg style="width:24px;height:24px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ingredients
                </h3>
                <div style="background:#ffffff;border:2px solid #e5e7eb;border-radius:12px;padding:20px;">
                    <p style="color:#374151;line-height:1.8;white-space:pre-line;">{{ $meal->ingredients }}</p>
                </div>
            </div>
            @endif

            <!-- Allergen Information -->
            @if($meal->allergen_info)
            <div style="margin-bottom:32px;">
                <div style="background:#fef3c7;border-left:6px solid #f59e0b;padding:24px;border-radius:12px;">
                    <h3 style="font-weight:700;color:#92400e;margin-bottom:12px;font-size:18px;display:flex;align-items:center;gap:10px;">
                        <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        ⚠️ Allergen Information
                    </h3>
                    <p style="color:#92400e;line-height:1.6;font-size:15px;">{{ $meal->allergen_info }}</p>
                </div>
            </div>
            @endif

            <!-- Nutritional Info -->
            @if($meal->nutritional_info)
            <div style="margin-bottom:32px;">
                <h3 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                    <svg style="width:24px;height:24px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Nutritional Information
                </h3>
                <div style="background:#f0fdf4;border:2px solid #86efac;border-radius:12px;padding:20px;">
                    <p style="color:#166534;line-height:1.8;white-space:pre-line;">{{ $meal->nutritional_info }}</p>
                </div>
            </div>
            @endif

            <!-- Preparation Instructions -->
            @if($meal->preparation_instructions)
            <div>
                <h3 style="font-size:20px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                    <svg style="width:24px;height:24px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Preparation Instructions
                </h3>
                <div style="background:#eff6ff;border:2px solid #93c5fd;border-radius:12px;padding:20px;">
                    <p style="color:#1e40af;line-height:1.9;white-space:pre-line;font-size:15px;">{{ $meal->preparation_instructions }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Status Card -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:16px;">Meal Status</h3>
            @php
                $statusColors = [
                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                    'approved' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                    'authenticated' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                    'dispatched' => ['bg' => '#fce7f3', 'text' => '#9f1239'],
                    'received' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                ];
                $statusStyle = $statusColors[$meal->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
            @endphp
            <span style="display:block;background:{{ $statusStyle['bg'] }};color:{{ $statusStyle['text'] }};padding:12px 20px;border-radius:10px;font-weight:700;text-align:center;font-size:15px;">
                {{ ucfirst($meal->status) }}
            </span>
        </div>

        <!-- Menu Planning -->
        @if($meal->season || $meal->route || $meal->menu_version)
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">📍 Menu Planning</h3>
            @if($meal->season)
            <div style="margin-bottom:14px;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Season</div>
                <div style="font-weight:600;color:#111827;font-size:15px;">{{ ucfirst($meal->season) }}</div>
            </div>
            @endif
            @if($meal->route)
            <div style="margin-bottom:14px;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Route</div>
                <div style="font-weight:600;color:#111827;font-size:15px;">✈️ {{ $meal->route }}</div>
            </div>
            @endif
            @if($meal->menu_version)
            <div>
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Menu Version</div>
                <div style="font-weight:600;color:#111827;font-size:15px;">{{ $meal->menu_version }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- Special Meal Badge -->
        @if($meal->is_special_meal)
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);text-align:center;color:white;margin-bottom:24px;">
            <svg style="width:48px;height:48px;margin:0 auto 16px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <h4 style="font-weight:700;margin-bottom:12px;font-size:18px;">⭐ Special Meal</h4>
            @if($meal->special_requirements)
                <p style="font-size:14px;opacity:0.95;line-height:1.5;">{{ $meal->special_requirements }}</p>
            @endif
        </div>
        @endif

        <!-- Effective Period -->
        @if($meal->effective_start_date || $meal->effective_end_date)
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">📅 Effective Period</h3>
            @if($meal->effective_start_date)
            <div style="margin-bottom:10px;">
                <span style="color:#6b7280;font-size:14px;">From:</span>
                <span style="font-weight:600;color:#111827;margin-left:8px;">{{ $meal->effective_start_date->format('M d, Y') }}</span>
            </div>
            @endif
            @if($meal->effective_end_date)
            <div>
                <span style="color:#6b7280;font-size:14px;">Until:</span>
                <span style="font-weight:600;color:#111827;margin-left:8px;">{{ $meal->effective_end_date->format('M d, Y') }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
