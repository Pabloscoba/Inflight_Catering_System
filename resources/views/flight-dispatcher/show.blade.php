@extends('layouts.app')

@section('title', 'Flight Dispatcher - Request')

@section('content')
    <h1>Request #{{ $request->id }}</h1>

    <p><strong>Flight:</strong> {{ $request->flight->flight_number ?? 'N/A' }}</p>
    <p><strong>Requester:</strong> {{ $request->requester->name ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $request->status }}</p>

    <h3>Items</h3>
    <ul>
        @foreach($request->items as $item)
            <li>{{ $item->product->name }} — Qty: {{ $item->quantity }}</li>
        @endforeach
    </ul>

    <div style="margin-top:18px">
        @can('comment on request')
            <form method="POST" action="{{ route('flight-dispatcher.requests.comment', $request->id) }}">
                @csrf
                <label for="comment"><strong>Dispatcher Comment</strong></label>
                <textarea name="comment" id="comment" rows="4" style="width:100%;margin-top:6px;padding:8px;border:1px solid #e5e7eb;border-radius:6px">{{ old('comment', $request->dispatcher_comments ?? '') }}</textarea>
                <div style="margin-top:8px">
                    <button style="padding:8px 12px;background:#0ea5a4;color:white;border:none;border-radius:6px;cursor:pointer">Save Comment</button>
                </div>
            </form>
        @endcan

        @can('recommend dispatch to flight operations')
            @if(!$request->dispatcher_recommended)
                <form method="POST" action="{{ route('flight-dispatcher.requests.recommend', $request->id) }}" style="margin-top:10px" id="recommend-form">
                    @csrf
                    <button type="button" onclick="showRecommendConfirmation()" style="padding:8px 12px;background:linear-gradient(90deg,#06b6d4,#0ea5a4);color:white;border:none;border-radius:6px;cursor:pointer">Recommend Dispatch to Flight Operations</button>
                </form>
            @else
                <div style="margin-top:10px;padding:8px;border-radius:6px;background:#ecfeff;color:#065f46">Recommended on {{ optional($request->dispatcher_recommended_at)->format('Y-m-d H:i') }} by {{ optional($request->dispatcher_recommended_by ? App\Models\User::find($request->dispatcher_recommended_by) : null)->name }}</div>
            @endif
        @endcan

        @can('forward requests to flight purser')
            <form method="POST" action="{{ route('flight-dispatcher.requests.forward', $request->id) }}" style="margin-top:12px" id="forward-form">
                @csrf
                <button type="button" onclick="showForwardConfirmation({{ $request->id }})" style="padding:8px 12px;background:linear-gradient(90deg,#7c3aed,#6d28d9);color:white;border:none;border-radius:6px;cursor:pointer">Forward to Flight Purser</button>
            </form>
        @endcan
    </div>

    {{-- Confirmation Modals --}}
    <div id="recommendModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
        <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2)">
            <h3 style="margin:0 0 12px;font-size:18px;font-weight:700">Confirm Recommendation</h3>
            <p style="color:#6b7280;margin:0 0 20px">Recommend this request for dispatch to Flight Operations?</p>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <button onclick="closeRecommendModal()" style="padding:10px 20px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Cancel
                </button>
                <button onclick="submitRecommendForm()" style="padding:10px 20px;background:#06b6d4;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <div id="forwardModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
        <div style="background:white;padding:24px;border-radius:12px;max-width:400px;width:90%;box-shadow:0 4px 20px rgba(0,0,0,0.2)">
            <h3 style="margin:0 0 12px;font-size:18px;font-weight:700">Forward to Flight Purser</h3>
            <p id="forwardMessage" style="color:#6b7280;margin:0 0 20px"></p>
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <button onclick="closeForwardModal()" style="padding:10px 20px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Cancel
                </button>
                <button onclick="submitForwardForm()" style="padding:10px 20px;background:#7c3aed;color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer">
                    Forward
                </button>
            </div>
        </div>
    </div>

    <script>
        function showRecommendConfirmation() {
            document.getElementById('recommendModal').style.display = 'flex';
        }

        function closeRecommendModal() {
            document.getElementById('recommendModal').style.display = 'none';
        }

        function submitRecommendForm() {
            document.getElementById('recommend-form').submit();
        }

        function showForwardConfirmation(requestId) {
            document.getElementById('forwardMessage').textContent = 'Forward Request #' + requestId + ' to Flight Purser?';
            document.getElementById('forwardModal').style.display = 'flex';
        }

        function closeForwardModal() {
            document.getElementById('forwardModal').style.display = 'none';
        }

        function submitForwardForm() {
            document.getElementById('forward-form').submit();
        }
    </script>

@endsection
