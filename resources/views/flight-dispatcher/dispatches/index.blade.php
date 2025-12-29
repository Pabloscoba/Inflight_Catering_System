@extends('layouts.app')

@section('title', 'All Dispatch Records')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>📋 All Dispatch Records</h1>
        <div style="display:flex;gap:10px">
            <a href="{{ route('flight-dispatcher.dispatches.create') }}" style="padding:10px 16px;background:#10b981;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ➕ New Dispatch
            </a>
            <a href="{{ route('flight-dispatcher.dashboard') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
                ← Dashboard
            </a>
        </div>
    </div>

    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">ID</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Flight</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Route</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Departure</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Checklist</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Status</th>
                        <th style="padding:12px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispatches as $dispatch)
                        <tr style="border-bottom:1px solid #e5e7eb">
                            <td style="padding:12px;font-weight:600">#{{ $dispatch->id }}</td>
                            <td style="padding:12px">{{ $dispatch->flight->flight_number }}</td>
                            <td style="padding:12px">{{ $dispatch->flight->origin }} → {{ $dispatch->flight->destination }}</td>
                            <td style="padding:12px">{{ $dispatch->flight->departure_time->format('Y-m-d H:i') }}</td>
                            <td style="padding:12px">
                                <div style="display:flex;gap:4px">
                                    <span style="padding:4px 8px;border-radius:6px;font-size:12px;{{ $dispatch->fuel_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">⛽</span>
                                    <span style="padding:4px 8px;border-radius:6px;font-size:12px;{{ $dispatch->crew_readiness === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">👥</span>
                                    <span style="padding:4px 8px;border-radius:6px;font-size:12px;{{ $dispatch->catering_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">🍽️</span>
                                    <span style="padding:4px 8px;border-radius:6px;font-size:12px;{{ $dispatch->baggage_status === 'confirmed' ? 'background:#d1fae5;color:#065f46' : 'background:#f3f4f6;color:#6b7280' }}">🧳</span>
                                </div>
                            </td>
                            <td style="padding:12px">
                                @if($dispatch->overall_status === 'ready')
                                    <span style="padding:4px 12px;border-radius:12px;background:#d1fae5;color:#065f46;font-size:12px">Ready</span>
                                @elseif($dispatch->overall_status === 'in_progress')
                                    <span style="padding:4px 12px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:12px">In Progress</span>
                                @else
                                    <span style="padding:4px 12px;border-radius:12px;background:#f3f4f6;color:#374151;font-size:12px">{{ ucfirst($dispatch->overall_status) }}</span>
                                @endif
                            </td>
                            <td style="padding:12px">
                                <a href="{{ route('flight-dispatcher.dispatches.show', $dispatch) }}" style="color:#3b82f6;text-decoration:none;font-weight:500;margin-right:12px">
                                    👁️ View
                                </a>
                                <a href="{{ route('flight-dispatcher.dispatches.edit', $dispatch) }}" style="color:#10b981;text-decoration:none;font-weight:500">
                                    ✏️ Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:24px;text-align:center;color:#6b7280">No dispatch records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px">
            {{ $dispatches->links() }}
        </div>
    </div>

@endsection
