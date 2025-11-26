@extends('layouts.app')

@section('content')
<div style="padding:24px;">
    <h2 style="font-size:24px;font-weight:600;margin-bottom:16px;">Pending Requests from Catering Staff</h2>

    @if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;margin-bottom:16px;">
        {{ session('error') }}
    </div>
    @endif

    @if($requests->count() > 0)
    <div style="background:white;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Request ID</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Flight</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Requester</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Date</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Items</th>
                    <th style="padding:12px;text-align:left;font-weight:600;color:#374151;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:12px;">#{{ $req->id }}</td>
                    <td style="padding:12px;">{{ $req->flight->flight_number }}<br><small style="color:#6b7280;">{{ $req->flight->origin }} → {{ $req->flight->destination }}</small></td>
                    <td style="padding:12px;">{{ $req->requester->name }}</td>
                    <td style="padding:12px;">{{ $req->requested_date }}</td>
                    <td style="padding:12px;">
                        <details>
                            <summary style="cursor:pointer;color:#2563eb;">{{ $req->items->count() }} items</summary>
                            <ul style="margin-top:8px;margin-left:16px;">
                                @foreach($req->items as $it)
                                    <li>{{ $it->product->name }} ({{ $it->quantity_requested }})</li>
                                @endforeach
                            </ul>
                        </details>
                    </td>
                    <td style="padding:12px;">
                        <form method="POST" action="{{ route('inventory-personnel.requests.forward-to-supervisor', $req) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit" onclick="return confirm('Forward request #{{ $req->id }} to Supervisor for approval?')" class="btn btn-sm" style="background:#2563eb;color:white;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;">Forward to Supervisor</button>
                        </form>
                        <a href="{{ route('admin.requests.show', $req) }}" class="btn btn-sm" style="margin-left:8px;background:#f3f4f6;color:#374151;padding:8px 12px;border-radius:6px;text-decoration:none;">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:12px;">{{ $requests->links() }}</div>
    </div>
    @else
    <div style="text-align:center;padding:40px;color:#6b7280;background:white;border-radius:8px;">
        No pending requests from Catering Staff at the moment.
    </div>
    @endif
</div>
@endsection
