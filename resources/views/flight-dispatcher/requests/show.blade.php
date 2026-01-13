@extends('layouts.app')

@section('title', 'Assess Request #' . $request->id)

@section('content')
<div style="max-width:1200px;margin:0 auto">
    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <div>
            <h1 style="margin:0 0 8px;font-size:28px;font-weight:700;color:#1a1a1a">✈️ Aircraft Assessment</h1>
            <p style="margin:0;color:#6b7280;font-size:14px">Request #{{ $request->id }} • Flight {{ $request->flight->flight_number }}</p>
        </div>
        <a href="{{ route('flight-dispatcher.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#f3f4f6;color:#374151;border-radius:8px;text-decoration:none;font-weight:600;transition:all 0.2s" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Status Alert --}}
    @if($request->status === 'awaiting_flight_dispatcher')
        <div style="background:linear-gradient(135deg,#fef3c7 0%,#fed7aa 100%);border-left:4px solid #f59e0b;padding:16px 20px;border-radius:10px;margin-bottom:24px">
            <p style="margin:0;color:#92400e;font-weight:600;font-size:14px">⚠️ This request is awaiting your assessment before clearance</p>
        </div>
    @elseif($request->status === 'flight_dispatcher_assessed')
        <div style="background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);border-left:4px solid#3b82f6;padding:16px 20px;border-radius:10px;margin-bottom:24px">
            <p style="margin:0;color:#1e40af;font-weight:600;font-size:14px">📋 Assessment completed. Proceed with flight clearance below.</p>
        </div>
    @elseif($request->status === 'flight_cleared_for_departure')
        <div style="background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);border-left:4px solid #10b981;padding:16px 20px;border-radius:10px;margin-bottom:24px">
            <p style="margin:0;color:#065f46;font-weight:600;font-size:14px">✅ Flight cleared for departure on {{ $request->flight_cleared_for_departure_at->format('M d, Y H:i') }}</p>
        </div>
    @endif

    {{-- Main Grid --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">
        
        {{-- Left Column: Request Details --}}
        <div>
            {{-- Flight Information --}}
            <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px">
                <h3 style="margin:0 0 20px;font-size:18px;font-weight:700;color:#1a1a1a">✈️ Flight Information</h3>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px">
                    <div>
                        <p style="margin:0 0 4px;font-size:12px;color:#6b7280;font-weight:600">FLIGHT NUMBER</p>
                        <p style="margin:0;font-size:20px;font-weight:700;color:#1a1a1a">{{ $request->flight->flight_number }}</p>
                    </div>
                    <div>
                        <p style="margin:0 0 4px;font-size:12px;color:#6b7280;font-weight:600">AIRCRAFT TYPE</p>
                        <p style="margin:0;font-size:16px;font-weight:600;color:#374151">{{ $request->flight->aircraft_type }}</p>
                    </div>
                    <div>
                        <p style="margin:0 0 4px;font-size:12px;color:#6b7280;font-weight:600">ROUTE</p>
                        <p style="margin:0;font-size:16px;font-weight:600;color:#374151">{{ $request->flight->origin }} → {{ $request->flight->destination }}</p>
                    </div>
                    <div>
                        <p style="margin:0 0 4px;font-size:12px;color:#6b7280;font-weight:600">DEPARTURE TIME</p>
                        <p style="margin:0;font-size:16px;font-weight:600;color:#374151">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y H:i') }}</p>
                        @php
                            $hoursUntil = \Carbon\Carbon::parse($request->flight->departure_time)->diffInHours(now(), false);
                        @endphp
                        @if($hoursUntil > 0 && $hoursUntil < 6)
                            <p style="margin:4px 0 0;font-size:12px;color:#dc2626;font-weight:600">⚠️ Departing in {{ $hoursUntil }} hours</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Catering Items --}}
            <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px">
                <h3 style="margin:0 0 20px;font-size:18px;font-weight:700;color:#1a1a1a">📦 Catering Items ({{ $request->items->count() }})</h3>
                <div style="display:flex;flex-direction:column;gap:12px">
                    @foreach($request->items as $item)
                        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px;display:flex;justify-content:space-between;align-items:center">
                            <div>
                                <p style="margin:0;font-weight:600;color:#1a1a1a;font-size:15px">{{ $item->product->name }}</p>
                                <p style="margin:4px 0 0;font-size:12px;color:#6b7280">{{ $item->product->category->name ?? 'N/A' }}</p>
                            </div>
                            <div style="text-align:right">
                                <p style="margin:0;font-weight:700;color:#374151;font-size:18px">{{ $item->quantity_approved ?? $item->quantity_requested }}</p>
                                <p style="margin:2px 0 0;font-size:11px;color:#9ca3af">units</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Column: Assessment Form --}}
        <div>
            @if($request->status === 'awaiting_flight_dispatcher')
                {{-- Assessment Form --}}
                <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px">
                    <h3 style="margin:0 0 20px;font-size:18px;font-weight:700;color:#1a1a1a">🔍 Aircraft Assessment</h3>
                    
                    <form method="POST" action="{{ route('flight-dispatcher.requests.assess', $request) }}">
                        @csrf
                        
                        {{-- Aircraft Condition --}}
                        <div style="margin-bottom:16px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Aircraft Condition *</label>
                            <select name="aircraft_condition" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                                <option value="">Select condition</option>
                                <option value="good">✅ Good - Ready for flight</option>
                                <option value="fair">⚠️ Fair - Minor issues</option>
                                <option value="needs_attention">🔴 Needs Attention</option>
                            </select>
                        </div>

                        {{-- Fuel Status --}}
                        <div style="margin-bottom:16px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Fuel Status *</label>
                            <select name="fuel_status" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                                <option value="">Select status</option>
                                <option value="sufficient">⛽ Sufficient</option>
                                <option value="check_required">⚠️ Check Required</option>
                            </select>
                        </div>

                        {{-- Crew Readiness --}}
                        <div style="margin-bottom:16px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Crew Readiness *</label>
                            <select name="crew_readiness" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                                <option value="">Select status</option>
                                <option value="ready">👥 Ready</option>
                                <option value="not_ready">⏳ Not Ready</option>
                            </select>
                        </div>

                        {{-- Catering Check --}}
                        <div style="margin-bottom:16px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Catering Check *</label>
                            <select name="catering_check" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                                <option value="">Select status</option>
                                <option value="approved">✅ Approved</option>
                                <option value="needs_review">📋 Needs Review</option>
                            </select>
                        </div>

                        {{-- Assessment Notes --}}
                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Assessment Notes *</label>
                            <textarea name="assessment_notes" required rows="5" placeholder="Enter detailed assessment notes..." style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical"></textarea>
                        </div>

                        <button type="submit" style="width:100%;background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%);color:white;padding:14px;border-radius:8px;border:none;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.2s;box-shadow:0 4px 12px rgba(245,158,11,0.3)" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(245,158,11,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(245,158,11,0.3)'">
                            📋 Complete Assessment
                        </button>
                    </form>
                </div>
            @elseif($request->status === 'flight_dispatcher_assessed')
                {{-- Clearance Form --}}
                <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px">
                    <h3 style="margin:0 0 20px;font-size:18px;font-weight:700;color:#1a1a1a">✈️ Flight Clearance</h3>
                    
                    {{-- Assessment Summary --}}
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:20px">
                        <p style="margin:0 0 8px;font-size:12px;color:#64748b;font-weight:600">ASSESSED BY</p>
                        <p style="margin:0;font-weight:600;color:#334155">{{ $request->flightDispatcherAssessor->name }}</p>
                        <p style="margin:8px 0 0;font-size:12px;color:#64748b">{{ $request->flight_dispatcher_assessed_at->diffForHumans() }}</p>
                    </div>

                    @if($request->flight_clearance_notes)
                        <div style="background:#fffbeb;border-left:4px solid #f59e0b;padding:12px 16px;border-radius:6px;margin-bottom:20px">
                            <p style="margin:0;font-size:13px;color:#78350f;line-height:1.6">{{ $request->flight_clearance_notes }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('flight-dispatcher.requests.clear-departure', $request) }}" id="clearance-form">
                        @csrf
                        
                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:13px">Final Clearance Notes (Optional)</label>
                            <textarea name="clearance_notes" rows="4" placeholder="Add any final notes before clearing for departure..." style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical"></textarea>
                        </div>

                        <button type="button" onclick="showClearanceConfirmation('{{ $request->flight->flight_number }}')" style="width:100%;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:14px;border-radius:8px;border:none;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.2s;box-shadow:0 4px 12px rgba(16,185,129,0.3)" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(16,185,129,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(16,185,129,0.3)'">
                            ✅ Clear for Departure
                        </button>
                    </form>
                </div>
            @else
                {{-- Already Cleared --}}
                <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.08)">
                    <div style="text-align:center;padding:20px">
                        <p style="font-size:64px;margin:0">✅</p>
                        <h3 style="margin:16px 0 8px;font-size:20px;font-weight:700;color:#065f46">Flight Cleared</h3>
                        <p style="margin:0;color:#6b7280;font-size:14px">This flight has been cleared for departure</p>
                        
                        @if($request->flightDispatcherAssessor)
                            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:16px;margin:20px 0">
                                <p style="margin:0 0 4px;font-size:12px;color:#16a34a;font-weight:600">CLEARED BY</p>
                                <p style="margin:0;font-weight:600;color:#065f46">{{ $request->flightDispatcherAssessor->name }}</p>
                                <p style="margin:4px 0 0;font-size:12px;color:#16a34a">{{ $request->flight_cleared_for_departure_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Request History --}}
            <div style="background:white;border-radius:12px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,0.08)">
                <h4 style="margin:0 0 16px;font-size:15px;font-weight:700;color:#1a1a1a">📜 Request History</h4>
                <div style="display:flex;flex-direction:column;gap:12px;font-size:13px">
                    <div style="display:flex;align-items:start;gap:10px">
                        <div style="width:6px;height:6px;background:#10b981;border-radius:50%;margin-top:6px"></div>
                        <div>
                            <p style="margin:0;color:#6b7280">Created by {{ $request->requester->name }}</p>
                            <p style="margin:2px 0 0;font-size:11px;color:#9ca3af">{{ $request->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($request->dispatched_at)
                        <div style="display:flex;align-items:start;gap:10px">
                            <div style="width:6px;height:6px;background:#3b82f6;border-radius:50%;margin-top:6px"></div>
                            <div>
                                <p style="margin:0;color:#6b7280">Dispatched by Ramp Agent</p>
                                <p style="margin:2px 0 0;font-size:11px;color:#9ca3af">{{ $request->dispatched_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($request->flight_dispatcher_assessed_at)
                        <div style="display:flex;align-items:start;gap:10px">
                            <div style="width:6px;height:6px;background:#f59e0b;border-radius:50%;margin-top:6px"></div>
                            <div>
                                <p style="margin:0;color:#6b7280">Assessed by {{ $request->flightDispatcherAssessor->name }}</p>
                                <p style="margin:2px 0 0;font-size:11px;color:#9ca3af">{{ $request->flight_dispatcher_assessed_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($request->flight_cleared_for_departure_at)
                        <div style="display:flex;align-items:start;gap:10px">
                            <div style="width:6px;height:6px;background:#10b981;border-radius:50%;margin-top:6px"></div>
                            <div>
                                <p style="margin:0;color:#6b7280;font-weight:600">✈️ Cleared for Departure</p>
                                <p style="margin:2px 0 0;font-size:11px;color:#9ca3af">{{ $request->flight_cleared_for_departure_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Clearance Confirmation Modal --}}
<div id="clearanceModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:white;padding:24px;border-radius:12px;max-width:450px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2)">
        <h3 style="margin:0 0 12px;font-size:18px;font-weight:700">✈️ Confirm Flight Clearance</h3>
        <p id="clearanceMessage" style="color:#6b7280;margin:0 0 8px"></p>
        <p style="color:#9ca3af;margin:0 0 20px;font-size:13px">This will notify Flight Purser and Cabin Crew that operations can begin.</p>
        <div style="display:flex;gap:12px;justify-content:flex-end">
            <button onclick="closeClearanceModal()" style="padding:10px 20px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Cancel
            </button>
            <button onclick="submitClearanceForm()" style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                Confirm Clearance
            </button>
        </div>
    </div>
</div>

<script>
    function showClearanceConfirmation(flightNumber) {
        document.getElementById('clearanceMessage').textContent = 'Clear Flight ' + flightNumber + ' for departure?';
        document.getElementById('clearanceModal').style.display = 'flex';
    }

    function closeClearanceModal() {
        document.getElementById('clearanceModal').style.display = 'none';
    }

    function submitClearanceForm() {
        document.getElementById('clearance-form').submit();
    }
</script>

@endsection
