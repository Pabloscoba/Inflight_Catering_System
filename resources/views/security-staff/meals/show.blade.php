@extends('layouts.app')

@section('title', 'Authenticate Meal - ' . $meal->name)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Authenticate: {{ $meal->name }}</h1>
            <p>Verify meal details before authentication</p>
        </div>
        <a href="{{ route('security-staff.meals.index') }}" 
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
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Approved Date</div>
                    <div style="font-weight:600;color:#111827;">{{ $meal->approved_at ? $meal->approved_at->format('M d, Y H:i') : 'N/A' }}</div>
                </div>
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
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Authentication Action -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:20px;">🔐 Authentication</h3>
            
            <button type="button" onclick="showAuthMealConfirmation({{ $meal->id }})"
                    style="width:100%;background:#10b981;color:white;padding:14px;border-radius:10px;border:none;font-weight:600;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Authenticate Meal
            </button>
            <form id="auth-meal-form-{{ $meal->id }}" action="{{ route('security-staff.meals.authenticate', $meal) }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

        <!-- Approval Info -->
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:24px;">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">Approval Details</h3>
            <div style="margin-bottom:12px;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Approved By</div>
                <div style="font-weight:600;color:#111827;">{{ $meal->approvedBy->name ?? 'N/A' }}</div>
            </div>
            <div>
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Date Approved</div>
                <div style="font-weight:600;color:#111827;">{{ $meal->approved_at ? $meal->approved_at->format('M d, Y H:i') : 'N/A' }}</div>
            </div>
        </div>

        @if($meal->route || $meal->season)
        <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:16px;">Menu Planning</h3>
            @if($meal->route)
            <div style="margin-bottom:12px;">
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Route</div>
                <div style="font-weight:600;color:#111827;">{{ $meal->route }}</div>
            </div>
            @endif
            @if($meal->season)
            <div>
                <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Season</div>
                <div style="font-weight:600;color:#111827;">{{ ucfirst($meal->season) }}</div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
function showAuthMealConfirmation(mealId) {
    const confirmDiv = document.createElement('div');
    confirmDiv.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:28px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:10000;max-width:450px;width:90%;';
    confirmDiv.innerHTML = `
        <h3 style="margin:0 0 16px 0;font-size:20px;font-weight:700;color:#1a202c;">Authenticate Meal?</h3>
        <div style="color:#4a5568;font-size:15px;line-height:1.6;margin-bottom:20px;">
            <p style="margin:0 0 8px 0;">Una uhakika unataka kuthibitisha meal hii?</p>
            <p style="margin:0;">Itapelekwa kwa Ramp Dispatcher kwa ajili ya dispatch.</p>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button onclick="closeAuthMealModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="submitAuthMealForm(${mealId})" style="background:#10b981;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">✓ Authenticate</button>
        </div>
    `;
    
    const overlay = document.createElement('div');
    overlay.id = 'auth-meal-modal-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;';
    overlay.onclick = closeAuthMealModal;
    
    document.body.appendChild(overlay);
    document.body.appendChild(confirmDiv);
    window.currentAuthMealConfirmDiv = confirmDiv;
}

function closeAuthMealModal() {
    const overlay = document.getElementById('auth-meal-modal-overlay');
    if (overlay) overlay.remove();
    if (window.currentAuthMealConfirmDiv) window.currentAuthMealConfirmDiv.remove();
}

function submitAuthMealForm(mealId) {
    closeAuthMealModal();
    document.getElementById('auth-meal-form-' + mealId).submit();
}
</script>

@endsection
