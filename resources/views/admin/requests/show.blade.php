@extends('layouts.app')

@section('page-title', 'Request #' . $request->id)
@section('page-description', 'View detailed request information')

@section('content')
<style>
    * { box-sizing: border-box; }
    body { background: #f8fafc; }
    
    /* Header Styles */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: 16px;
        margin-bottom: 32px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }
    .page-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }
    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 12px 0;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .page-header .request-id {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    .page-header .meta {
        display: flex;
        gap: 24px;
        margin-top: 12px;
        font-size: 15px;
        opacity: 0.95;
    }
    .page-header .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Button Styles */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 15px;
    }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .btn-secondary { background: white; color: #475569; border: 2px solid #e2e8f0; }
    .btn-danger { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .btn-success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    
    /* Card Styles */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 32px;
        margin-bottom: 24px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s;
    }
    .card:hover { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 3px solid #f1f5f9;
    }
    .card-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .card-header h3 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .info-item {
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        border-left: 4px solid #667eea;
        transition: all 0.3s;
    }
    .info-item:hover {
        transform: translateX(4px);
        border-left-color: #764ba2;
    }
    .info-item label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-item .value {
        color: #1e293b;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.4;
    }
    .info-item .value small {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 400;
        margin-top: 6px;
    }
    
    /* Badge Styles */
    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Table Styles */
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        color: white;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        padding: 18px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
    }
    tbody tr {
        transition: all 0.2s;
    }
    tbody tr:hover {
        background: #f8fafc;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Summary Box */
    .summary-box {
        margin-top: 24px;
        padding: 20px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 20px;
    }
    .summary-item {
        text-align: center;
    }
    .summary-item .label {
        font-size: 12px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .summary-item .value {
        font-size: 28px;
        font-weight: 700;
    }
    
    /* Status Info Boxes */
    .status-info {
        padding: 24px;
        border-radius: 12px;
        margin-top: 24px;
        border-left: 5px solid;
    }
    .status-info.approval {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left-color: #10b981;
    }
    .status-info.rejection {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left-color: #ef4444;
    }
    .status-info h4 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 16px 0;
    }
    .status-info .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    .status-info .info-row:last-child {
        border-bottom: none;
    }
    
    /* Notes Box */
    .notes-box {
        margin-top: 24px;
        padding: 20px;
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        border-radius: 12px;
    }
    .notes-box label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 12px;
        text-transform: uppercase;
    }
    .notes-box .content {
        color: #78350f;
        font-size: 15px;
        line-height: 1.6;
    }
    
    /* Actions Bar */
    .actions-bar {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f1f5f9;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; align-items: flex-start; gap: 20px; }
        .info-grid { grid-template-columns: 1fr; }
        .summary-box { flex-direction: column; }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div>
            <h1>
                <span style="opacity: 0.8;">Request</span>
                <span class="request-id">#{{ $request->id }}</span>
            </h1>
            <div class="meta">
                <div class="meta-item">
                    <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    {{ $request->requested_date->format('d M Y') }} ({{ $request->requested_date->diffForHumans() }})
                </div>
                <div class="meta-item">
                    <span class="badge" style="background: {{ $request->getStatusBackground() }}; color: {{ $request->getStatusColor() }};">
                        {{ str_replace('_', ' ', ucwords($request->status)) }}
                    </span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Requests
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">👤</div>
        <h3>Request Information</h3>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                Requester
            </label>
            <div class="value">
                {{ $request->requester->name }}
                <small>
                    <svg style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    {{ $request->requester->email }}
                </small>
                @if($request->requester->roles->isNotEmpty())
                    <small style="display: inline-block; margin-top: 8px; padding: 4px 10px; background: #667eea; color: white; border-radius: 6px; font-size: 11px; font-weight: 600;">
                        {{ $request->requester->roles->first()->name }}
                    </small>
                @endif
            </div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Request Date
            </label>
            <div class="value">
                {{ $request->requested_date->format('d M Y') }}
                <small>⏰ {{ $request->requested_date->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    @if($request->notes)
        <div class="notes-box">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Notes
            </label>
            <div class="content">{{ $request->notes }}</div>
        </div>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">✈️</div>
        <h3>Flight Details</h3>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
                Flight Number
            </label>
            <div class="value">{{ $request->flight->flight_number }}</div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                </svg>
                Airline
            </label>
            <div class="value">{{ $request->flight->airline ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Route
            </label>
            <div class="value">
                <span style="display: inline-flex; align-items: center; gap: 8px;">
                    <span style="font-weight: 700; color: #667eea;">{{ $request->flight->origin ?? 'N/A' }}</span>
                    <svg style="width: 20px; height: 20px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    <span style="font-weight: 700; color: #764ba2;">{{ $request->flight->destination ?? 'N/A' }}</span>
                </span>
            </div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Departure Time
            </label>
            <div class="value">
                {{ $request->flight->departure_time->format('d M Y H:i') }}
                <small>🕐 {{ $request->flight->departure_time->diffForHumans() }}</small>
            </div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                </svg>
                Aircraft Type
            </label>
            <div class="value">{{ $request->flight->aircraft_type ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <label>
                <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                Passenger Capacity
            </label>
            <div class="value">
                {{ $request->flight->passenger_capacity ?? 'N/A' }} 
                @if($request->flight->passenger_capacity)
                    <small style="color: #667eea;">👥 passengers</small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-header-icon">📦</div>
        <h3>Requested Items</h3>
    </div>
    
    <div class="table-container">
        <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Meal Type</th>
                            <th>Quantity Requested</th>
                            <th>Scheduled</th>
                            @if($request->isApproved() || $request->isRejected())
                                <th>Quantity Approved</th>
                                <th>Fulfillment</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request->items as $item)
                            <tr>
                                <td><strong>{{ $item->product->name }}</strong></td>
                                <td>{{ $item->product->category->name }}</td>
                                <td>
                                    @if($item->meal_type)
                                        @php
                                            $mealBadges = [
                                                'breakfast' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '🍳', 'label' => 'Breakfast'],
                                                'lunch' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '🍽️', 'label' => 'Lunch'],
                                                'dinner' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '🌙', 'label' => 'Dinner'],
                                                'snack' => ['bg' => '#fce7f3', 'color' => '#9f1239', 'icon' => '🍪', 'label' => 'Snack'],
                                                'VIP_meal' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'icon' => '👑', 'label' => 'VIP Meal'],
                                                'special_meal' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '⭐', 'label' => 'Special'],
                                                'non_meal' => ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'Non-Meal']
                                            ];
                                            $badge = $mealBadges[$item->meal_type] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => ucfirst($item->meal_type)];
                                        @endphp
                                        <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:8px 14px;border-radius:20px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;">
                                            <span style="font-size: 16px;">{{ $badge['icon'] }}</span>
                                            {{ $badge['label'] }}
                                        </span>
                                    @else
                                        <span style="color:#9ca3af;font-size:13px;font-weight:500;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 20px; font-weight: 700; color: #667eea;">{{ $item->quantity_requested }}</span>
                                    <span style="font-size: 12px; color: #64748b; margin-left: 4px;">units</span>
                                </td>
                                <td>
                                    @if($item->is_scheduled)
                                        <span style="background: #dcfce7; color: #166534; padding: 8px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            @if($item->scheduled_at)
                                                {{ \Carbon\Carbon::parse($item->scheduled_at)->format('M d, H:i') }}
                                            @else
                                                Scheduled
                                            @endif
                                        </span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 8px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Not Scheduled
                                        </span>
                                    @endif
                                </td>
                                @if($request->isApproved() || $request->isRejected())
                                    <td>
                                        <strong style="color: {{ $item->quantity_approved > 0 ? '#059669' : '#64748b' }};">
                                            {{ $item->quantity_approved ?? 0 }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($item->isFullyApproved())
                                            <span style="color: #059669; font-weight: 500;">✓ Fully Approved</span>
                                        @elseif($item->isPartiallyApproved())
                                            <span style="color: #f59e0b; font-weight: 500;">⚠ Partial ({{ $item->getApprovalPercentage() }}%)</span>
                                        @else
                                            <span style="color: #64748b;">✗ Not Approved</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
        </table>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="label">📦 Total Products</div>
            <div class="value">{{ $request->items->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="label">📊 Total Quantity</div>
            <div class="value">{{ $request->getTotalItemsCount() }}</div>
        </div>
        @if($request->isApproved())
            <div class="summary-item">
                <div class="label">✅ Approved Quantity</div>
                <div class="value">{{ $request->items->sum('quantity_approved') }}</div>
            </div>
        @endif
    </div>
</div>

@if($request->isApproved())
    <div class="status-info approval">
        <h4>
            <svg style="width: 24px; height: 24px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="color: #065f46;">Approval Information</span>
        </h4>
        <div class="info-row">
            <label style="display: flex; align-items: center; gap: 8px;">
                <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                Approved By
            </label>
            <span class="value" style="color: #065f46; font-weight: 700;">{{ $request->approver->name }}</span>
        </div>
        <div class="info-row">
            <label style="display: flex; align-items: center; gap: 8px;">
                <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                Approval Date
            </label>
            <span class="value" style="color: #065f46; font-weight: 700;">{{ $request->approved_date->format('d M Y H:i') }}</span>
        </div>
        <div class="info-row">
            <label style="display: flex; align-items: center; gap: 8px;">
                <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Stock Movement Ref
            </label>
            <span class="value" style="color: #065f46; font-weight: 700;">REQ-{{ $request->id }} / {{ $request->flight->flight_number }}</span>
        </div>
    </div>
@endif

@if($request->isRejected())
    <div class="status-info rejection">
        <h4>
            <svg style="width: 24px; height: 24px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span style="color: #991b1b;">Rejection Information</span>
        </h4>
        <div class="info-row">
            <label>Rejected By:</label>
            <span class="value" style="color: #991b1b; font-weight: 700;">{{ $request->approver->name ?? 'N/A' }}</span>
        </div>
        @if($request->rejection_reason)
            <div style="margin-top: 16px; padding: 16px; background: white; border-radius: 8px; border: 2px dashed #fca5a5;">
                <label style="display: block; margin-bottom: 8px; color: #991b1b; font-weight: 700; font-size: 13px; text-transform: uppercase;">Rejection Reason:</label>
                <div style="color: #1e293b; line-height: 1.6;">{{ $request->rejection_reason }}</div>
            </div>
        @endif
    </div>
@endif

@if($request->isPending())
    <div class="actions-bar">
        <a href="{{ route('admin.requests.approve-form', $request) }}" class="btn btn-success">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Approve Request
        </a>
        <form method="POST" action="{{ route('admin.requests.destroy', $request) }}" style="display: inline;" onsubmit="return confirm('⚠️ Delete this request? This action cannot be undone!');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Request
            </button>
        </form>
    </div>
@endif
@endsection
