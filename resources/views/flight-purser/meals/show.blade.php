@extends('layouts.app')

@section('title', 'Load Meal - ' . $meal->name)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Load: {{ $meal->name }}</h1>
            <p>Confirm loading meal onto the aircraft</p>
        </div>
        <a href="{{ route('flight-purser.meals.index') }}" 
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
        <div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            @if($meal->photo)
                <div style="margin-bottom:24px;border-radius:12px;overflow:hidden;max-height:400px;">
                    <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" style="width:100%;height:auto;object-fit:cover;">
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
                @if($meal->route)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Route</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->route }}</div>
                </div>
                @endif
            </div>

            @if($meal->ingredients)
            <div style="margin-bottom:24px;">
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">Ingredients</h3>
                <p style="color:#6b7280;line-height:1.6;white-space:pre-line;">{{ $meal->ingredients }}</p>
            </div>
            @endif

            @if($meal->allergen_info)
            <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:16px;border-radius:8px;margin-bottom:24px;">
                <div style="font-weight:600;color:#92400e;margin-bottom:8px;">⚠️ Allergen Information:</div>
                <p style="color:#92400e;">{{ $meal->allergen_info }}</p>
            </div>
            @endif

            @if($meal->preparation_instructions)
            <div>
                <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">Preparation Instructions</h3>
                <p style="color:#6b7280;line-height:1.6;white-space:pre-line;">{{ $meal->preparation_instructions }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Load Action -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:20px;">✈️ Load onto Aircraft</h3>
            
            <form action="{{ route('flight-purser.meals.receive', $meal) }}" method="POST" id="load-meal-form">
                @csrf
                <button type="button" onclick="showLoadMealConfirmation()"
                        style="width:100%;background:#10b981;color:white;padding:14px;border-radius:10px;border:none;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Confirm Load
                </button>
            </form>
        </div>

        <!-- Workflow Trail -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">📋 Workflow Trail</h3>
            
            @if($meal->approved_by)
            <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #e5e7eb;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">✅ Approved By</div>
                <div style="font-weight:600;color:#111827;font-size:14px;">{{ $meal->approvedBy->name ?? 'N/A' }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $meal->approved_at ? $meal->approved_at->format('M d, Y H:i') : '' }}</div>
            </div>
            @endif

            @if($meal->authenticated_by)
            <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #e5e7eb;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">🔐 Authenticated By</div>
                <div style="font-weight:600;color:#111827;font-size:14px;">{{ $meal->authenticatedBy->name ?? 'N/A' }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $meal->authenticated_at ? $meal->authenticated_at->format('M d, Y H:i') : '' }}</div>
            </div>
            @endif

            @if($meal->dispatched_by)
            <div>
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">🚚 Dispatched By</div>
                <div style="font-weight:600;color:#111827;font-size:14px;">{{ $meal->dispatchedBy->name ?? 'N/A' }}</div>
                <div style="font-size:12px;color:#6b7280;">{{ $meal->dispatched_at ? $meal->dispatched_at->format('M d, Y H:i') : '' }}</div>
            </div>
            @endif
        </div>

        <!-- Special Meal Badge -->
        @if($meal->is_special_meal)
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);text-align:center;color:white;">
            <svg style="width:40px;height:40px;margin:0 auto 12px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <h4 style="font-weight:700;margin-bottom:8px;">⭐ Special Meal</h4>
            @if($meal->special_requirements)
                <p style="font-size:14px;opacity:0.9;">{{ $meal->special_requirements }}</p>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Load Meal Confirmation Modal --}}
<div id="loadMealModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2)">
        <h3 style="margin:0 0 12px;font-size:18px;font-weight:700">✈️ Confirm Load</h3>
        <p style="color:#6b7280;margin:0 0 20px">Confirm loading this meal onto the aircraft?</p>
        <div style="display:flex;gap:12px;justify-content:flex-end">
            <button onclick="closeLoadMealModal()" style="padding:10px 20px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Cancel
            </button>
            <button onclick="submitLoadMealForm()" style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Confirm Load
            </button>
        </div>
    </div>
</div>

<script>
    function showLoadMealConfirmation() {
        document.getElementById('loadMealModal').style.display = 'flex';
    }

    function closeLoadMealModal() {
        document.getElementById('loadMealModal').style.display = 'none';
    }

    function submitLoadMealForm() {
        document.getElementById('load-meal-form').submit();
    }
</script>

@endsection
