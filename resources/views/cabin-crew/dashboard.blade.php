@extends('layouts.app')

@section('title', 'Cabin Crew Dashboard')

@section('content')
<div class="content-header">
    <h1>🛫 Cabin Crew Dashboard</h1>
</div>

<!-- Notifications/Alerts -->
@if($urgentFlights->count() > 0)
<div style="background:linear-gradient(135deg,#ff6b6b 0%,#ee5a6f 100%);border-radius:16px;padding:20px 24px;margin-bottom:24px;color:white;box-shadow:0 4px 12px rgba(255,107,107,0.3);">
    <div style="display:flex;align-items:center;gap:16px;">
        <div style="font-size:32px;">⚠️</div>
        <div style="flex:1;">
            <h3 style="margin:0 0 4px 0;font-size:18px;font-weight:700;">Urgent: {{ $urgentFlights->count() }} Flight(s) Departing Soon!</h3>
            <p style="margin:0;font-size:14px;opacity:0.95;">Flights departing within 3 hours require immediate attention</p>
        </div>
        <div style="background:rgba(255,255,255,0.2);padding:8px 20px;border-radius:12px;font-weight:700;font-size:16px;">
            {{ $pendingItems }} Items Pending
        </div>
    </div>
</div>
@endif

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
    <!-- Items To Receive -->
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📦</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $loadedRequests }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Requests To Receive</div>
        </div>
    </div>

    <!-- Today's Service -->
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">🍽️</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $todayServed }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Served Today</div>
        </div>
    </div>

    <!-- Active Flights -->
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">✈️</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $activeFlightsCount }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Active Flights Today</div>
        </div>
    </div>

    <!-- Total Items Served -->
    <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:16px;align-items:center;">
        <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span style="font-size:28px;">📊</span>
        </div>
        <div style="flex:1;">
            <div style="font-size:28px;font-weight:700;color:#1a202c;line-height:1;">{{ $todayItemsServed }}</div>
            <div style="font-size:13px;color:#718096;margin-top:4px;">Items Served Today</div>
        </div>
    </div>
</div>

