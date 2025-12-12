<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inflight Catering System') }} - @yield('page-title', 'Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        a { text-decoration: none; cursor: pointer; }
        
        /* Sidebar */
        .sidebar { width: 240px; background: #0b1a68; border-right: 1px solid #1a2980; overflow-y: auto; overflow-x: hidden; display: flex; flex-direction: column; }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: #0b1a68; }
        .sidebar::-webkit-scrollbar-thumb { background: #1a2980; border-radius: 3px; }
        .sidebar .logo-row { height: 64px; display: flex; align-items: center; justify-content: center; background: #0b1a68; color: #fff; border-bottom: 1px solid #1a2980; position: sticky; top: 0; z-index: 10; }
        .sidebar .profile { padding: 0.8rem 1rem; border-bottom: 1px solid #1a2980; background: #0b1a68; }
        .sidebar-link { display: flex; align-items: center; gap: .75rem; padding: 12px 15px; margin-bottom: 8px; color: #fff; border-radius: 6px; transition: all 0.15s ease; cursor: pointer; background: transparent; border: none; width: 100%; text-align: left; font-size: 14px; }
        .sidebar-link:hover { background: #1a2980; color: #fff; cursor: pointer; }
        .sidebar-link.active { background: #1a2980; color: #fff; border-right: 4px solid #4dabf7; }
        .sidebar-submenu { padding-left: 30px; max-height: 0; overflow: hidden; transition: max-height 0.3s ease; background: #0b1a68; }
        .sidebar-submenu a { display: block; padding: 10px 15px; font-size: 13px; color: #a8b8ff; border-radius: 6px; }
        .sidebar-submenu a:hover { color: #fff; background: #1a2980; }
        .sidebar-submenu a.active { color: #fff; background: #1a2980; }
        
        /* Main content */
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { height: 60px; background: #fff; border-bottom: 1px solid #ddd; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; flex-shrink: 0; }
        .content-wrap { flex: 1; padding: 24px; overflow-y: auto; overflow-x: hidden; }
        
        /* Helpers */
        .rounded-lg { border-radius: 0.75rem; }
        .btn-ghost { background: transparent; border: none; }
        .chevron { width: 16px; height: 16px; margin-left: auto; transition: transform 0.3s; }
        .rotate-180 { transform: rotate(180deg); }
        
        /* Layout wrapper */
        .flex { display: flex; }
        .h-screen { height: 100vh; }
        .overflow-hidden { overflow: hidden; }
        
        /* Dashboard Cards & Components */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; display: flex; align-items: center; gap: 16px; }
        .icon { width: 52px; height: 52px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .info h2 { font-size: 28px; font-weight: 700; color: #000; margin: 0; }
        .info p { font-size: 14px; color: #666; margin: 0; }
        
        .grid-chart { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 24px; }
        .box { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; }
        .box-title { font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        .chart-placeholder { height: 200px; background: #fafafa; border: 2px dashed #ccc; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #999; }
        
        .bar { height: 6px; background: #eee; border-radius: 10px; margin-bottom: 12px; }
        .bar-label { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
        
        .empty { height: 120px; background: #fafafa; border: 1px dashed #ccc; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 13px; }
        
        /* Logout Button */
        .logout-btn { width: 100%; padding: 12px 15px; background: #dc2626; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px; transition: all 0.2s; }
        .logout-btn:hover { background: #b91c1c; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3); }
        .logout-btn:active { transform: translateY(0); }
    </style>
</head>
<body>
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Logo Header -->
            <div class="logo-row">
                <div class="flex items-center space-x-2">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/></svg>
                    <span class="text-white font-bold text-lg">Inflight-CIMS</span>
                </div>
            </div>

            <!-- User Profile -->
            <div class="profile">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background: #4dabf7;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color: #ffffff;">{{ Auth::user()->name }}</p>
                        <p class="text-xs" style="color: #a8b8ff;">{{ Auth::user()->roles->first()->name ?? 'User' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav style="flex: 1; overflow-y: auto; padding: 16px 8px;">
                <!-- Dashboard -->
                <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') || request()->routeIs('admin.dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                @role('Admin')
                <!-- Users Management -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('users')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="font-medium flex-1 text-left">Users Management</span>
                        <svg id="users-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="users-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        <a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">Create Users</a>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">Manage Users</a>
                    </div>
                </div>

                <!-- Roles & Permissions -->
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="font-medium">Roles & Permissions</span>
                </a>
                @endrole

                @can('view products')
                <!-- Products Management -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('products')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span class="font-medium flex-1 text-left">Products</span>
                        <svg id="products-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="products-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        @role('Admin')
                        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">View Products</a>
                        @can('manage categories')
                        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                        @endcan
                        @endrole
                        @role('Inventory Personnel')
                        <a href="{{ route('inventory-personnel.products.index') }}" class="{{ request()->routeIs('inventory-personnel.products.index') ? 'active' : '' }}">View Products</a>
                        @endrole
                        @role('Inventory Supervisor')
                        <a href="{{ route('inventory-supervisor.approvals.products') }}" class="{{ request()->routeIs('inventory-supervisor.approvals.products') ? 'active' : '' }}">Approve Products</a>
                        @endrole
                    </div>
                </div>
                @endcan

                @can('view stock levels')
                <!-- Stock Movements -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('stock')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        <span class="font-medium flex-1 text-left">Stock Movements</span>
                        <svg id="stock-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="stock-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        @role('Admin')
                        @can('add stock')
                        <a href="{{ route('admin.stock-movements.incoming') }}" class="{{ request()->routeIs('admin.stock-movements.incoming') ? 'active' : '' }}">Incoming</a>
                        <a href="{{ route('admin.stock-movements.issue') }}" class="{{ request()->routeIs('admin.stock-movements.issue') ? 'active' : '' }}">Issue</a>
                        <a href="{{ route('admin.stock-movements.returns') }}" class="{{ request()->routeIs('admin.stock-movements.returns') ? 'active' : '' }}">Returns</a>
                        @endcan
                        <a href="{{ route('admin.stock-movements.index') }}" class="{{ request()->routeIs('admin.stock-movements.index') ? 'active' : '' }}">History</a>
                        @endrole
                        
                        @role('Inventory Personnel')
                        @can('add stock')
                        <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" class="{{ request()->routeIs('inventory-personnel.stock-movements.incoming') ? 'active' : '' }}">Incoming</a>
                        @endcan
                        @can('issue stock')
                        <a href="{{ route('inventory-personnel.stock-movements.issue') }}" class="{{ request()->routeIs('inventory-personnel.stock-movements.issue') ? 'active' : '' }}">Issue</a>
                        @endcan
                        @can('process returns')
                        <a href="{{ route('inventory-personnel.stock-movements.returns') }}" class="{{ request()->routeIs('inventory-personnel.stock-movements.returns') ? 'active' : '' }}">Returns</a>
                        @endcan
                        <a href="{{ route('inventory-personnel.stock-movements.index') }}" class="{{ request()->routeIs('inventory-personnel.stock-movements.index') ? 'active' : '' }}">History</a>
                        @endrole
                        
                        @role('Inventory Supervisor')
                        <a href="{{ route('inventory-supervisor.approvals.movements') }}" class="{{ request()->routeIs('inventory-supervisor.approvals.movements') ? 'active' : '' }}">Approve Movements</a>
                        <a href="{{ route('inventory-supervisor.stock-movements.index') }}" class="{{ request()->routeIs('inventory-supervisor.stock-movements.index') ? 'active' : '' }}">History</a>
                        @endrole
                    </div>
                </div>
                @endcan

                @if(auth()->user()->can('create catering request') || auth()->user()->can('view all requests') || auth()->user()->hasRole('Admin'))
                <!-- Requests -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('requests')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span class="font-medium flex-1 text-left">Requests</span>
                        <svg id="requests-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="requests-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        @if(auth()->user()->hasRole('Admin') || auth()->user()->can('view all requests'))
                            @can('create catering request')
                            <a href="{{ route('admin.requests.create') }}" class="{{ request()->routeIs('admin.requests.create') ? 'active' : '' }}">Create Request</a>
                            @endcan
                            <a href="{{ route('admin.requests.pending') }}" class="{{ request()->routeIs('admin.requests.pending') || request()->routeIs('admin.requests.approve') ? 'active' : '' }}">Pending</a>
                            <a href="{{ route('admin.requests.approved') }}" class="{{ request()->routeIs('admin.requests.approved') ? 'active' : '' }}">Approved</a>
                            <a href="{{ route('admin.requests.rejected') }}" class="{{ request()->routeIs('admin.requests.rejected') ? 'active' : '' }}">Rejected</a>
                            @can('view all requests')
                            <a href="{{ route('admin.requests.index') }}" class="{{ request()->routeIs('admin.requests.index') || request()->routeIs('admin.requests.show') ? 'active' : '' }}">All Requests</a>
                            @endcan
                        @else
                            @can('create catering request')
                            <a href="{{ route('catering-staff.requests.create') }}" class="{{ request()->routeIs('catering-staff.requests.create') ? 'active' : '' }}">Create Request</a>
                            @endcan
                            <a href="{{ route('catering-staff.requests.index') }}" class="{{ request()->routeIs('catering-staff.requests.index') || request()->routeIs('catering-staff.requests.show') ? 'active' : '' }}">Pending</a>
                            <a href="{{ route('catering-staff.requests.index', ['filter' => 'approved']) }}" class="{{ request()->routeIs('catering-staff.requests.index') ? 'active' : '' }}">Approved</a>
                            <a href="{{ route('catering-staff.requests.index', ['filter' => 'rejected']) }}" class="{{ request()->routeIs('catering-staff.requests.index') ? 'active' : '' }}">Rejected</a>
                        @endif
                    </div>
                </div>
                @endif

                @role('Catering Staff')
                <!-- Meal Management (Catering Staff Only) -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('meals')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l3 9 4-18 3 9h4"/></svg>
                        <span class="flex-1">Meal Management</span>
                        <svg class="chevron w-4 h-4 transform transition-transform duration-200" id="meals-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="sidebar-submenu" id="meals-submenu" style="max-height: 0px;">
                        <a href="{{ route('catering-staff.meals.index') }}" class="{{ request()->routeIs('catering-staff.meals.index') || request()->routeIs('catering-staff.meals.show') ? 'active' : '' }}">All Meals</a>
                        <a href="{{ route('catering-staff.meals.create') }}" class="{{ request()->routeIs('catering-staff.meals.create') ? 'active' : '' }}">Add New Meal</a>
                        <a href="{{ route('catering-staff.meals.index', ['meal_type' => 'breakfast']) }}">Breakfast</a>
                        <a href="{{ route('catering-staff.meals.index', ['meal_type' => 'lunch']) }}">Lunch</a>
                        <a href="{{ route('catering-staff.meals.index', ['meal_type' => 'dinner']) }}">Dinner</a>
                        <a href="{{ route('catering-staff.meals.index', ['meal_type' => 'VIP_meal']) }}">VIP Meals</a>
                        <a href="{{ route('catering-staff.meals.index', ['special' => '1']) }}">Special Meals</a>
                        <a href="{{ route('catering-staff.meals.index', ['active_menu' => '1']) }}">Active Menus</a>
                    </div>
                </div>
                @endrole

                @if(auth()->user()->can('manage flights') || auth()->user()->hasRole('Admin'))
                <!-- Flights Management (Admin Only) -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('flights')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="font-medium flex-1 text-left">Flights</span>
                        <svg id="flights-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="flights-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        @can('manage flights')
                        <a href="{{ route('admin.flights.create') }}" class="{{ request()->routeIs('admin.flights.create') ? 'active' : '' }}">Add Flight</a>
                        @endcan
                        <a href="{{ route('admin.flights.index') }}" class="{{ request()->routeIs('admin.flights.index') ? 'active' : '' }}">View Flights</a>
                    </div>
                </div>
                @endif
                
                @role('Flight Purser|Cabin Crew|Ramp Dispatcher')
                <!-- Flight Schedule (View Only) -->
                <a href="{{ route('flights.schedule') }}" class="sidebar-link {{ request()->routeIs('flights.schedule') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium">Flight Schedule</span>
                </a>
                @endrole

                <!-- System Settings -->
                <div>
                    <button class="sidebar-link w-full btn-ghost" onclick="toggleSubmenu('settings')">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-medium flex-1 text-left">Settings</span>
                        <svg id="settings-icon" class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="settings-submenu" class="sidebar-submenu" style="max-height: 0px;">
                        @role('Admin')
                        <a href="{{ route('admin.settings.general') }}" class="{{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">General Settings</a>
                        <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">Activity Logs</a>
                        <a href="{{ route('admin.logs.index') }}" class="{{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">Audit Logs</a>
                        <a href="{{ route('admin.backup.index') }}" class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">Backup</a>
                        @endrole
                        
                        @role('Catering Staff')
                        <a href="{{ route('catering-staff.settings') }}" class="{{ request()->routeIs('catering-staff.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Inventory Personnel')
                        <a href="{{ route('inventory-personnel.settings') }}" class="{{ request()->routeIs('inventory-personnel.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Inventory Supervisor')
                        <a href="{{ route('inventory-supervisor.settings') }}" class="{{ request()->routeIs('inventory-supervisor.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Security Staff')
                        <a href="{{ route('security-staff.settings') }}" class="{{ request()->routeIs('security-staff.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Catering Incharge')
                        <a href="{{ route('catering-incharge.settings') }}" class="{{ request()->routeIs('catering-incharge.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Ramp Dispatcher')
                        <a href="{{ route('ramp-dispatcher.settings') }}" class="{{ request()->routeIs('ramp-dispatcher.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Flight Purser')
                        <a href="{{ route('flight-purser.settings') }}" class="{{ request()->routeIs('flight-purser.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                        
                        @role('Cabin Crew')
                        <a href="{{ route('cabin-crew.settings') }}" class="{{ request()->routeIs('cabin-crew.settings*') ? 'active' : '' }}"> Settings</a>
                        @endrole
                    </div>
                </div>

            </nav>

            <!-- Logout Button - Fixed at bottom -->
            <div style="padding: 15px; border-top: 1px solid #1a2980; background: #0b1a68;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main ml-64">
            <!-- Top Bar -->
            <header class="topbar">
                <div>
                    <h1 class="text-xl font-bold" style="color: #212529;">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-sm" style="color: #6c757d; margin:0;">@yield('page-description', 'Welcome to Inflight Catering System')</p>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" id="notifications-container">
                        <button onclick="toggleNotifications()" class="relative p-2 hover:bg-gray-100 rounded-lg transition" style="color: #6c757d; background:transparent; border:none; cursor:pointer;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span id="notification-badge" class="absolute top-1 right-1 w-5 h-5 rounded-full text-xs flex items-center justify-center text-white font-semibold" style="background-color: #dc3545; display: none;">0</span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div id="notifications-dropdown" style="display:none; position: absolute; right: 0; top: 100%; margin-top: 8px; width: 380px; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; max-height: 500px; overflow-y: auto;">
                            <!-- Header -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid #e9ecef;">
                                <h3 style="font-size: 16px; font-weight: 600; margin: 0;">Notifications</h3>
                                <button onclick="markAllAsRead()" style="font-size: 13px; color: #0066cc; background: none; border: none; cursor: pointer;">Mark all read</button>
                            </div>
                            
                            <!-- Notifications List -->
                            <div id="notifications-list" style="max-height: 400px; overflow-y: auto;">
                                <!-- Loading state -->
                                <div class="text-center py-8" style="color: #6c757d;">
                                    <svg class="animate-spin h-8 w-8 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div style="padding: 12px 16px; border-top: 1px solid #e9ecef; text-align: center;">
                                <a href="{{ route('notifications.index') }}" style="font-size: 13px; color: #0066cc; text-decoration: none;">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold" style="background: linear-gradient(135deg, #0066cc 0%, #004999 100%);">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-wrap">
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg flex items-center" style="background-color: #d1e7dd; border: 1px solid #badbcc; color: #0f5132;">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg flex items-center" style="background-color: #f8d7da; border: 1px solid #f5c2c7; color: #842029;">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id + '-submenu');
            const icon = document.getElementById(id + '-icon');
            
            if (!submenu) return;
            
            if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
                submenu.style.maxHeight = '0px';
                if (icon) icon.classList.remove('rotate-180');
            } else {
                // Close all other submenus
                document.querySelectorAll('.sidebar-submenu').forEach(s => {
                    s.style.maxHeight = '0px';
                });
                document.querySelectorAll('.chevron').forEach(i => {
                    i.classList.remove('rotate-180');
                });
                
                // Open this submenu
                submenu.style.maxHeight = '500px';
                if (icon) icon.classList.add('rotate-180');
            }
        }
        
        // Preserve submenu open state on page load if active link inside
        document.addEventListener('DOMContentLoaded', function() {
            const submenuIds = ['users', 'products', 'stock', 'requests', 'flights', 'settings'];
            submenuIds.forEach(id => {
                const submenu = document.getElementById(id + '-submenu');
                if (!submenu) return;
                if (submenu.querySelector('a.active')) {
                    submenu.style.maxHeight = '500px';
                    const icon = document.getElementById(id + '-icon');
                    if (icon) icon.classList.add('rotate-180');
                }
            });
            
            // Load notifications on page load
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });
        
        // NOTIFICATIONS SYSTEM
        let notificationsDropdownOpen = false;
        
        function toggleNotifications() {
            const dropdown = document.getElementById('notifications-dropdown');
            notificationsDropdownOpen = !notificationsDropdownOpen;
            
            if (notificationsDropdownOpen) {
                dropdown.style.display = 'block';
                loadRecentNotifications();
            } else {
                dropdown.style.display = 'none';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.getElementById('notifications-container');
            if (container && !container.contains(event.target) && notificationsDropdownOpen) {
                document.getElementById('notifications-dropdown').style.display = 'none';
                notificationsDropdownOpen = false;
            }
        });
        
        function loadNotifications() {
            fetch('{{ route("notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.count);
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        function loadRecentNotifications() {
            const list = document.getElementById('notifications-list');
            list.innerHTML = '<div class="text-center py-4" style="color: #6c757d;">Loading...</div>';
            
            fetch('{{ route("notifications.recent") }}')
                .then(response => response.json())
                .then(data => {
                    updateNotificationBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    list.innerHTML = '<div class="text-center py-4" style="color: #dc3545;">Error loading notifications</div>';
                });
        }
        
        function renderNotifications(notifications) {
            const list = document.getElementById('notifications-list');
            
            if (notifications.length === 0) {
                list.innerHTML = '<div class="text-center py-8" style="color: #6c757d;">No notifications</div>';
                return;
            }
            
            list.innerHTML = notifications.map(notification => {
                const data = notification.data;
                const isUnread = !notification.read_at;
                const timeAgo = formatTimeAgo(notification.created_at);
                
                const iconColors = {
                    blue: '#0066cc',
                    green: '#28a745',
                    red: '#dc3545',
                    orange: '#fd7e14',
                    purple: '#6f42c1'
                };
                
                const iconColor = iconColors[data.color] || '#6c757d';
                
                return `
                    <div onclick="handleNotificationClick('${notification.id}', '${data.action_url || '#'}')" 
                         style="padding: 16px; border-bottom: 1px solid #e9ecef; cursor: pointer; background: ${isUnread ? '#f8f9fa' : 'white'}; transition: background 0.2s;"
                         onmouseover="this.style.background='#f1f3f5'" 
                         onmouseout="this.style.background='${isUnread ? '#f8f9fa' : 'white'}'">
                        <div style="display: flex; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: ${iconColor}15; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 20px; height: 20px; color: ${iconColor};" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                                </svg>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                                    <h4 style="font-size: 14px; font-weight: 600; margin: 0; color: #212529;">${data.title}</h4>
                                    ${isUnread ? '<span style="width: 8px; height: 8px; background: #0066cc; border-radius: 50%; display: inline-block;"></span>' : ''}
                                </div>
                                <p style="font-size: 13px; color: #6c757d; margin: 0; line-height: 1.4;">${data.message}</p>
                                <span style="font-size: 12px; color: #adb5bd; margin-top: 4px; display: block;">${timeAgo}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function handleNotificationClick(notificationId, actionUrl) {
            // Mark as read
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                loadNotifications();
                if (actionUrl && actionUrl !== '#') {
                    window.location.href = actionUrl;
                }
            });
        }
        
        function markAllAsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                loadRecentNotifications();
            });
        }
        
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
        
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
            if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
            return date.toLocaleDateString();
        }
    </script>
</body>
</html>
