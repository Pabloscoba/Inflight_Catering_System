@extends('layouts.app')

@section('title', 'Approve Stock Movements')

@section('content')
    <div class="content-header" style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 800; color: #1e3a8a; margin: 0;">Stock Movement Verification</h1>
        <p style="font-size: 15px; color: #64748b; margin-top: 4px;">Authorization queue for all incoming, issued, and
            returned inventory items.</p>
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

    @if($movements->count() > 0)

        <!-- Summary Cards -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 32px;">
            <div class="card-atcl" style="padding: 20px; background: #1e3a8a; color: white;">
                <div style="font-size: 12px; font-weight: 600; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pending Total</div>
                <div style="font-size: 32px; font-weight: 800; margin: 8px 0;">{{ $movements->total() }}</div>
                <div style="font-size: 11px; opacity: 0.7;">Active verification queue</div>
            </div>

            <div class="card-atcl" style="padding: 20px; border-bottom: 4px solid #10b981;">
                <div style="font-size: 12px; font-weight: 700; color: #10b981; text-transform: uppercase;">📥 Incoming</div>
                <div style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 8px 0;">
                    {{ $movements->where('type', 'incoming')->count() }}</div>
                <div style="font-size: 11px; color: #64748b;">+{{ $movements->where('type', 'incoming')->sum('quantity') }}
                    total units</div>
            </div>

            <div class="card-atcl" style="padding: 20px; border-bottom: 4px solid #3b82f6;">
                <div style="font-size: 12px; font-weight: 700; color: #3b82f6; text-transform: uppercase;">🔄 Transfers</div>
                <div style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 8px 0;">
                    {{ $movements->where('type', 'transfer_to_catering')->count() }}</div>
                <div style="font-size: 11px; color: #64748b;">
                    {{ $movements->where('type', 'transfer_to_catering')->sum('quantity') }} units assigned</div>
            </div>

            <div class="card-atcl" style="padding: 20px; border-bottom: 4px solid #f59e0b;">
                <div style="font-size: 12px; font-weight: 700; color: #f59e0b; text-transform: uppercase;">↗ Issues</div>
                <div style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 8px 0;">
                    {{ $movements->where('type', 'issued')->count() }}</div>
                <div style="font-size: 11px; color: #64748b;">-{{ $movements->where('type', 'issued')->sum('quantity') }} total
                    units</div>
            </div>

            <div class="card-atcl" style="padding: 20px; border-bottom: 4px solid #6366f1;">
                <div style="font-size: 12px; font-weight: 700; color: #6366f1; text-transform: uppercase;">↩ Returns</div>
                <div style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 8px 0;">
                    {{ $movements->where('type', 'returned')->count() }}</div>
                <div style="font-size: 11px; color: #64748b;">+{{ $movements->where('type', 'returned')->sum('quantity') }}
                    total units</div>
            </div>
        </div>

        <div class="card-atcl" style="padding: 24px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 1100px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Schedule</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Product Details</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Movement Type</th>
                        <th
                            style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Volume</th>
                        <th
                            style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Logistics Data</th>
                        <th
                            style="padding: 14px 16px; text-align: right; font-size: 12px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">
                            Management</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 16px; white-space: nowrap;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $movement->created_at->format('d M, Y') }}</div>
                                <div style="font-size: 11px; color: #94a3b8; font-weight: 600;">
                                    {{ $movement->created_at->format('H:i') }} UTC</div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 800; color: #0f172a;">{{ $movement->product->name }}</div>
                                <div style="font-size: 11px; color: #64748b;">SKU: {{ $movement->product->sku }}</div>
                            </td>
                            <td style="padding: 16px;">
                                @php
                                    $types = [
                                        'incoming' => ['label' => 'Incoming', 'color' => '#10b981', 'bg' => '#ecfdf5', 'icon' => '📥'],
                                        'issued' => ['label' => 'Issued', 'color' => '#f59e0b', 'bg' => '#fffbeb', 'icon' => '↗'],
                                        'transfer_to_catering' => ['label' => 'Transfer', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'icon' => '🔄'],
                                        'returned' => ['label' => 'Returned', 'color' => '#6366f1', 'bg' => '#eef2ff', 'icon' => '↩']
                                    ];
                                    $t = $types[$movement->type] ?? ['label' => $movement->type, 'color' => '#64748b', 'bg' => '#f8fafc', 'icon' => '•'];
                                @endphp
                                <span
                                    style="padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; background: {{ $t['bg'] }}; color: {{ $t['color'] }}; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px;">
                                    <span>{{ $t['icon'] }}</span> {{ $t['label'] }}
                                </span>
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <div
                                    style="font-size: 18px; font-weight: 800; {{ in_array($movement->type, ['incoming', 'returned']) ? 'color: #10b981;' : 'color: #ef4444;' }}">
                                    {{ in_array($movement->type, ['incoming', 'returned']) ? '+' : '-' }}{{ $movement->quantity }}
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-size: 13px; font-weight: 600; color: #334155;">{{ $movement->user->name }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">Ref: {{ $movement->reference_number ?? 'N/A' }}</div>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form action="{{ route('inventory-supervisor.approvals.movements.approve', $movement) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn-atcl"
                                            style="padding: 8px 16px; background: #059669; color: white; font-size: 12px;">Authorize</button>
                                    </form>
                                    <form action="{{ route('inventory-supervisor.approvals.movements.reject', $movement) }}"
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
                {{ $movements->links() }}
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
            <h3 style="font-size: 22px; font-weight: 800; color: #1e3a8a; margin-bottom: 8px;">Movement Queue Clear</h3>
            <p style="color: #64748b; font-size: 16px;">System reports no pending stock movements requiring supervisor
                authorization.</p>
        </div>
    @endif
@endsection