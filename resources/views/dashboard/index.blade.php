@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-description', 'System overview and statistics')

@section('content')
<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Total Users -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $totalUsers }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Total Users</div>
        </div>
    </div>

    <!-- Total Products -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $totalProducts }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Products</div>
        </div>
    </div>

    <!-- Total Requests -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $totalRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Total Requests</div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $pendingRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Pending</div>
        </div>
    </div>

    <!-- Flights -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: #1a1a1a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $totalFlights }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Flights</div>
        </div>
    </div>

    <!-- Completed Requests -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; gap: 20px; align-items: center; transition: all 0.3s;">
        <div style="width: 64px; height: 64px; border-radius: 12px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a; line-height: 1.2;">{{ $completedRequests }}</div>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">Completed</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Request Status Distribution -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Request Status Distribution</h3>
        </div>
        @forelse($requestsByStatus as $status => $count)
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; font-weight: 600; color: #4b5563;">{{ $status }}</span>
                    <span style="font-size: 14px; font-weight: 700; color: #1a1a1a;">{{ $count }} ({{ $totalRequests > 0 ? round(($count / $totalRequests * 100), 1) : 0 }}%)</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                    <div style="width: {{ $totalRequests > 0 ? ($count / $totalRequests * 100) : 0 }}%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px; transition: width 0.3s;"></div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 32px; color: #9ca3af;">
                <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p>No data available</p>
            </div>
        @endforelse
    </div>

    <!-- By Department -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">By Department</h3>
        </div>
        @php
            $maxCount = max(array_values($requestsByDepartment)) ?: 1;
            $colors = [
                'Catering Staff' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'Inventory' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'Security' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'Ramp Operations' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'Flight Operations' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            ];
        @endphp
        @foreach($requestsByDepartment as $dept => $count)
            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 14px; font-weight: 600; color: #4b5563;">{{ $dept }}</span>
                    <span style="font-size: 14px; font-weight: 700; color: #1a1a1a;">{{ $count }}</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                    <div style="width: {{ ($count / $maxCount * 100) }}%; height: 100%; background: {{ $colors[$dept] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}; border-radius: 4px; transition: width 0.3s;"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Activity Lists -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
    <!-- Latest Requests -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Latest Requests</h3>
        </div>
        <div style="padding: 12px 0;">
            @forelse($latestRequests as $request)
                <a href="{{ route('admin.requests.show', $request) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div style="padding: 16px 28px; border-bottom: 1px solid #f3f4f6; transition: all 0.2s;" onmouseover="this.style.background='#f9fafb'; this.style.borderLeftWidth='4px'; this.style.borderLeftColor='#667eea';" onmouseout="this.style.background='white'; this.style.borderLeftWidth='0px';">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <div style="font-weight: 700; color: #1a1a1a; font-size: 16px;">{{ $request->flight->flight_number ?? 'N/A' }}</div>
                                        <div style="font-size: 11px; background: #eff6ff; color: #1e40af; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                                            #{{ $request->id }}
                                        </div>
                                        @php
                                            $requestType = $request->items->first()?->meal_type ? 'Meal' : 'Product';
                                        @endphp
                                        <div style="font-size: 10px; background: {{ $requestType === 'Meal' ? '#fef3c7' : '#dbeafe' }}; color: {{ $requestType === 'Meal' ? '#92400e' : '#1e40af' }}; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                            {{ $requestType }}
                                        </div>
                                    </div>
                                    <div style="font-size: 12px; color: #6b7280; display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <svg style="width: 12px; height: 12px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span style="font-weight: 600;">{{ $request->flight->origin ?? 'N/A' }}</span>
                                        <svg style="width: 12px; height: 12px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        <span style="font-weight: 600;">{{ $request->flight->destination ?? 'N/A' }}</span>
                                    </div>
                                    <div style="font-size: 11px; color: #9ca3af;">
                                        👤 <span style="font-weight: 600;">{{ $request->requester->name ?? 'Unknown' }}</span>
                                        <span style="margin: 0 6px;">•</span>
                                        📦 <span style="font-weight: 600;">{{ $request->items->count() }} items ({{ $request->items->sum('quantity_requested') }} total)</span>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align: right; flex-shrink: 0; margin-left: 12px;">
                                <div style="font-size: 11px; color: #9ca3af; margin-bottom: 4px; white-space: nowrap;">{{ $request->created_at->diffForHumans() }}</div>
                                <div style="font-size: 10px; color: #6b7280; font-weight: 500;">{{ $request->created_at->format('M d, H:i') }}</div>
                            </div>
                        </div>
                        
                        <!-- Product Details -->
                        @if($request->items->count() > 0)
                        <div style="margin: 10px 0 10px 50px; padding: 8px; background: #f9fafb; border-radius: 6px; border-left: 3px solid #667eea;">
                            <div style="font-size: 10px; color: #6b7280; font-weight: 600; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                📋 Products Requested:
                            </div>
                            @php
                                $topItems = $request->items->take(2);
                                $remainingCount = $request->items->count() - 2;
                            @endphp
                            @foreach($topItems as $item)
                            <div style="font-size: 11px; color: #374151; margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                                <span style="background: #dbeafe; padding: 2px 6px; border-radius: 3px; font-weight: 600; color: #1e40af;">× {{ $item->quantity_requested }}</span>
                                <span style="font-weight: 600;">{{ $item->product->name ?? 'N/A' }}</span>
                                @if($item->meal_type)
                                <span style="background: #fef3c7; color: #92400e; padding: 1px 4px; border-radius: 3px; font-size: 9px; font-weight: 600;">
                                    🍽️ {{ ucfirst($item->meal_type) }}
                                </span>
                                @endif
                            </div>
                            @endforeach
                            @if($remainingCount > 0)
                            <div style="font-size: 10px; color: #9ca3af; margin-top: 4px; font-weight: 600;">
                                +{{ $remainingCount }} more items...
                            </div>
                            @endif
                        </div>
                        @endif

                        <div style="margin-left: 50px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                            @php
                                $statusColors = [
                                    'pending_inventory' => ['bg' => '#fef3c7', 'text' => '#d97706', 'icon' => '📋'],
                                    'pending_supervisor' => ['bg' => '#fef3c7', 'text' => '#d97706', 'icon' => '👨‍💼'],
                                    'supervisor_approved' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => '✅'],
                                    'sent_to_security' => ['bg' => '#dbeafe', 'text' => '#2563eb', 'icon' => '🔒'],
                                    'security_approved' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => '🔒'],
                                    'catering_approved' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => '🍽️'],
                                    'ready_for_dispatch' => ['bg' => '#e0e7ff', 'text' => '#4f46e5', 'icon' => '📦'],
                                    'dispatched' => ['bg' => '#e0e7ff', 'text' => '#4f46e5', 'icon' => '🚚'],
                                    'loaded' => ['bg' => '#dbeafe', 'text' => '#2563eb', 'icon' => '✈️'],
                                    'flight_received' => ['bg' => '#bfdbfe', 'text' => '#1e40af', 'icon' => '✅'],
                                    'delivered' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => '🎉'],
                                    'served' => ['bg' => '#d1fae5', 'text' => '#059669', 'icon' => '✅'],
                                ];
                                $color = $statusColors[$request->status] ?? ['bg' => '#e0e7ff', 'text' => '#4f46e5', 'icon' => '❓'];
                                
                                // Determine responsible person based on current status
                                $actionPerson = null;
                                $actionLabel = null;
                                $actionRole = null;
                                
                                // Show who's responsible or who did the action
                                if ($request->status === 'pending_inventory') {
                                    $actionRole = 'Inventory Personnel';
                                    $actionLabel = 'Pending';
                                } elseif ($request->status === 'pending_supervisor') {
                                    $actionRole = 'Inventory Supervisor';
                                    $actionLabel = 'Pending';
                                } elseif ($request->status === 'supervisor_approved' && $request->approver) {
                                    $actionPerson = $request->approver->name;
                                    $actionLabel = 'Approved by';
                                } elseif ($request->status === 'sent_to_security') {
                                    $actionRole = 'Security Staff';
                                    $actionLabel = 'Pending';
                                } elseif ($request->status === 'security_approved' && $request->approver) {
                                    $actionPerson = $request->approver->name;
                                    $actionLabel = 'Authenticated by';
                                } elseif ($request->status === 'catering_approved' && $request->cateringApprover) {
                                    $actionPerson = $request->cateringApprover->name;
                                    $actionLabel = 'Approved by';
                                } elseif ($request->status === 'ready_for_dispatch') {
                                    $actionRole = 'Ramp Dispatcher';
                                    $actionLabel = 'Ready for';
                                } elseif ($request->status === 'dispatched' && $request->securityDispatcher) {
                                    $actionPerson = $request->securityDispatcher->name;
                                    $actionLabel = 'Dispatched by';
                                } elseif ($request->status === 'loaded' && $request->rampAgent) {
                                    $actionPerson = $request->rampAgent->name;
                                    $actionRole = 'Flight Purser';
                                    $actionLabel = 'Loaded by';
                                } elseif ($request->status === 'flight_received' && $request->flightPurser) {
                                    $actionPerson = $request->flightPurser->name;
                                    $actionRole = 'Flight Purser';
                                    $actionLabel = 'Received by';
                                } elseif (in_array($request->status, ['delivered', 'served']) && $request->cabinCrew) {
                                    $actionPerson = $request->cabinCrew->name;
                                    $actionRole = 'Cabin Crew';
                                    $actionLabel = 'Delivered by';
                                }
                            @endphp
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <span style="display: inline-block; padding: 5px 14px; border-radius: 6px; font-size: 11px; font-weight: 700; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                    {{ $color['icon'] }} {{ str_replace('_', ' ', ucwords($request->status)) }}
                                </span>
                                @if($actionPerson)
                                <span style="font-size: 10px; background: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 6px; font-weight: 600;">
                                    {{ $actionLabel }}: <span style="color: #1a1a1a;">{{ $actionPerson }}</span>
                                </span>
                                @endif
                                @if($actionRole)
                                <span style="font-size: 10px; background: #eff6ff; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-weight: 600; border: 1px solid #bfdbfe;">
                                    👤 {{ $actionLabel }}: {{ $actionRole }}
                                </span>
                                @endif
                            </div>
                            <span style="font-size: 11px; color: #3b82f6; font-weight: 600;">
                                View Details →
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 48px 28px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p style="margin: 0;">No requests</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Latest Approvals -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Latest Approvals</h3>
        </div>
        <div style="padding: 12px 0;">
            @forelse($latestApprovals as $approval)
                @php
                    // Collect all approvals in chronological order
                    $approvals = [];
                    
                    if ($approval->approver) {
                        $approvals[] = ['name' => $approval->approver->name, 'role' => 'Inventory Supervisor'];
                    }
                    if ($approval->cateringApprover) {
                        $approvals[] = ['name' => $approval->cateringApprover->name, 'role' => 'Catering Incharge'];
                    }
                    if ($approval->securityDispatcher) {
                        $approvals[] = ['name' => $approval->securityDispatcher->name, 'role' => 'Security Staff'];
                    }
                    if ($approval->dispatcher) {
                        $approvals[] = ['name' => $approval->dispatcher->name, 'role' => 'Ramp Dispatcher'];
                    }
                    if ($approval->flightPurser) {
                        $approvals[] = ['name' => $approval->flightPurser->name, 'role' => 'Flight Purser'];
                    }
                    if ($approval->cabinCrew) {
                        $approvals[] = ['name' => $approval->cabinCrew->name, 'role' => 'Cabin Crew'];
                    }
                @endphp
                <div style="padding: 16px 28px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 15px; margin-bottom: 2px;">
                                    Request #{{ $approval->id }} - {{ $approval->flight->flight_number ?? 'N/A' }}
                                </div>
                                <div style="font-size: 12px; color: #6b7280;">
                                    Requested by: <span style="font-weight: 600;">{{ $approval->requester->name ?? 'Unknown' }}</span>
                                </div>
                                @if(count($approvals) > 0)
                                    <div style="font-size: 12px; color: #059669; margin-top: 4px;">
                                        <div style="font-weight: 600; margin-bottom: 2px;">✅ Approval Chain:</div>
                                        @foreach($approvals as $index => $approver)
                                            <div style="display: inline-flex; align-items: center; margin-right: 8px; margin-bottom: 2px;">
                                                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; line-height: 20px; font-size: 10px; font-weight: 600; margin-right: 4px;">{{ $index + 1 }}</span>
                                                <span style="font-weight: 600;">{{ $approver['name'] }}</span>
                                                <span style="opacity: 0.7; margin-left: 3px;">({{ $approver['role'] }})</span>
                                                @if($index < count($approvals) - 1)
                                                    <span style="margin: 0 4px; opacity: 0.5;">→</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">{{ $approval->updated_at->diffForHumans() }}</span>
                    </div>
                    <div style="margin-left: 46px; display: flex; gap: 8px; align-items: center;">
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #d1fae5; color: #059669;">
                            {{ str_replace('_', ' ', ucwords($approval->status)) }}
                        </span>
                        @if($approval->items->count() > 0)
                            <span style="font-size: 11px; color: #6b7280;">
                                {{ $approval->items->count() }} products
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 48px 28px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p style="margin: 0;">No approvals</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Pending Stock Movements Approval - Full Width Section -->
<div style="margin-top: 32px;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Pending Stock Movements Approval</h3>
            </div>
            <span style="background: #fef3c7; color: #b45309; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 700;">
                {{ $pendingStockMovements->count() }} Pending
            </span>
        </div>
        <div style="padding: 20px; overflow-x: auto; width: 100%;">
            @if($pendingStockMovements->isEmpty())
                <div style="text-align: center; padding: 48px 28px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p style="margin: 0;">No pending stock movements</p>
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1000px;">
                    <thead>
                        <tr style="background: #fef3c7; border-bottom: 2px solid #fbbf24;">
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; white-space: nowrap; width: 120px;">Reference</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; width: 200px;">Product</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; width: 120px;">Type</th>
                            <th style="padding: 12px 16px; text-align: center; font-weight: 700; color: #78350f; width: 100px;">Quantity</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; width: 150px;">Requested By</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; width: 200px;">Notes</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #78350f; width: 120px;">Date</th>
                            <th style="padding: 12px 16px; text-align: center; font-weight: 700; color: #78350f; width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingStockMovements as $stock)
                            @php
                                $typeBadge = [
                                    'incoming' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '📥'],
                                    'issued' => ['bg' => '#fef3c7', 'text' => '#b45309', 'icon' => '📤'],
                                    'adjustment' => ['bg' => '#e0e7ff', 'text' => '#4338ca', 'icon' => '⚙️'],
                                    'returned' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => '↩️'],
                                ][$stock->type] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => '📦'];
                            @endphp
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s; background: #fffbeb;" 
                                onmouseover="this.style.background='#fef3c7'" 
                                onmouseout="this.style.background='#fffbeb'">
                                
                                <td style="padding: 14px 16px;">
                                    <span style="font-weight: 600; color: #1e40af;">{{ $stock->reference_number ?? 'N/A' }}</span>
                                </td>

                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: #1a1a1a;">{{ $stock->product->name ?? 'N/A' }}</div>
                                    <div style="font-size: 11px; color: #9ca3af;">{{ $stock->product->category->name ?? '' }}</div>
                                </td>

                                <td style="padding: 14px 16px;">
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: {{ $typeBadge['bg'] }}; color: {{ $typeBadge['text'] }};">
                                        {{ $typeBadge['icon'] }} {{ ucfirst($stock->type) }}
                                    </span>
                                </td>

                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="font-weight: 700; color: {{ $stock->type === 'incoming' ? '#059669' : '#dc2626' }}; font-size: 15px;">
                                        {{ $stock->type === 'incoming' ? '+' : '-' }}{{ abs($stock->quantity) }}
                                    </span>
                                </td>

                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: #1a1a1a;">{{ $stock->user->name ?? 'System' }}</div>
                                    @if($stock->user && $stock->user->roles->isNotEmpty())
                                        <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">
                                            {{ $stock->user->roles->first()->name }}
                                        </div>
                                    @endif
                                </td>

                                <td style="padding: 14px 16px;">
                                    <span style="font-size: 12px; color: #6b7280;">
                                        {{ $stock->notes ? Str::limit($stock->notes, 30) : 'No notes' }}
                                    </span>
                                </td>

                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 500; color: #1a1a1a;">{{ $stock->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">{{ $stock->created_at->diffForHumans() }}</div>
                                </td>

                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <form method="POST" action="{{ route('inventory-supervisor.approvals.movements.approve', $stock) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);"
                                                    onmouseover="this.style.background='#059669'" 
                                                    onmouseout="this.style.background='#10b981'">
                                                ✅ Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('inventory-supervisor.approvals.movements.reject', $stock) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);"
                                                    onmouseover="this.style.background='#dc2626'" 
                                                    onmouseout="this.style.background='#ef4444'"
                                                    onclick="return confirm('Are you sure you want to reject this stock movement?')">
                                                ❌ Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Recent Stock Movements - Full Width Section -->
