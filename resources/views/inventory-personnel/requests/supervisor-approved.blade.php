@extends('layouts.app')

@section('title', 'Supervisor Approved Requests')

@section('content')
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px 0;">
                ✅ Supervisor Approved Requests
            </h1>
            <p style="color: #6b7280; margin: 0; font-size: 14px;">
                These requests have been approved by Inventory Supervisor and are ready to be issued and forwarded to Security
            </p>
        </div>
        <a href="{{ route('inventory-personnel.dashboard') }}" 
           style="display: inline-block; padding: 10px 20px; background: #6b7280; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
            ← Back to Dashboard
        </a>
    </div>

    @if(session('success'))
    <div style="background: #d1fae5; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; color: #065f46; font-weight: 500; border-left: 4px solid #10b981;">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #fee2e2; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; color: #991b1b; font-weight: 500; border-left: 4px solid #ef4444;">
        ❌ {{ session('error') }}
    </div>
    @endif

    @if($requests->count())
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
        <!-- Stats Bar -->
        <div style="padding: 16px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">Total Requests Awaiting Action</div>
                <div style="font-size: 32px; font-weight: 700;">{{ $requests->total() }}</div>
            </div>
            <svg style="width: 64px; height: 64px; opacity: 0.3;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <!-- Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 16px 20px; text-align: left; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Request</th>
                        <th style="padding: 16px 20px; text-align: left; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Flight Details</th>
                        <th style="padding: 16px 20px; text-align: left; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Products Requested</th>
                        <th style="padding: 16px 20px; text-align: left; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Requested By</th>
                        <th style="padding: 16px 20px; text-align: left; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Approved By</th>
                        <th style="padding: 16px 20px; text-align: center; font-weight: 700; color: #374151; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" 
                        onmouseover="this.style.background='#fef3c7'" 
                        onmouseout="this.style.background='white'">
                        
                        <!-- Request ID & Status -->
                        <td style="padding: 18px 20px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <span style="font-weight: 700; color: #1e40af; font-size: 16px;">#{{ $req->id }}</span>
                                <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                                    ✅ SUPERVISOR APPROVED
                                </span>
                            </div>
                            <div style="font-size: 12px; color: #9ca3af;">
                                Requested: {{ optional($req->requested_date)->format('M d, Y H:i') }}
                            </div>
                            <div style="font-size: 12px; color: #9ca3af;">
                                Approved: {{ optional($req->approved_date)->format('M d, Y H:i') }}
                            </div>
                        </td>

                        <!-- Flight Details -->
                        <td style="padding: 18px 20px;">
                            <div style="font-weight: 700; color: #1a1a1a; font-size: 15px; margin-bottom: 6px;">
                                ✈️ {{ $req->flight->flight_number ?? 'N/A' }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6b7280; margin-bottom: 4px;">
                                <svg style="width: 14px; height: 14px; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span style="font-weight: 600;">{{ $req->flight->origin ?? 'N/A' }}</span>
                                <svg style="width: 14px; height: 14px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <span style="font-weight: 600;">{{ $req->flight->destination ?? 'N/A' }}</span>
                            </div>
                            <div style="font-size: 12px; color: #9ca3af;">
                                🕐 Departure: {{ optional($req->flight->departure_time)->format('H:i, M d') ?? 'N/A' }}
                            </div>
                        </td>

                        <!-- Products List -->
                        <td style="padding: 18px 20px;">
                            <div style="background: #f9fafb; padding: 12px; border-radius: 8px; border-left: 3px solid #667eea;">
                                <div style="font-size: 11px; color: #6b7280; font-weight: 700; margin-bottom: 8px; text-transform: uppercase;">
                                    📦 {{ $req->items->count() }} {{ $req->items->count() == 1 ? 'Item' : 'Items' }} Requested
                                </div>
                                @foreach($req->items as $item)
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; padding: 6px; background: white; border-radius: 6px;">
                                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 13px; min-width: 50px; text-align: center;">
                                        × {{ $item->quantity_approved ?? $item->quantity_requested }}
                                    </span>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1a1a1a; font-size: 13px;">{{ $item->product->name ?? 'N/A' }}</div>
                                        <div style="font-size: 11px; color: #9ca3af;">{{ $item->product->category->name ?? '' }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Requested By -->
                        <td style="padding: 18px 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                    {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;">{{ $req->requester->name ?? 'Unknown' }}</div>
                                    <div style="font-size: 12px; color: #6b7280;">{{ $req->requester->roles->first()->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Approved By -->
                        <td style="padding: 18px 20px;">
                            @if($req->approver)
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                    {{ strtoupper(substr($req->approver->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1a1a1a; font-size: 14px;">{{ $req->approver->name }}</div>
                                    <div style="font-size: 12px; color: #10b981; font-weight: 600;">✅ Inventory Supervisor</div>
                                </div>
                            </div>
                            @else
                            <div style="font-size: 13px; color: #9ca3af; font-style: italic;">Not yet approved</div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td style="padding: 18px 20px; text-align: center;">
                            <div style="display: flex; flex-direction: column; gap: 8px; align-items: center;">
                                <form method="POST" action="{{ route('inventory-personnel.requests.forward-to-security', $req) }}" style="width: 100%;">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('⚠️ Forward Request #{{ $req->id }} to Security?\n\nThis action will:\n✓ Issue stock for {{ $req->items->count() }} item(s)\n✓ Send request to Security for authentication\n✓ Update request status to Ready for Dispatch\n\nContinue?')" 
                                            style="width: 100%; padding: 10px 16px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3); transition: transform 0.2s;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)'" 
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.3)'">
                                        🔒 Forward to Security
                                    </button>
                                </form>
                                <a href="{{ route('admin.requests.show', $req) }}" 
                                   style="width: 100%; padding: 8px 16px; background: #f3f4f6; color: #374151; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; text-align: center; display: block; transition: background 0.2s;"
                                   onmouseover="this.style.background='#e5e7eb'" 
                                   onmouseout="this.style.background='#f3f4f6'">
                                    👁️ View Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
            {{ $requests->links() }}
        </div>
    </div>
    @else
    <div style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 60px; text-align: center;">
        <svg style="width: 80px; height: 80px; margin: 0 auto 20px; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 style="font-size: 20px; font-weight: 700; color: #6b7280; margin: 0 0 12px 0;">No Supervisor-Approved Requests</h3>
        <p style="color: #9ca3af; margin: 0; font-size: 14px;">All requests have been processed. New supervisor-approved requests will appear here.</p>
    </div>
    @endif
</div>

@endsection
