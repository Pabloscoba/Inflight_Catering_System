@extends('layouts.app')

@section('title', 'Review Meal - ' . $meal->name)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Review Meal: {{ $meal->name }}</h1>
            <p>Review meal details and approve or reject</p>
        </div>
        <a href="{{ route('catering-incharge.meals.index') }}" 
           style="display:inline-flex;align-items:center;gap:8px;background:#6b7280;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;">
    <!-- Main Content -->
    <div>
        <!-- Meal Photo & Details -->
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            @if($meal->photo)
                <div style="margin-bottom:24px;border-radius:12px;overflow:hidden;max-height:400px;">
                    <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" style="width:100%;height:auto;object-fit:cover;">
                </div>
            @else
                <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;height:300px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
                    <svg style="width:80px;height:80px;color:white;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            @endif

            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <h2 style="font-size:28px;font-weight:700;color:#111827;margin:0;">{{ $meal->name }}</h2>
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
                <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;">
                    {{ $badge['icon'] }} {{ ucfirst(str_replace('_', ' ', $meal->meal_type)) }}
                </span>
            </div>

            @if($meal->description)
                <p style="color:#6b7280;margin-bottom:24px;line-height:1.6;">{{ $meal->description }}</p>
            @endif

            <!-- Basic Info Grid -->
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:28px;padding:20px;background:#f9fafb;border-radius:12px;">
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">SKU</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->sku }}</div>
                </div>
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Category</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->category->name ?? 'N/A' }}</div>
                </div>
                @if($meal->portion_size)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Portion Size</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->portion_size }}</div>
                </div>
                @endif
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Date Created</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>

            <!-- Recipe Section -->
            @if($meal->ingredients || $meal->allergen_info)
            <div style="margin-bottom:28px;">
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <svg style="width:22px;height:22px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Recipe & Ingredients
                </h3>
                @if($meal->ingredients)
                    <div style="margin-bottom:16px;">
                        <div style="font-weight:600;color:#374151;margin-bottom:8px;">Ingredients:</div>
                        <p style="color:#6b7280;line-height:1.6;white-space:pre-line;">{{ $meal->ingredients }}</p>
                    </div>
                @endif
                @if($meal->allergen_info)
                    <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:16px;border-radius:8px;">
                        <div style="font-weight:600;color:#92400e;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Allergen Information:
                        </div>
                        <p style="color:#92400e;">{{ $meal->allergen_info }}</p>
                    </div>
                @endif
            </div>
            @endif

            <!-- Preparation Instructions -->
            @if($meal->preparation_instructions)
            <div>
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <svg style="width:22px;height:22px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Preparation Instructions
                </h3>
                <p style="color:#6b7280;line-height:1.8;white-space:pre-line;">{{ $meal->preparation_instructions }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div>
        <!-- Approval Actions -->
        @if($meal->status === 'pending')
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:20px;">Approval Decision</h3>
            
            <!-- Approve Button -->
            <form action="{{ route('catering-incharge.meals.approve', $meal) }}" method="POST" style="margin-bottom:12px;">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to approve this meal?')"
                        style="width:100%;background:#10b981;color:white;padding:14px;border-radius:10px;border:none;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Meal
                </button>
            </form>

            <!-- Reject Button & Form -->
            <button onclick="document.getElementById('rejectForm').style.display='block';this.style.display='none';"
                    style="width:100%;background:#ef4444;color:white;padding:14px;border-radius:10px;border:none;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reject Meal
            </button>

            <form id="rejectForm" action="{{ route('catering-incharge.meals.reject', $meal) }}" method="POST" style="display:none;margin-top:16px;">
                @csrf
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;">Rejection Reason:</label>
                <textarea name="rejection_reason" required
                          style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;margin-bottom:12px;resize:vertical;"
                          rows="4" placeholder="Enter reason for rejection..."></textarea>
                <div style="display:flex;gap:8px;">
                    <button type="submit" 
                            style="flex:1;background:#ef4444;color:white;padding:10px;border-radius:8px;border:none;font-weight:600;cursor:pointer;">
                        Submit Rejection
                    </button>
                    <button type="button" onclick="this.closest('form').style.display='none';this.closest('form').previousElementSibling.style.display='flex';"
                            style="flex:1;background:#6b7280;color:white;padding:10px;border-radius:8px;border:none;font-weight:600;cursor:pointer;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @else
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">Status</h3>
            <span style="background:#10b981;color:white;padding:8px 16px;border-radius:8px;font-weight:600;display:inline-block;">
                {{ ucfirst($meal->status) }}
            </span>
        </div>
        @endif

        <!-- Menu Planning Info -->
        @if($meal->season || $meal->route || $meal->menu_version)
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">Menu Planning</h3>
            @if($meal->season)
                <div style="margin-bottom:12px;">
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Season</div>
                    <div style="font-weight:600;color:#111827;">{{ ucfirst($meal->season) }}</div>
                </div>
            @endif
            @if($meal->route)
                <div style="margin-bottom:12px;">
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Route</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->route }}</div>
                </div>
            @endif
            @if($meal->menu_version)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Menu Version</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->menu_version }}</div>
                </div>
            @endif
        </div>
        @endif

        <!-- Special Meal Badge -->
        @if($meal->is_special_meal)
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);text-align:center;color:white;margin-bottom:24px;">
            <svg style="width:40px;height:40px;margin:0 auto 12px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <h4 style="font-weight:700;margin-bottom:8px;">Special Meal</h4>
            @if($meal->special_requirements)
                <p style="font-size:14px;opacity:0.9;">{{ $meal->special_requirements }}</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
