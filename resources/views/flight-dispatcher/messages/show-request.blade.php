@extends('layouts.app')

@section('title', 'Request Conversation')

@section('content')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h1>💬 Request #{{ $requestModel->id }} - Conversation</h1>
        <a href="{{ route('flight-dispatcher.messages.index') }}" style="padding:10px 16px;background:#6b7280;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            ← Back to Messages
        </a>
    </div>

    {{-- Request Info --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Request Information</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Flight</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $requestModel->flight->flight_number }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Route</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $requestModel->flight->origin }} → {{ $requestModel->flight->destination }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Requester</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $requestModel->requester->name }}</p>
            </div>
            <div>
                <p style="color:#6b7280;font-size:14px;margin:0">Items</p>
                <p style="font-weight:600;margin:4px 0 0">{{ $requestModel->items->count() }} items</p>
            </div>
        </div>
    </div>

    {{-- Conversation Thread --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Conversation Thread</h3>
        <div style="display:flex;flex-direction:column;gap:16px;max-height:500px;overflow-y:auto;padding:16px;background:#f9fafb;border-radius:8px">
            @forelse($messages as $message)
                <div style="display:flex;flex-direction:column;align-items:{{ $message->sender_role === 'Flight Dispatcher' ? 'flex-end' : 'flex-start' }}">
                    <div style="max-width:70%;padding:12px 16px;border-radius:12px;{{ $message->sender_role === 'Flight Dispatcher' ? 'background:#3b82f6;color:white' : 'background:white;border:1px solid #e5e7eb' }}">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                            <p style="font-weight:600;font-size:14px;margin:0;{{ $message->sender_role === 'Flight Dispatcher' ? 'color:white' : 'color:#111' }}">{{ $message->sender->name }}</p>
                            <span style="font-size:12px;{{ $message->sender_role === 'Flight Dispatcher' ? 'color:#bfdbfe' : 'color:#6b7280' }}">{{ $message->sender_role }}</span>
                        </div>
                        <p style="margin:8px 0 0;font-size:14px">{{ $message->message }}</p>
                        <p style="margin:8px 0 0;font-size:12px;{{ $message->sender_role === 'Flight Dispatcher' ? 'color:#bfdbfe' : 'color:#6b7280' }}">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            @empty
                <p style="text-align:center;color:#6b7280;padding:24px 0">No messages yet</p>
            @endforelse
        </div>
    </div>

    {{-- Send Message Form --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;margin-bottom:24px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">Send Message</h3>
        <form method="POST" action="{{ route('flight-dispatcher.messages.send') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $requestModel->id }}">
            
            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Send To</label>
                <select name="recipient_role" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                    <option value="">-- Select Recipient --</option>
                    <option value="Cabin Crew">Cabin Crew</option>
                    <option value="Ramp Dispatcher">Ramp Dispatcher</option>
                    <option value="Catering Staff">Catering Staff</option>
                    <option value="Catering Incharge">Catering Incharge</option>
                    <option value="Flight Purser">Flight Purser</option>
                </select>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Message Type</label>
                <select name="message_type" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
                    <option value="general">General</option>
                    <option value="urgent">Urgent</option>
                    <option value="confirmation">Confirmation</option>
                    <option value="query">Query</option>
                </select>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Message</label>
                <textarea name="message" required placeholder="Type your message..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="4"></textarea>
            </div>

            <button type="submit" style="padding:12px 24px;background:#3b82f6;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                📤 Send Message
            </button>
        </form>
    </div>

    {{-- Send Delay Report Form --}}
    <div style="background:white;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px">
        <h3 style="margin:0 0 16px;font-size:18px;font-weight:600">⚠️ Send Delay Report</h3>
        <form method="POST" action="{{ route('flight-dispatcher.messages.delay-report', $requestModel) }}">
            @csrf
            
            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Delay Reason</label>
                <textarea name="delay_reason" required placeholder="Describe the delay..." style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px" rows="3"></textarea>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Estimated Delay (minutes)</label>
                <input type="number" name="estimated_delay_minutes" min="0" placeholder="e.g., 30" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px">
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-weight:600;margin-bottom:8px">Notify Teams (select all that apply)</label>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="notify_roles[]" value="Cabin Crew">
                        <span>Cabin Crew</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="notify_roles[]" value="Ramp Dispatcher">
                        <span>Ramp Dispatcher</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="notify_roles[]" value="Catering Staff">
                        <span>Catering Staff</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="notify_roles[]" value="Catering Incharge">
                        <span>Catering Incharge</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="notify_roles[]" value="Flight Purser">
                        <span>Flight Purser</span>
                    </label>
                </div>
            </div>

            <button type="submit" style="padding:12px 24px;background:#ef4444;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                ⚠️ Send Delay Report
            </button>
        </form>
    </div>

@endsection