<!-- Flight Overview Section -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">✈️ Flight Overview</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Today's flights and upcoming schedule</p>
        </div>
    </div>
    
    @if($todayFlights->count() > 0)
    <div style="margin-bottom:20px;">
        <h3 style="font-size:16px;font-weight:600;color:#2d3748;margin:0 0 12px 0;">📅 Today's Flights</h3>
        <div style="display:grid;gap:12px;">
            @foreach($todayFlights as $flight)
            <div style="background:#f7fafc;border-left:4px solid #4299e1;padding:16px;border-radius:8px;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;">
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;color:#2d3748;">{{ $flight->flight_number }}</div>
                        <div style="font-size:14px;color:#4a5568;margin-top:4px;">
                            <span style="font-weight:600;">{{ $flight->origin }}</span> → <span style="font-weight:600;">{{ $flight->destination }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:14px;font-weight:600;color:#2d3748;">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i A') }}</div>
                        <div style="font-size:12px;color:#718096;margin-top:2px;">{{ \Carbon\Carbon::parse($flight->departure_time)->diffForHumans() }}</div>
                    </div>
                    @if($flight->requests->count() > 0)
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                            {{ $flight->requests->count() }} Requests
                        </span>
                        @foreach($flight->requests as $req)
                        <a href="{{ route('cabin-crew.products.view', $req) }}" style="background:#4299e1;color:white;padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;text-decoration:none;white-space:nowrap;">
                            View #{{ $req->id }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($upcomingFlights->count() > 0)
    <div>
        <h3 style="font-size:16px;font-weight:600;color:#2d3748;margin:0 0 12px 0;">🔜 Upcoming Flights (Next 3 Days)</h3>
        <div style="display:grid;gap:10px;">
            @foreach($upcomingFlights as $flight)
            <div style="background:#fffbeb;border-left:4px solid #f59e0b;padding:14px;border-radius:8px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-weight:700;color:#2d3748;">{{ $flight->flight_number }}</span>
                    <span style="color:#718096;margin:0 8px;">•</span>
                    <span style="color:#4a5568;">{{ $flight->origin }} → {{ $flight->destination }}</span>
                </div>
                <div style="font-size:13px;color:#92400e;font-weight:600;">
                    {{ \Carbon\Carbon::parse($flight->departure_time)->format('M d, H:i') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:12px;">📅</div>
        <div style="font-size:14px;">No upcoming flights in the next 3 days</div>
    </div>
    @endif
</div>

<!-- Items Received Summary -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="margin-bottom:20px;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📋 Items Received Summary</h2>
        <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Breakdown by product category</p>
    </div>
    
    @if($itemsReceivedSummary->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
        @foreach($itemsReceivedSummary as $summary)
        <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:20px;color:white;position:relative;overflow:hidden;cursor:pointer;transition:transform 0.2s;" onclick="window.location='{{ route('cabin-crew.usage.index') }}'" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="position:absolute;top:-10px;right:-10px;font-size:60px;opacity:0.15;">📦</div>
            <div style="position:relative;z-index:1;">
                <div style="font-size:13px;opacity:0.9;margin-bottom:8px;font-weight:600;">{{ $summary['category'] }}</div>
                <div style="font-size:32px;font-weight:700;margin-bottom:4px;">{{ $summary['total_items'] }}</div>
                <div style="font-size:12px;opacity:0.85;margin-bottom:12px;">
                    {{ $summary['unique_products'] }} products
                    @if($summary['meal_items'] > 0)
                        • {{ $summary['meal_items'] }} meals
                    @endif
                </div>
                <div style="font-size:11px;opacity:0.9;border-top:1px solid rgba(255,255,255,0.3);padding-top:8px;">
                    Click to view details →
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:12px;">📦</div>
        <div style="font-size:14px;">No items received yet</div>
    </div>
    @endif
</div>

<!-- Products Available by Meal Type -->
<div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="margin-bottom:20px;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">🍽️ Products Available</h2>
        <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Breakdown by meal type and service category</p>
    </div>
    
    @if($productsAvailable->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;">
        @foreach($productsAvailable as $product)
        @php
            $mealBadges = [
                'breakfast' => ['bg' => 'linear-gradient(135deg,#fef3c7 0%,#fde68a 100%)', 'color' => '#92400e', 'icon' => '🍳', 'label' => 'Breakfast'],
                'lunch' => ['bg' => 'linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%)', 'color' => '#1e40af', 'icon' => '🍽️', 'label' => 'Lunch'],
                'dinner' => ['bg' => 'linear-gradient(135deg,#e0e7ff 0%,#c7d2fe 100%)', 'color' => '#3730a3', 'icon' => '🌙', 'label' => 'Dinner'],
                'snack' => ['bg' => 'linear-gradient(135deg,#fce7f3 0%,#fbcfe8 100%)', 'color' => '#9f1239', 'icon' => '🍪', 'label' => 'Snacks'],
                'VIP_meal' => ['bg' => 'linear-gradient(135deg,#f3e8ff 0%,#e9d5ff 100%)', 'color' => '#6b21a8', 'icon' => '👑', 'label' => 'VIP Meals'],
                'special_meal' => ['bg' => 'linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%)', 'color' => '#065f46', 'icon' => '⭐', 'label' => 'Special'],
                'non_meal' => ['bg' => 'linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%)', 'color' => '#374151', 'icon' => '📦', 'label' => 'Non-Meal']
            ];
            $badge = $mealBadges[$product['meal_type']] ?? $mealBadges['non_meal'];
        @endphp
        <div style="background:{{ $badge['bg'] }};border-radius:12px;padding:16px;text-align:center;cursor:pointer;transition:transform 0.2s;box-shadow:0 2px 4px rgba(0,0,0,0.1);" onclick="window.location='{{ route('cabin-crew.meals.index') }}?meal_type={{ $product['meal_type'] }}'" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div style="font-size:32px;margin-bottom:8px;">{{ $badge['icon'] }}</div>
            <div style="font-size:24px;font-weight:700;color:{{ $badge['color'] }};margin-bottom:4px;">{{ $product['count'] }}</div>
            <div style="font-size:12px;color:{{ $badge['color'] }};font-weight:600;opacity:0.9;">{{ $badge['label'] }}</div>
            <div style="font-size:11px;color:{{ $badge['color'] }};opacity:0.75;margin-top:4px;">{{ $product['products'] }} types</div>
            <div style="font-size:10px;color:{{ $badge['color'] }};opacity:0.8;margin-top:6px;border-top:1px solid {{ $badge['color'] }};padding-top:6px;">
                Click to view →
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:12px;">🍽️</div>
        <div style="font-size:14px;">No products available</div>
    </div>
    @endif
</div>

<!-- Live Usage Summary -->
<div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:24px;margin-bottom:24px;color:white;box-shadow:0 4px 12px rgba(102,126,234,0.4);">
    <div style="margin-bottom:20px;">
        <h2 style="font-size:20px;font-weight:700;margin:0;">📊 Live Usage Summary</h2>
        <p style="opacity:0.9;font-size:14px;margin:4px 0 0 0;">Real-time service statistics for today</p>
    </div>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
        <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;backdrop-filter:blur(10px);">
            <div style="font-size:36px;font-weight:700;margin-bottom:4px;">{{ $todayServed }}</div>
            <div style="font-size:14px;opacity:0.9;">Requests Completed</div>
        </div>
        <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;backdrop-filter:blur(10px);">
            <div style="font-size:36px;font-weight:700;margin-bottom:4px;">{{ $todayItemsServed }}</div>
            <div style="font-size:14px;opacity:0.9;">Total Items Served</div>
        </div>
        <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;backdrop-filter:blur(10px);">
            <div style="font-size:36px;font-weight:700;margin-bottom:4px;">{{ $activeFlightsCount }}</div>
            <div style="font-size:14px;opacity:0.9;">Active Flights</div>
        </div>
        <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:20px;backdrop-filter:blur(10px);">
            <div style="font-size:36px;font-weight:700;margin-bottom:4px;">{{ $loadedRequests }}</div>
            <div style="font-size:14px;opacity:0.9;">Pending Service</div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="margin-bottom:24px;">
        <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">⚡ Quick Actions</h2>
        <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Common tasks and operations</p>
    </div>
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;">
        <!-- Create Products (Only if has permission) -->
        @can('create products')
        <a href="{{ route('cabin-crew.products.create') }}" style="text-decoration:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(102,126,234,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">➕</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Add Product</div>
                    <div style="font-size:12px;opacity:0.9;">Create new product</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        @endcan
        
        <!-- Record Usage -->
        <a href="{{ route('cabin-crew.usage.index') }}" style="text-decoration:none;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(79,172,254,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">📊</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Record Usage</div>
                    <div style="font-size:12px;opacity:0.9;">Track product consumption</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        
        <!-- Return Items -->
        <a href="{{ route('cabin-crew.returns.index') }}" style="text-decoration:none;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(240,147,251,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">↩️</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Return Items</div>
                    <div style="font-size:12px;opacity:0.9;">Process unused products</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        
        <!-- View Meals -->
        <a href="{{ route('cabin-crew.meals.index') }}" style="text-decoration:none;background:linear-gradient(135deg,#fad961 0%,#f76b1c 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(250,217,97,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">🍽️</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">View Meals</div>
                    <div style="font-size:12px;opacity:0.9;">Browse meal catalog</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        
        <!-- Delivered History -->
        <a href="{{ route('cabin-crew.delivered') }}" style="text-decoration:none;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(67,233,123,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">✅</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Service History</div>
                    <div style="font-size:12px;opacity:0.9;">View completed services</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        
        <!-- DYNAMIC PERMISSION-BASED ACTIONS (Auto-appear when permissions added) -->
        <x-permission-actions :exclude="['receive goods from dispatcher', 'record items used during flight', 'record remaining items', 'submit usage report']" />
    </div>
</div>

<!-- Record Usage & Return Items Section -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📝 Record Usage & Returns</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Manage product usage and return unused items</p>
        </div>
    </div>
    
    @if($requestsToReceive->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;">
        <div style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:12px;padding:24px;color:white;">
            <div style="font-size:40px;margin-bottom:12px;">📊</div>
            <h3 style="margin:0 0 8px 0;font-size:18px;font-weight:700;">Record Usage</h3>
            <p style="margin:0 0 16px 0;font-size:13px;opacity:0.9;">Track products used during service</p>
            <a href="{{ route('cabin-crew.usage.index') }}" style="display:inline-block;background:rgba(255,255,255,0.25);color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;backdrop-filter:blur(10px);">
                View Usage Records →
            </a>
        </div>
        
        <div style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:12px;padding:24px;color:white;">
            <div style="font-size:40px;margin-bottom:12px;">↩️</div>
            <h3 style="margin:0 0 8px 0;font-size:18px;font-weight:700;">Return Items</h3>
            <p style="margin:0 0 16px 0;font-size:13px;opacity:0.9;">Return unused products after flight</p>
            <a href="{{ route('cabin-crew.returns.index') }}" style="display:inline-block;background:rgba(255,255,255,0.25);color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;backdrop-filter:blur(10px);">
                Manage Returns →
            </a>
        </div>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:12px;">📝</div>
        <div style="font-size:14px;">No active requests to record usage or returns</div>
    </div>
    @endif
</div>

<!-- Requests Loaded onto Aircraft -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">✈️ Supplies Loaded onto Aircraft</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Ready to serve to passengers during flight</p>
        </div>
        @if($requestsToReceive->count() > 0)
        <span style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:6px 16px;border-radius:20px;font-weight:600;font-size:14px;">
            {{ $requestsToReceive->count() }}
        </span>
        @endif
    </div>

    @if($requestsToReceive->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight Details</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Route</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Departure</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Items</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Loaded At</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestsToReceive as $request)
                <tr style="border-bottom:1px solid #e2e8f0;transition:background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <td style="padding:16px;font-weight:600;color:#2d3748;">
                        <a href="{{ route('catering-staff.requests.show', $request) }}" style="color:#667eea;text-decoration:none;font-weight:700;">#{{ $request->id }}</a>
                    </td>
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $request->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $request->flight->airline }}</div>
                    </td>
                    <td style="padding:16px;font-size:14px;color:#4a5568;">
                        <span style="font-weight:600;">{{ $request->flight->origin }}</span>
                        <span style="color:#cbd5e0;margin:0 4px;">→</span>
                        <span style="font-weight:600;">{{ $request->flight->destination }}</span>
                    </td>
                    <td style="padding:16px;">
                        @php
                            $departureTime = \Carbon\Carbon::parse($request->flight->departure_time);
                            $hoursUntilDeparture = now()->diffInHours($departureTime, false);
                        @endphp
                        <div style="font-weight:600;color:#2d3748;font-size:14px;">{{ $departureTime->format('M d, H:i') }}</div>
                        @if($hoursUntilDeparture < 24 && $hoursUntilDeparture > 0)
                            <div style="font-size:12px;color:#e53e3e;font-weight:600;margin-top:2px;">⚠️ {{ round($hoursUntilDeparture) }}h remaining</div>
                        @elseif($hoursUntilDeparture > 0)
                            <div style="font-size:12px;color:#38a169;margin-top:2px;">{{ round($hoursUntilDeparture) }}h ahead</div>
                        @endif
                    </td>
                    <td style="padding:16px;">
                        @php
                            $itemsPreview = $request->items->take(2);
                            $remainingCount = $request->items->count() - 2;
                        @endphp
                        <div style="font-size:13px;color:#4a5568;">
                            @foreach($itemsPreview as $item)
                                <div style="margin-bottom:2px;">• {{ $item->product->name }} ({{ $item->quantity }})</div>
                            @endforeach
                            @if($remainingCount > 0)
                                <div style="color:#667eea;font-weight:600;font-size:12px;margin-top:4px;">+{{ $remainingCount }} more</div>
                            @endif
                        </div>
                    </td>
                    <td style="padding:16px;font-size:13px;color:#718096;">
                        {{ $request->loaded_at ? \Carbon\Carbon::parse($request->loaded_at)->format('M d, H:i') : 'N/A' }}
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <a href="{{ route('cabin-crew.products.view', $request) }}" style="background:#4299e1;color:white;border:none;padding:8px 16px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;display:inline-block;">
                                👁️ View Items
                            </a>
                            <a href="{{ route('cabin-crew.served.form', $request) }}" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;display:inline-block;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(67,233,123,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                                🍽️ Mark as Served
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">📦</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Requests to Receive</div>
        <div style="font-size:14px;color:#a0aec0;">All loaded requests have been delivered</div>
    </div>
    @endif
</div>

<!-- Final Flight Report Section -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">📄 Final Flight Report</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Complete service summary and reports</p>
        </div>
    </div>
    
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:24px;color:white;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="flex:1;">
                <h3 style="margin:0 0 8px 0;font-size:18px;font-weight:700;">Generate Service Report</h3>
                <p style="margin:0;font-size:14px;opacity:0.9;">Create comprehensive report of today's service activities</p>
            </div>
            <div>
                <button onclick="window.print()" style="background:rgba(255,255,255,0.25);color:white;border:none;padding:12px 24px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;backdrop-filter:blur(10px);">
                    📄 Generate Report
                </button>
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;margin-top:20px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.2);">
            <div>
                <div style="font-size:12px;opacity:0.8;margin-bottom:4px;">Total Flights</div>
                <div style="font-size:24px;font-weight:700;">{{ $totalFlights }}</div>
            </div>
            <div>
                <div style="font-size:12px;opacity:0.8;margin-bottom:4px;">Requests Served</div>
                <div style="font-size:24px;font-weight:700;">{{ $deliveredRequests }}</div>
            </div>
            <div>
                <div style="font-size:12px;opacity:0.8;margin-bottom:4px;">Items Delivered</div>
                <div style="font-size:24px;font-weight:700;">{{ $todayItemsServed }}</div>
            </div>
            <div>
                <div style="font-size:12px;opacity:0.8;margin-bottom:4px;">Service Rate</div>
                <div style="font-size:24px;font-weight:700;">
                    {{ $deliveredRequests > 0 ? number_format(($deliveredRequests / max($deliveredRequests + $loadedRequests, 1)) * 100, 0) : 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recently Delivered Requests -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">✅ Recently Delivered Requests</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Service completion history</p>
        </div>
    </div>

    @if($deliveredRequestsList->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Items Count</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Delivered At</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveredRequestsList as $request)
                <tr style="border-bottom:1px solid #e2e8f0;transition:background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='white'">
                    <td style="padding:16px;font-weight:700;color:#667eea;">
                        <a href="{{ route('catering-staff.requests.show', $request) }}" style="color:#667eea;text-decoration:none;">#{{ $request->id }}</a>
                    </td>
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $request->flight->flight_number }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
                    </td>
                    <td style="padding:16px;font-weight:600;color:#2d3748;">
                        {{ $request->items->count() }} items
                    </td>
                    <td style="padding:16px;color:#718096;font-size:13px;">
                        {{ $request->delivered_at ? \Carbon\Carbon::parse($request->delivered_at)->format('M d, H:i') : 'N/A' }}
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                            Delivered
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:40px 20px;color:#a0aec0;">
        <div style="font-size:14px;">No delivered requests yet</div>
    </div>
    @endif
</div>
@endsection
