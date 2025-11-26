@extends('layouts.app')

@section('title', 'Flight Purser Dashboard')

@section('content')
<div class="content-header">
    <h1>Flight Purser Dashboard</h1>
    <p>Load catering supplies onto aircraft and coordinate with Cabin Crew</p>
</div>

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;margin-bottom:32px;">
    <!-- Dispatched Requests -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:20px;align-items:center;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $dispatchedRequests }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">To Load</div>
        </div>
    </div>

    <!-- Loaded Requests -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:20px;align-items:center;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $loadedRequests }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Loaded</div>
        </div>
    </div>

    <!-- Upcoming Flights -->
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;gap:20px;align-items:center;">
        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:32px;height:32px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:32px;font-weight:700;color:#1a202c;line-height:1;">{{ $upcomingFlights }}</div>
            <div style="font-size:14px;color:#718096;margin-top:4px;">Next 7 Days</div>
        </div>
    </div>
</div>

<!-- Requests Dispatched from Ramp -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">✈️ Requests to Load onto Aircraft</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Dispatched from Ramp Dispatcher - Ready for Loading</p>
        </div>
        @if($requestsToLoad->count() > 0)
        <span style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:6px 16px;border-radius:20px;font-weight:600;font-size:14px;">
            {{ $requestsToLoad->count() }}
        </span>
        @endif
    </div>

    @if($requestsToLoad->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight Details</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Route</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Departure</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Items</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Dispatched</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestsToLoad as $request)
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
                        {{ $request->dispatched_at ? \Carbon\Carbon::parse($request->dispatched_at)->format('M d, H:i') : 'N/A' }}
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <form action="{{ route('flight-purser.requests.load', $request) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(67,233,123,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
                                📦 Load
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:60px 20px;color:#a0aec0;">
        <div style="font-size:48px;margin-bottom:16px;">📦</div>
        <div style="font-size:16px;font-weight:600;color:#718096;margin-bottom:8px;">No Requests to Load</div>
        <div style="font-size:14px;color:#a0aec0;">All dispatched requests have been loaded</div>
    </div>
    @endif
</div>

<!-- Recently Loaded Requests -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:28px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0;">✅ Recently Loaded Requests</h2>
            <p style="color:#718096;font-size:14px;margin:4px 0 0 0;">Ready to send to Cabin Crew</p>
        </div>
    </div>

    @if($loadedRequestsList->count() > 0)
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Request ID</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Items Count</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Loaded At</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loadedRequestsList as $request)
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
                        {{ $request->loaded_at ? \Carbon\Carbon::parse($request->loaded_at)->format('M d, H:i') : 'N/A' }}
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;">
                            Loaded
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align:center;padding:40px 20px;color:#a0aec0;">
        <div style="font-size:14px;">No loaded requests yet</div>
    </div>
    @endif
</div>
@endsection
