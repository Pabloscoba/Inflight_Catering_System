@props(['exclude' => []])

@php
    $dashboardActions = config('dashboard-actions', []);
    $userPermissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();
    
    // Get role-specific route prefix
    $rolePrefix = 'admin';
    if (auth()->user()->hasRole('Cabin Crew')) {
        $rolePrefix = 'cabin-crew';
    } elseif (auth()->user()->hasRole('Catering Staff')) {
        $rolePrefix = 'catering-staff';
    } elseif (auth()->user()->hasRole('Inventory Personnel')) {
        $rolePrefix = 'inventory-personnel';
    } elseif (auth()->user()->hasRole('Inventory Supervisor')) {
        $rolePrefix = 'inventory-supervisor';
    } elseif (auth()->user()->hasRole('Security Staff')) {
        $rolePrefix = 'security-staff';
    } elseif (auth()->user()->hasRole('Ramp Dispatcher')) {
        $rolePrefix = 'ramp-dispatcher';
    } elseif (auth()->user()->hasRole('Catering Incharge')) {
        $rolePrefix = 'catering-incharge';
    } elseif (auth()->user()->hasRole('Flight Purser')) {
        $rolePrefix = 'flight-purser';
    }
    
    // Filter actions based on user permissions
    $availableActions = [];
    foreach ($userPermissions as $permission) {
        if (isset($dashboardActions[$permission]) && !in_array($permission, $exclude)) {
            $action = $dashboardActions[$permission];
            
            // If route is dynamic, replace prefix with user's role
            if (isset($action['dynamic_route']) && $action['dynamic_route']) {
                $routeParts = explode('.', $action['route']);
                if (count($routeParts) >= 2) {
                    $routeParts[0] = $rolePrefix;
                    $action['route'] = implode('.', $routeParts);
                }
            }
            
            $action['permission'] = $permission;
            $availableActions[] = $action;
        }
    }
@endphp

@if(count($availableActions) > 0)
    @foreach($availableActions as $action)
        @php
            // Check if route exists before rendering
            try {
                $url = route($action['route']);
                $routeExists = true;
            } catch (\Exception $e) {
                $routeExists = false;
            }
        @endphp
        
        @if($routeExists)
        <a href="{{ $url }}" 
           style="text-decoration:none;background:{{ $action['color'] ?? 'linear-gradient(135deg,#667eea 0%,#764ba2 100%)' }};border-radius:12px;padding:20px;color:white;transition:transform 0.2s,box-shadow 0.2s;display:block;" 
           onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 20px rgba(102,126,234,0.4)'" 
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'"
           title="{{ $action['permission'] }}">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;">{{ $action['icon'] ?? '🔘' }}</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;margin-bottom:4px;">{{ $action['title'] }}</div>
                    <div style="font-size:12px;opacity:0.9;">{{ $action['description'] }}</div>
                </div>
                <div style="font-size:20px;">→</div>
            </div>
        </a>
        @endif
    @endforeach
@endif
