@extends('layouts.app')

@section('title', 'Manage Product Approvals')

@section('content')
    <div class="content-header" style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 800; color: #1e3a8a; margin: 0;">Product Queue Verification</h1>
        <p style="font-size: 15px; color: #64748b; margin-top: 4px;">Detailed review of system products awaiting supervisor
            clearance.</p>
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

    @if($products->count() > 0)
        <div class="card-atcl" style="padding: 24px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Identity</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Classification</th>
                        <th
                            style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Valuation</th>
                        <th
                            style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Inventory State</th>
                        <th
                            style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Management</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 16px;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $product->name }}</div>
                                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">
                                    {{ $product->sku }}</div>
                            </td>
                            <td style="padding: 16px; font-size: 14px; color: #334155; font-weight: 500;">
                                {{ $product->category->name }}</td>
                            <td style="padding: 16px; text-align: center;">
                                <span
                                    style="font-weight: 700; color: #1e293b; background: #f1f5f9; padding: 4px 10px; border-radius: 6px;">
                                    {{ $product->currency }} {{ number_format($product->unit_price, 2) }}
                                </span>
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $product->quantity_in_stock }}
                                    {{ $product->unit_of_measure }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">Min: {{ $product->reorder_level }}</div>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form action="{{ route('inventory-supervisor.approvals.products.approve', $product) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl"
                                            style="padding: 8px 16px; background: #059669; color: white; font-size: 12px;">Verify
                                            Entry</button>
                                    </form>
                                    <form action="{{ route('inventory-supervisor.approvals.products.reject', $product) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl"
                                            style="padding: 8px 16px; background: #dc2626; color: white; font-size: 12px;">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                {{ $products->links() }}
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
            <h3 style="font-size: 22px; font-weight: 800; color: #1e3a8a; margin-bottom: 8px;">Product Queue Clear</h3>
            <p style="color: #64748b; font-size: 16px;">There are no pending products requiring verification at this time.</p>
        </div>
    @endif
@endsection