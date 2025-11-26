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
                <div style="padding: 16px 28px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">{{ $request->flight->flight_number ?? 'N/A' }}</div>
                                <div style="font-size: 13px; color: #6b7280;">By: {{ $request->requester->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">{{ $request->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="margin-left: 46px;">
                        @php
                            $statusColors = [
                                'delivered' => ['bg' => '#d1fae5', 'text' => '#059669'],
                                'pending_inventory' => ['bg' => '#fef3c7', 'text' => '#d97706'],
                                'pending_supervisor' => ['bg' => '#fef3c7', 'text' => '#d97706'],
                                'loaded' => ['bg' => '#dbeafe', 'text' => '#2563eb'],
                                'dispatched' => ['bg' => '#e0e7ff', 'text' => '#4f46e5'],
                                'catering_approved' => ['bg' => '#d1fae5', 'text' => '#059669'],
                            ];
                            $color = $statusColors[$request->status] ?? ['bg' => '#e0e7ff', 'text' => '#4f46e5'];
                        @endphp
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                            {{ str_replace('_', ' ', ucwords($request->status)) }}
                        </span>
                    </div>
                </div>
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
                <div style="padding: 16px 28px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">{{ $approval->flight->flight_number ?? 'N/A' }}</div>
                                <div style="font-size: 13px; color: #6b7280;">By: {{ $approval->requester->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">{{ $approval->updated_at->diffForHumans() }}</span>
                    </div>
                    <div style="margin-left: 46px;">
                        <span style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #d1fae5; color: #059669;">
                            {{ str_replace('_', ' ', ucwords($approval->status)) }}
                        </span>
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

    <!-- Recent Stock Movements -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px 28px; border-bottom: 2px solid #f3f4f6; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Recent Stock</h3>
        </div>
        <div style="padding: 12px 0;">
            @forelse($recentStock as $stock)
                <div style="padding: 16px 28px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">{{ $stock->product->name ?? 'N/A' }}</div>
                                <div style="font-size: 13px; color: #6b7280;">{{ ucfirst($stock->type) }}: {{ abs($stock->quantity) }} units</div>
                            </div>
                        </div>
                        <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">{{ $stock->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="margin-left: 46px;">
                        <span style="font-size: 12px; color: #6b7280;">
                            By: <span style="font-weight: 600; color: #1a1a1a;">{{ $stock->user->name ?? 'System' }}</span>
                        </span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 48px 28px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p style="margin: 0;">No stock movements</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
