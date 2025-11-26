@extends('layouts.app')

@section('title', 'Supervisor Approved Requests')

@section('content')
<div style="padding:24px; max-width:1200px; margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h1>Supervisor Approved Requests</h1>
        <a href="{{ route('inventory-personnel.dashboard') }}" class="btn" style="background:#6b7280;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;">← Back</a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5;padding:12px;border-radius:8px;margin-bottom:12px;color:#065f46;">{{ session('success') }}</div>
    @endif

    @if($requests->count())
    <div style="background:white;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.04);">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Flight</th>
                    <th>Requested By</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>#{{ $req->id }}</td>
                    <td>{{ $req->flight->flight_number }}</td>
                    <td>{{ $req->requester->name }}</td>
                    <td>{{ optional($req->requested_date)->format('M d, Y') }}</td>
                    <td>
                        <details>
                            <summary>{{ $req->items->count() }} items</summary>
                            <ul style="padding-left:18px;margin:6px 0 0 0;">
                                @foreach($req->items as $it)
                                    <li>{{ $it->product->name }} ({{ $it->quantity }})</li>
                                @endforeach
                            </ul>
                        </details>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('inventory-personnel.requests.forward-to-security', $req) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit" onclick="return confirm('Forward request #{{ $req->id }} to Security for authentication?')" class="btn btn-sm" style="background:#2563eb;color:white;padding:8px 12px;border-radius:6px;border:none;">Forward to Security</button>
                        </form>
                        <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm" style="margin-left:8px;background:#f3f4f6;color:#374151;padding:8px 12px;border-radius:6px;text-decoration:none;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px;">{{ $requests->links() }}</div>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#6b7280;">No supervisor-approved requests at the moment.</div>
    @endif
</div>

@endsection
