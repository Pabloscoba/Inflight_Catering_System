@extends('layouts.app')

@section('title', 'Flight Dispatcher Dashboard')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h1>✈️ Flight Dispatcher Dashboard</h1>
        <div style="display:flex;gap:10px">
            <a href="{{ route('flight-dispatcher.flights.schedule') }}" style="padding:10px 16px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                📅 Flight Schedule
            </a>
            <a href="{{ route('flight-dispatcher.dispatches.create') }}" style="padding:10px 16px;background:#10b981;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ➕ New Dispatch
            </a>
            <a href="{{ route('flight-dispatcher.messages.index') }}" style="padding:10px 16px;background:#8b5cf6;color:white;border-radius:6px;text-decoration:none;font-weight:600;position:relative">
                💬 Messages
                @if($stats['unread_messages'] > 0)
                    <span style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:white;border-radius:50%;padding:4px 8px;font-size:12px">
                        {{ $stats['unread_messages'] }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
        <div style="background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="color:#6b7280;font-size:14px;margin:0">Flights Today</p>
                    <p style="font-size:32px;font-weight:bold;color:#3b82f6;margin:8px 0 0">{{ $stats['flights_today'] }}</p>
                </div>
                <div style="background:#dbeafe;padding:12px;border-radius:50%;font-size:24px">✈️</div>
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="color:#6b7280;font-size:14px;margin:0">Upcoming (24h)</p>
                    <p style="font-size:32px;font-weight:bold;color:#10b981;margin:8px 0 0">{{ $stats['flights_upcoming'] }}</p>
                </div>
                <div style="background:#d1fae5;padding:12px;border-radius:50%;font-size:24px">⏰</div>
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="color:#6b7280;font-size:14px;margin:0">Active Dispatches</p>
                    <p style="font-size:32px;font-weight:bold;color:#f59e0b;margin:8px 0 0">{{ $stats['active_dispatches'] }}</p>
                </div>
                <div style="background:#fed7aa;padding:12px;border-radius:50%;font-size:24px">📋</div>
            </div>
        </div>

        <div style="background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="color:#6b7280;font-size:14px;margin:0">Boarding Now</p>
                    <p style="font-size:32px;font-weight:bold;color:#8b5cf6;margin:8px 0 0">{{ $stats['flights_boarding'] }}</p>
                </div>
                <div style="background:#ede9fe;padding:12px;border-radius:50%;font-size:24px">🚪</div>
            </div>
        </div>
    </div>

    {{-- Today's Flights --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📅 Today's Flights</h3>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Flight #</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Airline</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Route</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Departure</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Status</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todaysFlights as $flight)
                        <tr style="border-bottom:1px solid #e5e7eb">
                            <td style="padding:12px;font-weight:600">{{ $flight->flight_number }}</td>
                            <td style="padding:12px">{{ $flight->airline }}</td>
                            <td style="padding:12px">{{ $flight->origin }} → {{ $flight->destination }}</td>
                            <td style="padding:12px">{{ $flight->departure_time->format('H:i') }}</td>
                            <td style="padding:12px">
                                @if($flight->status === 'scheduled')
                                    <span style="padding:4px 12px;border-radius:12px;background:#dbeafe;color:#1e40af;font-size:12px">Scheduled</span>
                                @elseif($flight->status === 'boarding')
                                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Boarding</span>
                                @elseif($flight->status === 'delayed')
                                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">Delayed</span>
                                @elseif($flight->status === 'departed')
                                    <span style="padding:4px 12px;border-radius:12px;background:#ede9fe;color:#5b21b6;font-size:12px">Departed</span>
                                @else
                                    <span style="padding:4px 12px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">{{ ucfirst($flight->status) }}</span>
                                @endif
                            </td>
                            <td style="padding:12px">
                                <a href="{{ route('flight-dispatcher.flights.show', $flight) }}" style="color:#3b82f6;text-decoration:none;font-weight:500">
                                    👁️ View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:24px;text-align:center;color:#6b7280">No flights scheduled for today</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;margin-bottom:24px">
        
        {{-- Active Dispatch Records --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">📋 Active Dispatch Records</h3>
            <div style="display:flex;flex-direction:column;gap:12px">
                @forelse($activeDispatches as $dispatch)
                    <div style="border:1px solid #e5e7eb;border-radius:8px;padding:16px">
                        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px">
                            <div>
                                <p style="font-weight:600;margin:0">{{ $dispatch->flight->flight_number }}</p>
                                <p style="font-size:14px;color:#6b7280;margin:4px 0 0">{{ $dispatch->flight->origin }} → {{ $dispatch->flight->destination }}</p>
                            </div>
                            @if($dispatch->overall_status === 'ready')
                                <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Ready</span>
                            @elseif($dispatch->overall_status === 'in_progress')
                                <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">In Progress</span>
                            @else
                                <span style="padding:4px 12px;border-radius:12px;background:#f3f4f6;color:#374151;font-size:12px">Pending</span>
                            @endif
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div style="margin-bottom:12px">
                            <div style="display:flex;justify-between;font-size:12px;color:#6b7280;margin-bottom:4px">
                                <span>Completion</span>
                                <span>{{ $dispatch->getCompletionPercentage() }}%</span>
                            </div>
                            <div style="width:100%;background:#e5e7eb;border-radius:999px;height:8px;overflow:hidden">
                                <div style="background:#3b82f6;height:100%;border-radius:999px;width:{{ $dispatch->getCompletionPercentage() }}%"></div>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div style="display:flex;gap:8px;font-size:12px">
                                <span style="padding:4px 8px;border-radius:6px;{{ $dispatch->fuel_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">⛽</span>
                                <span style="padding:4px 8px;border-radius:6px;{{ $dispatch->crew_readiness === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">👥</span>
                                <span style="padding:4px 8px;border-radius:6px;{{ $dispatch->catering_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">🍽️</span>
                                <span style="padding:4px 8px;border-radius:6px;{{ $dispatch->baggage_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">🧳</span>
                            </div>
                            <a href="{{ route('flight-dispatcher.dispatches.show', $dispatch) }}" style="color:#3b82f6;text-decoration:none;font-size:14px;font-weight:500">
                                View Details →
                            </a>
                        </div>
                    </div>
                @empty
                    <p style="text-align:center;color:#6b7280;padding:24px 0">No active dispatch records</p>
                @endforelse
            </div>
            <div style="margin-top:16px">
                <a href="{{ route('flight-dispatcher.dispatches.index') }}" style="color:#3b82f6;text-decoration:none;font-size:14px;font-weight:500">
                    View All Dispatches →
                </a>
            </div>
        </div>

        {{-- Recent Messages --}}
        <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
            <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">
                💬 Recent Messages
                @if($stats['unread_messages'] > 0)
                    <span style="background:#ef4444;color:white;font-size:12px;padding:4px 8px;border-radius:12px;margin-left:8px">{{ $stats['unread_messages'] }} new</span>
                @endif
            </h3>
            <div style="display:flex;flex-direction:column;gap:12px">
                @forelse($unreadMessages as $message)
                    <div style="border-left:4px solid {{ $message->message_type === 'urgent' ? '#ef4444' : '#3b82f6' }};padding-left:16px;padding-top:8px;padding-bottom:8px">
                        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:4px">
                            <p style="font-weight:600;font-size:14px;margin:0">{{ $message->sender->name }}</p>
                            <span style="font-size:12px;color:#6b7280">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="font-size:12px;color:#6b7280;margin:4px 0">{{ $message->sender_role }} • Request #{{ $message->request_id }}</p>
                        <p style="font-size:14px;margin:8px 0">{{ Str::limit($message->message, 100) }}</p>
                        <a href="{{ route('flight-dispatcher.messages.show-request', $message->request) }}" style="color:#3b82f6;text-decoration:none;font-size:12px;font-weight:500">
                            View Conversation →
                        </a>
                    </div>
                @empty
                    <p style="text-align:center;color:#6b7280;padding:24px 0">No unread messages</p>
                @endforelse
            </div>
            <div style="margin-top:16px">
                <a href="{{ route('flight-dispatcher.messages.index') }}" style="color:#3b82f6;text-decoration:none;font-size:14px;font-weight:500">
                    View All Messages →
                </a>
            </div>
        </div>
    </div>

    {{-- FLIGHT ASSESSMENT WORKFLOW --}}
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:12px;padding:28px;margin-bottom:24px;color:white">
        <h2 style="margin:0 0 12px;font-size:24px;font-weight:700">✈️ Aircraft Clearance Workflow</h2>
        <p style="margin:0;font-size:14px;opacity:0.9">Assess aircraft readiness and clear flights for departure</p>
    </div>

    {{-- Three Column Assessment Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:24px;margin-bottom:24px">
        
        {{-- Column 1: Awaiting Assessment --}}
        <div style="background:white;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);padding:24px">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                <div style="background:#fef3c7;padding:12px;border-radius:10px;font-size:24px">⏳</div>
                <div>
                    <h3 style="margin:0;font-size:18px;font-weight:700;color:#1a1a1a">Awaiting Assessment</h3>
                    <p style="margin:2px 0 0;font-size:13px;color:#6b7280">{{ $stats['awaiting_requests'] }} requests</p>
                </div>
            </div>
            
            @forelse($awaitingRequests as $request)
                <div style="border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:12px;transition:all 0.2s;cursor:pointer" onmouseover="this.style.borderColor='#f59e0b';this.style.boxShadow='0 4px 12px rgba(245,158,11,0.15)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px">
                        <div>
                            <p style="font-weight:700;color:#1a1a1a;margin:0;font-size:15px">Request #{{ $request->id }}</p>
                            <p style="font-size:13px;color:#6b7280;margin:4px 0 0">✈️ {{ $request->flight->flight_number }}</p>
                        </div>
                        <span style="padding:4px 10px;border-radius:8px;background:#fed7aa;color:#9a3412;font-size:11px;font-weight:600">URGENT</span>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;font-size:12px;color:#6b7280">
                        <div>📍 {{ $request->flight->destination }}</div>
                        <div>🕐 {{ \Carbon\Carbon::parse($request->flight->departure_time)->format('H:i') }}</div>
                        <div>👤 {{ $request->requester->name }}</div>
                        <div>📦 {{ $request->items->count() }} items</div>
                    </div>
                    
                    <a href="{{ route('flight-dispatcher.requests.show', $request) }}" style="display:block;text-align:center;background:#f59e0b;color:white;padding:10px;border-radius:8px;text-decoration:none;font-weight:600;font-size:13px;transition:background 0.2s" onmouseover="this.style.background='#d97706'" onmouseout="this.style.background='#f59e0b'">
                        🔍 Assess Aircraft →
                    </a>
                </div>
            @empty
                <div style="text-align:center;padding:40px 20px;color:#9ca3af">
                    <p style="font-size:48px;margin:0">✅</p>
                    <p style="margin:12px 0 0;font-size:14px">All requests assessed</p>
                </div>
            @endforelse
        </div>

        {{-- Column 2: Assessed - Pending Clearance --}}
        <div style="background:white;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);padding:24px">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                <div style="background:#dbeafe;padding:12px;border-radius:10px;font-size:24px">📋</div>
                <div>
                    <h3 style="margin:0;font-size:18px;font-weight:700;color:#1a1a1a">Pending Clearance</h3>
                    <p style="margin:2px 0 0;font-size:13px;color:#6b7280">{{ $stats['assessed_requests'] }} assessed</p>
                </div>
            </div>
            
            @forelse($assessedRequests as $request)
                <div style="border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:12px;transition:all 0.2s" onmouseover="this.style.borderColor='#3b82f6';this.style.boxShadow='0 4px 12px rgba(59,130,246,0.15)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px">
                        <div>
                            <p style="font-weight:700;color:#1a1a1a;margin:0;font-size:15px">Request #{{ $request->id }}</p>
                            <p style="font-size:13px;color:#6b7280;margin:4px 0 0">✈️ {{ $request->flight->flight_number }}</p>
                        </div>
                        <span style="padding:4px 10px;border-radius:8px;background:#dbeafe;color:#1e40af;font-size:11px;font-weight:600">ASSESSED</span>
                    </div>
                    
                    <div style="background:#f8fafc;padding:10px;border-radius:6px;margin-bottom:10px">
                        <p style="font-size:11px;color:#64748b;margin:0 0 4px;font-weight:600">Assessed by:</p>
                        <p style="font-size:12px;color:#334155;margin:0">{{ $request->flightDispatcherAssessor->name }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('flight-dispatcher.requests.clear-departure', $request) }}" style="margin:0">
                        @csrf
                        <textarea name="clearance_notes" placeholder="Clearance notes (optional)" style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px;font-size:12px;margin-bottom:10px;resize:vertical" rows="2"></textarea>
                        <button type="submit" onclick="return confirm('Clear flight {{ $request->flight->flight_number }} for departure?')" style="width:100%;background:#10b981;color:white;padding:10px;border-radius:8px;border:none;font-weight:600;font-size:13px;cursor:pointer;transition:background 0.2s" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                            ✅ Clear for Departure →
                        </button>
                    </form>
                </div>
            @empty
                <div style="text-align:center;padding:40px 20px;color:#9ca3af">
                    <p style="font-size:48px;margin:0">📋</p>
                    <p style="margin:12px 0 0;font-size:14px">No pending clearances</p>
                </div>
            @endforelse
        </div>

        {{-- Column 3: Cleared for Departure --}}
        <div style="background:white;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);padding:24px">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                <div style="background:#d1fae5;padding:12px;border-radius:10px;font-size:24px">✈️</div>
                <div>
                    <h3 style="margin:0;font-size:18px;font-weight:700;color:#1a1a1a">Cleared Flights</h3>
                    <p style="margin:2px 0 0;font-size:13px;color:#6b7280">{{ $stats['cleared_flights'] }} ready</p>
                </div>
            </div>
            
            @forelse($clearedFlights as $request)
                <div style="border:1px solid #10b981;border-radius:10px;padding:16px;margin-bottom:12px;background:linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%)">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px">
                        <div>
                            <p style="font-weight:700;color:#065f46;margin:0;font-size:15px">✅ Request #{{ $request->id }}</p>
                            <p style="font-size:13px;color:#047857;margin:4px 0 0">✈️ {{ $request->flight->flight_number }}</p>
                        </div>
                        <span style="padding:4px 10px;border-radius:8px;background:#10b981;color:white;font-size:11px;font-weight:600">CLEARED</span>
                    </div>
                    
                    <div style="background:white;padding:10px;border-radius:6px;margin-bottom:10px;border:1px solid #86efac">
                        <p style="font-size:11px;color:#047857;margin:0 0 4px;font-weight:600">Cleared by:</p>
                        <p style="font-size:12px;color:#065f46;margin:0">{{ $request->flightDispatcherAssessor->name }}</p>
                        <p style="font-size:11px;color:#6b7280;margin:4px 0 0">{{ $request->flight_cleared_for_departure_at->diffForHumans() }}</p>
                    </div>
                    
                    <div style="display:flex;align-items:center;gap:6px;background:white;padding:8px 12px;border-radius:6px;font-size:12px;color:#047857;font-weight:600">
                        <svg style="width:16px;height:16px" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Ready for Operations
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:40px 20px;color:#9ca3af">
                    <p style="font-size:48px;margin:0">🚀</p>
                    <p style="margin:12px 0 0;font-size:14px">No cleared flights yet</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
