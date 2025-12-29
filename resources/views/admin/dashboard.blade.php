@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('content')
<div style="padding: 32px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin-bottom: 8px;">
            👋 Welcome, {{ auth()->user()->name }}
        </h1>
        <p style="color: #718096; font-size: 16px;">System Administrator Dashboard</p>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        @php
            $totalUsers = \App\Models\User::count();
            $totalProducts = \App\Models\Product::count();
            $pendingRequests = \App\Models\CateringRequest::where('status', 'pending')->count();
            $todayActivity = \Spatie\Activitylog\Models\Activity::whereDate('created_at', today())->count();
        @endphp

        <!-- Total Users -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="font-size: 40px;">👥</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $totalUsers }}</div>
            </div>
            <div style="font-size: 14px; font-weight: 600; opacity: 0.9;">Total Users</div>
        </div>

        <!-- Total Products -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="font-size: 40px;">📦</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $totalProducts }}</div>
            </div>
            <div style="font-size: 14px; font-weight: 600; opacity: 0.9;">Total Products</div>
        </div>

        <!-- Pending Requests -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="font-size: 40px;">⏳</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $pendingRequests }}</div>
            </div>
            <div style="font-size: 14px; font-weight: 600; opacity: 0.9;">Pending Requests</div>
        </div>

        <!-- Today's Activity -->
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 4px 12px rgba(67, 233, 123, 0.3);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <div style="font-size: 40px;">📊</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $todayActivity }}</div>
            </div>
            <div style="font-size: 14px; font-weight: 600; opacity: 0.9;">Today's Activity</div>
        </div>
    </div>

    <!-- Primary Actions -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #1a202c; margin-bottom: 20px;">🚀 Quick Actions</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
            <!-- Users Management -->
            @can('manage users')
            <a href="{{ route('admin.users.index') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(102,126,234,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">👥</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Manage Users</div>
                        <div style="font-size:12px;opacity:0.9;">Create and manage user accounts</div>
                    </div>
                    <div style="font-size:20px;">→</div>
                </div>
            </a>
            @endcan

            <!-- Roles & Permissions -->
            @can('manage roles')
            <a href="{{ route('admin.roles.index') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(240,147,251,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">🔐</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Roles & Permissions</div>
                        <div style="font-size:12px;opacity:0.9;">Configure access control</div>
                    </div>
                    <div style="font-size:20px;">→</div>
                </div>
            </a>
            @endcan

            <!-- Products Management -->
            @can('manage products')
            <a href="{{ route('admin.products.index') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(79,172,254,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">📦</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Manage Products</div>
                        <div style="font-size:12px;opacity:0.9;">View and configure products</div>
                    </div>
                    <div style="font-size:20px;">→</div>
                </div>
            </a>
            @endcan

            <!-- Categories -->
            @can('manage categories')
            <a href="{{ route('admin.categories.index') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(67,233,123,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">🏷️</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Categories</div>
                        <div style="font-size:12px;opacity:0.9;">Manage product categories</div>
                    </div>
                    <div style="font-size:20px;">→</div>
                </div>
            </a>
            @endcan

            <!-- Flights -->
            @can('manage flights')
            <a href="{{ route('admin.flights.index') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#fa709a 0%,#fee140 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(250,112,154,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">✈️</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;">Manage Flights</div>
                        <div style="font-size:12px;opacity:0.9;">Configure flight schedules</div>
                    </div>
                    <div style="font-size:20px;">→</div>
                </div>
            </a>
            @endcan

            <!-- Requests -->
            @can('view all requests')
            <a href="{{ route('admin.requests.pending') }}" 
               style="text-decoration:none;background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%);border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(168,237,234,0.4)'" 
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="font-size:36px;">📋</div>
                    <div style="flex:1;">
                        <div style="font-size:16px;font-weight:700;margin-bottom:4px;color:#1a202c;">View Requests</div>
                        <div style="font-size:12px;opacity:0.7;color:#4a5568;">Monitor catering requests</div>
                    </div>
                    <div style="font-size:20px;color:#1a202c;">→</div>
                </div>
            </a>
            @endcan
        </div>
    </div>

    <!-- System Management (Dynamic Permissions) -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #1a202c; margin-bottom: 20px;">⚙️ System Management</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
            <!-- Dynamic permission-based actions will appear here automatically -->
            <x-permission-actions :exclude="['manage users', 'manage roles', 'manage products', 'manage categories', 'manage flights', 'view all requests', 'manage permissions', 'create catering request', 'view approved requests']" />
        </div>
    </div>

    <!-- Recent Activity Section (Optional) -->
    @can('view activity logs')
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #1a202c; margin: 0;">📊 Recent System Activity</h2>
            <a href="{{ route('admin.activity-logs.index') }}" style="color: #667eea; font-weight: 600; text-decoration: none; font-size: 14px;">View All →</a>
        </div>
        @php
            $recentActivities = \Spatie\Activitylog\Models\Activity::latest()->take(5)->get();
        @endphp
        
        @if($recentActivities->count() > 0)
        <div style="space-y: 12px;">
            @foreach($recentActivities as $activity)
            <div style="padding: 16px; background: #f8f9fa; border-radius: 10px; margin-bottom: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #1a202c; margin-bottom: 4px;">
                            {{ $activity->description ?? 'Activity' }}
                        </div>
                        <div style="font-size: 13px; color: #6c757d;">
                            By: {{ $activity->causer->name ?? 'System' }}
                        </div>
                    </div>
                    <div style="font-size: 12px; color: #adb5bd;">
                        {{ $activity->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color: #6c757d; text-align: center; padding: 20px;">No recent activity</p>
        @endif
    </div>
    @endcan
</div>
@endsection
