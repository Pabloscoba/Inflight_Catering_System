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
                <form method="POST" action="{{ route('flight-dispatcher.requests.recommend', $request->id) }}" style="margin-top:10px">
                    @csrf
                    <button style="padding:8px 12px;background:linear-gradient(90deg,#06b6d4,#0ea5a4);color:white;border:none;border-radius:6px;cursor:pointer" onclick="return confirm('Recommend this request for dispatch to Flight Operations?')">Recommend Dispatch to Flight Operations</button>
                </form>
            @else
                <div style="margin-top:10px;padding:8px;border-radius:6px;background:#ecfeff;color:#065f46">Recommended on {{ optional($request->dispatcher_recommended_at)->format('Y-m-d H:i') }} by {{ optional($request->dispatcher_recommended_by ? App\Models\User::find($request->dispatcher_recommended_by) : null)->name }}</div>
            @endif
        @endcan

        @can('forward requests to flight purser')
            <form method="POST" action="{{ route('flight-dispatcher.requests.forward', $request->id) }}" style="margin-top:12px">
                @csrf
                <button style="padding:8px 12px;background:linear-gradient(90deg,#7c3aed,#6d28d9);color:white;border:none;border-radius:6px;cursor:pointer" onclick="return confirm('Forward Request #{{ $request->id }} to Flight Purser?')">Forward to Flight Purser</button>
            </form>
        @endcan
    </div>

@endsection
