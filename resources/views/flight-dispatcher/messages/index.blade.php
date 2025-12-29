@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>💬 Messages & Communications</h1>
        <a href="{{ route('flight-dispatcher.dashboard') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            ← Dashboard
        </a>
    </div>

    {{-- Filters --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <form method="GET" action="{{ route('flight-dispatcher.messages.index') }}">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">Filter by Status</label>
                    <select name="filter" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                        <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>All Messages</option>
                        <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>Unread Only</option>
                    </select>
                </div>
                
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:8px">From Role</label>
                    <select name="sender_role" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                        <option value="all">All Roles</option>
                        <option value="Cabin Crew" {{ request('sender_role') === 'Cabin Crew' ? 'selected' : '' }}>Cabin Crew</option>
                        <option value="Ramp Dispatcher" {{ request('sender_role') === 'Ramp Dispatcher' ? 'selected' : '' }}>Ramp Dispatcher</option>
                        <option value="Catering Staff" {{ request('sender_role') === 'Catering Staff' ? 'selected' : '' }}>Catering Staff</option>
                        <option value="Catering Incharge" {{ request('sender_role') === 'Catering Incharge' ? 'selected' : '' }}>Catering Incharge</option>
                    </select>
                </div>
                
                <div style="display:flex;align-items:end;gap:8px">
                    <button type="submit" style="flex:1;padding:10px;background:#3b82f6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                        🔍 Filter
                    </button>
                    <form method="POST" action="{{ route('flight-dispatcher.messages.mark-all-read') }}" style="flex:1">
                        @csrf
                        <button type="submit" style="width:100%;padding:10px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                            ✓ Mark All Read
                        </button>
                    </form>
                </div>
            </div>
        </form>
    </div>

    {{-- Messages List --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">All Messages</h3>
        <div style="display:flex;flex-direction:column;gap:12px">
            @forelse($messages as $message)
                <div style="border:1px solid #e5e7eb;border-left:4px solid {{ $message->message_type === 'urgent' ? '#ef4444' : ($message->is_read ? '#e5e7eb' : '#3b82f6') }};border-radius:8px;padding:16px;{{ !$message->is_read ? 'background:#f0f9ff' : '' }}">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px">
                        <div style="flex:1">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                                <p style="font-weight:600;margin:0">{{ $message->sender->name }}</p>
                                <span style="padding:2px 8px;border-radius:12px;background:#f3f4f6;color:#6b7280;font-size:12px">{{ $message->sender_role }}</span>
                                @if($message->message_type === 'urgent')
                                    <span style="padding:2px 8px;border-radius:12px;background:#fee2e2;color:#991b1b;font-size:12px">⚠️ URGENT</span>
                                @endif
                                @if(!$message->is_read)
                                    <span style="padding:2px 8px;border-radius:12px;background:#3b82f6;color:white;font-size:12px">New</span>
                                @endif
                            </div>
                            <p style="font-size:12px;color:#6b7280;margin:0">
                                Request #{{ $message->request_id }} • {{ $message->request->flight->flight_number ?? 'N/A' }} • 
                                {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$message->is_read)
                            <form method="POST" action="{{ route('flight-dispatcher.messages.mark-read', $message) }}">
                                @csrf
                                <button type="submit" style="padding:6px 12px;background:#10b981;color:white;border:none;border-radius:6px;font-size:12px;cursor:pointer">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <p style="margin:12px 0;font-size:14px">{{ $message->message }}</p>
                    
                    <a href="{{ route('flight-dispatcher.messages.show-request', $message->request) }}" style="color:#3b82f6;text-decoration:none;font-size:14px;font-weight:500">
                        View Full Conversation →
                    </a>
                </div>
            @empty
                <p style="text-align:center;color:#6b7280;padding:24px 0">No messages found</p>
            @endforelse
        </div>

        <div style="margin-top:20px">
            {{ $messages->appends(request()->query())->links() }}
        </div>
    </div>

@endsection
