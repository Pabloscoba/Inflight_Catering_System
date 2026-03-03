@extends('layouts.app')

@section('title', 'Verify Catering Requests')

    @section('content')
        <div class="content-header" style="margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <h1 style="font-size: 28px; font-weight: 800; color: #1e3a8a; margin: 0;">Order Clearance Queue</h1>
                    <p style="font-size: 15px; color: #64748b; margin-top: 4px;">Authorization of catering orders approved by
                        Incharge personnel.</p>
                </div>
                <a href="{{ route('inventory-supervisor.dashboard') }}" class="btn-atcl btn-atcl-secondary"
                    style="padding: 10px 20px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div
                style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span style="font-weight: 600;">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div
                style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span style="font-weight: 600;">{{ session('error') }}</span>
            </div>
        @endif

        @if($requests->count() > 0)
            <div class="card-atcl" style="padding: 24px; overflow-x: auto;">
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0;">{{ $requests->total() }} Orders
                        Awaiting Review</h3>
                </div>

                <table style="width: 100%; border-collapse: collapse; min-width: 1000px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                            <th
                                style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Reference</th>
                            <th
                                style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Flight Details</th>
                            <th
                                style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Requester</th>
                            <th
                                style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Item Inventory</th>
                            <th
                                style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Status</th>
                            <th
                                style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase;">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 16px;">
                                    <div style="font-weight: 800; color: #0f172a;">
                                        #REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div style="font-size: 11px; color: #64748b;">{{ $request->requested_date->format('d M, Y') }}</div>
                                </td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 700; color: #1e293b;">{{ $request->flight->flight_number }}</div>
                                    <div style="font-size: 12px; color: #94a3b8;">{{ $request->flight->origin }} →
                                        {{ $request->flight->destination }}</div>
                                </td>
                                <td style="padding: 16px; font-size: 14px; color: #334155; font-weight: 500;">
                                    {{ $request->requester->name }}</td>
                                <td style="padding: 16px;">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $request->items->count() }} items</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        @foreach($request->items->take(1) as $item)
                                            {{ $item->product->name }}...
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding: 16px;">
                                    <span
                                        style="background: #f0fdf4; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; text-transform: uppercase; border: 1px solid #dcfce7;">
                                        Incharge Approved
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="{{ route('inventory-supervisor.requests.show', $request) }}"
                                            class="btn-atcl btn-atcl-secondary" style="padding: 6px 14px; font-size: 12px;">Review</a>
                                        <button onclick="document.getElementById('approve-{{ $request->id }}').style.display='flex'"
                                            class="btn-atcl"
                                            style="padding: 6px 14px; background: #059669; color: white; font-size: 12px;">Quick
                                            Approve</button>
                                        <button onclick="document.getElementById('reject-{{ $request->id }}').style.display='flex'"
                                            class="btn-atcl"
                                            style="padding: 6px 14px; background: #dc2626; color: white; font-size: 12px;">Reject</button>
                                    </div>

                                    <!-- Modals remain for functionality but with ATCL styling -->
                                    <div id="approve-{{ $request->id }}"
                                        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);"
                                        onclick="this.style.display='none'">
                                        <div class="card-atcl"
                                            style="padding: 32px; max-width: 480px; width: 90%; background: #ffffff; text-align: left;"
                                            onclick="event.stopPropagation()">
                                            <h4 style="font-size: 20px; font-weight: 800; color: #1e3a8a; margin-bottom: 12px;">Confirm
                                                Authorization</h4>
                                            <p style="color: #64748b; font-size: 15px; margin-bottom: 24px;">Are you sure you want to
                                                approve Order <strong>#REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</strong>?
                                                This will release items for inventory processing.</p>
                                            <form action="{{ route('inventory-supervisor.requests.approve', $request) }}" method="POST">
                                                @csrf
                                                <div style="display: flex; gap: 12px;">
                                                    <button type="button"
                                                        onclick="document.getElementById('approve-{{ $request->id }}').style.display='none'"
                                                        class="btn-atcl btn-atcl-secondary"
                                                        style="flex: 1; padding: 12px;">Cancel</button>
                                                    <button type="submit" class="btn-atcl"
                                                        style="flex: 1; padding: 12px; background: #1e3a8a; color: white;">Confirm
                                                        Approval</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="reject-{{ $request->id }}"
                                        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);"
                                        onclick="this.style.display='none'">
                                        <div class="card-atcl"
                                            style="padding: 32px; max-width: 480px; width: 90%; background: #ffffff; text-align: left;"
                                            onclick="event.stopPropagation()">
                                            <h4 style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-bottom: 12px;">Reject
                                                Order Request</h4>
                                            <form action="{{ route('inventory-supervisor.requests.reject', $request) }}" method="POST">
                                                @csrf
                                                <div style="margin-bottom: 20px;">
                                                    <label class="label-atcl" style="margin-bottom: 8px; display: block;">Official
                                                        Reason for Rejection</label>
                                                    <textarea name="rejection_reason" required class="input-atcl"
                                                        placeholder="Enter reason here..." style="min-height: 120px;"></textarea>
                                                </div>
                                                <div style="display: flex; gap: 12px;">
                                                    <button type="button"
                                                        onclick="document.getElementById('reject-{{ $request->id }}').style.display='none'"
                                                        class="btn-atcl btn-atcl-secondary"
                                                        style="flex: 1; padding: 12px;">Cancel</button>
                                                    <button type="submit" class="btn-atcl"
                                                        style="flex: 1; padding: 12px; background: #b91c1c; color: white;">Confirm
                                                        Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                    {{ $requests->links() }}
                </div>
            </div>
        @else
            <div class="card-atcl" style="padding: 80px 40px; text-align: center;">
                <div
                    style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg style="width: 40px; height: 40px; color: #cbd5e1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 style="font-size: 22px; font-weight: 800; color: #1e3a8a; margin-bottom: 8px;">Order Queue Empty</h3>
                <p style="color: #64748b; font-size: 16px;">All pending catering orders have been processed and authorized.</p>
            </div>
        @endif
    @endsection
@endsection