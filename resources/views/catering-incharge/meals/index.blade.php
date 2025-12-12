@extends('layouts.app')

@section('title', 'Pending Meals - Approval')

@section('content')
<div class="content-header">
    <div>
        <h1>Pending Meals for Approval</h1>
        <p>Review and approve or reject meals created by Catering Staff</p>
    </div>
</div>

@if($meals->isEmpty())
    <div style="background:white;border-radius:16px;padding:48px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg style="width:64px;height:64px;color:#9ca3af;margin:0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 style="color:#374151;margin-bottom:8px;">No Pending Meals</h3>
        <p style="color:#6b7280;">All meals have been reviewed.</p>
    </div>
@else
    <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                <tr>
                    <th style="padding:16px;text-align:left;font-weight:600;color:#374151;">Meal Name</th>
                    <th style="padding:16px;text-align:left;font-weight:600;color:#374151;">Category</th>
                    <th style="padding:16px;text-align:left;font-weight:600;color:#374151;">Meal Type</th>
                    <th style="padding:16px;text-align:left;font-weight:600;color:#374151;">Created By</th>
                    <th style="padding:16px;text-align:left;font-weight:600;color:#374151;">Date Created</th>
                    <th style="padding:16px;text-align:center;font-weight:600;color:#374151;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meals as $meal)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:16px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($meal->photo)
                                <img src="{{ asset('storage/' . $meal->photo) }}" alt="{{ $meal->name }}" 
                                     style="width:50px;height:50px;border-radius:8px;object-fit:cover;">
                            @else
                                <div style="width:50px;height:50px;border-radius:8px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;">
                                    <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:#111827;">{{ $meal->name }}</div>
                                <div style="font-size:13px;color:#6b7280;">{{ $meal->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:16px;color:#374151;">{{ $meal->category->name ?? 'N/A' }}</td>
                    <td style="padding:16px;">
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
                        <span style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;">
                            {{ $badge['icon'] }} {{ ucfirst(str_replace('_', ' ', $meal->meal_type)) }}
                        </span>
                    </td>
                    <td style="padding:16px;color:#374151;">{{ $meal->created_at->diffForHumans() }}</td>
                    <td style="padding:16px;color:#374151;">{{ $meal->created_at->format('M d, Y') }}</td>
                    <td style="padding:16px;text-align:center;">
                        <a href="{{ route('catering-incharge.meals.show', $meal) }}" 
                           style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:white;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:24px;">
        {{ $meals->links() }}
    </div>
@endif
@endsection