<div style="margin-top: 32px;">
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Recent Stock Movements</h3>
        </div>
        <div style="padding: 20px; overflow-x: auto; width: 100%;">
            @if($recentStock->isEmpty())
                <div style="text-align: center; padding: 48px 28px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p style="margin: 0;">No stock movements</p>
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1200px;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; white-space: nowrap; width: 120px;">Reference</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 180px;">Product</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 120px;">Type</th>
                            <th style="padding: 12px 16px; text-align: center; font-weight: 700; color: #374151; width: 100px;">Quantity</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 150px;">Requested By</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 150px;">Approved By</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 200px;">Destination</th>
                            <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #374151; width: 110px;">Date</th>
                            <th style="padding: 12px 16px; text-align: center; font-weight: 700; color: #374151; width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentStock as $stock)
                            @php
                                // Determine type badge color
                                $typeBadge = [
                                    'incoming' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '📥'],
                                    'issued' => ['bg' => '#fef3c7', 'text' => '#b45309', 'icon' => '📤'],
                                    'adjustment' => ['bg' => '#e0e7ff', 'text' => '#4338ca', 'icon' => '⚙️'],
                                    'returned' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => '↩️'],
                                ][$stock->type] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => '📦'];

                                // Extract destination from notes or reference
                                $destination = 'N/A';
                                
                                // Try to extract from notes first
                                if (!empty($stock->notes)) {
                                    // Check if notes contain flight info
                                    if (preg_match('/Flight\s+([A-Z0-9]+)/i', $stock->notes, $matches)) {
                                        $destination = $matches[1];
                                    } elseif (preg_match('/([A-Z]{3})\s*(?:to|→|-)\s*([A-Z]{3})/i', $stock->notes, $matches)) {
                                        $destination = $matches[1] . ' → ' . $matches[2];
                                    } else {
                                        // Show notes if no flight pattern found
                                        $destination = substr($stock->notes, 0, 40) . (strlen($stock->notes) > 40 ? '...' : '');
                                    }
                                }
                                
                                // If still N/A, try reference number
                                if ($destination === 'N/A' && preg_match('/REQ-(\d+)/', $stock->reference_number ?? '', $matches)) {
                                    $reqId = $matches[1];
                                    $request = \App\Models\Request::with('flight')->find($reqId);
                                    if ($request && $request->flight) {
                                        $destination = $request->flight->flight_number . ' (' . 
                                                      $request->flight->origin . ' → ' . 
                                                      $request->flight->destination . ')';
                                    } else {
                                        $destination = 'Request #' . $reqId;
                                    }
                                }
                                
                                // For incoming type, show supplier/source info
                                if ($stock->type === 'incoming' && $destination === 'N/A') {
                                    $destination = '📦 Main Stock';
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" 
                                onmouseover="this.style.background='#f9fafb'" 
                                onmouseout="this.style.background='white'">
                                
                                <!-- Reference -->
                                <td style="padding: 14px 16px;">
                                    <span style="font-weight: 600; color: #1e40af;">{{ $stock->reference_number ?? 'N/A' }}</span>
                                </td>

                                <!-- Product -->
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: #1a1a1a;">{{ $stock->product->name ?? 'N/A' }}</div>
                                    <div style="font-size: 11px; color: #9ca3af;">{{ $stock->product->category->name ?? '' }}</div>
                                </td>

                                <!-- Type -->
                                <td style="padding: 14px 16px;">
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: {{ $typeBadge['bg'] }}; color: {{ $typeBadge['text'] }};">
                                        {{ $typeBadge['icon'] }} {{ ucfirst($stock->type) }}
                                    </span>
                                </td>

                                <!-- Quantity -->
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="font-weight: 700; color: {{ $stock->type === 'incoming' ? '#059669' : '#dc2626' }}; font-size: 15px;">
                                        {{ $stock->type === 'incoming' ? '+' : '-' }}{{ abs($stock->quantity) }}
                                    </span>
                                </td>

                                <!-- Requested By -->
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 600; color: #1a1a1a;">{{ $stock->user->name ?? 'System' }}</div>
                                    @if($stock->user && $stock->user->roles->isNotEmpty())
                                        <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">
                                            {{ $stock->user->roles->first()->name }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Approved By -->
                                <td style="padding: 14px 16px;">
                                    @if($stock->approvedBy)
                                        <div style="font-weight: 600; color: #059669;">✅ {{ $stock->approvedBy->name }}</div>
                                        <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">
                                            {{ $stock->approved_at ? $stock->approved_at->format('M d, H:i') : '' }}
                                        </div>
                                    @else
                                        <span style="color: #9ca3af; font-size: 12px;">Pending</span>
                                    @endif
                                </td>

                                <!-- Destination -->
                                <td style="padding: 14px 16px;">
                                    <span style="font-size: 12px; color: {{ $destination !== 'N/A' ? '#1a1a1a' : '#9ca3af' }};">
                                        {{ $destination }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 500; color: #1a1a1a;">{{ $stock->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">{{ $stock->created_at->format('H:i') }}</div>
                                </td>

                                <!-- Status -->
                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($stock->status === 'approved')
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #d1fae5; color: #065f46;">
                                            ✅ Approved
                                        </span>
                                    @elseif($stock->status === 'pending')
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #fef3c7; color: #b45309;">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; background: #f3f4f6; color: #6b7280;">
                                            {{ ucfirst($stock->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection
