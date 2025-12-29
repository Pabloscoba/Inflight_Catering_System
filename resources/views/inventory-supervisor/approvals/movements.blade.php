@extends('layouts.app')

@section('title', 'Approve Stock Movements')

@section('content')
<div class="content-header">
    <h1>Pending Stock Movements for Approval</h1>
    <p>Review and approve stock movements from Inventory Personnel</p>
</div>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #c3e6cb;">
    ✓ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #f8d7da; color: #721c24; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #f5c6cb;">
    ✗ {{ session('error') }}
</div>
@endif

@if($movements->count() > 0)

<!-- Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 6px;">Total Pending</div>
        <div style="font-size: 28px; font-weight: 700;">{{ $movements->total() }}</div>
        <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">Awaiting approval</div>
    </div>
    
    <div style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); padding: 20px; border-radius: 12px; color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="font-size: 13px; opacity: 0.8; margin-bottom: 6px;">📥 Incoming</div>
        <div style="font-size: 28px; font-weight: 700;">{{ $movements->where('type', 'incoming')->count() }}</div>
        <div style="font-size: 11px; opacity: 0.7; margin-top: 4px;">+{{ $movements->where('type', 'incoming')->sum('quantity') }} units</div>
    </div>
    
    <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); padding: 20px; border-radius: 12px; color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="font-size: 13px; opacity: 0.8; margin-bottom: 6px;">🔄 Transfers</div>
        <div style="font-size: 28px; font-weight: 700;">{{ $movements->where('type', 'transfer_to_catering')->count() }}</div>
        <div style="font-size: 11px; opacity: 0.7; margin-top: 4px;">{{ $movements->where('type', 'transfer_to_catering')->sum('quantity') }} units to catering</div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); padding: 20px; border-radius: 12px; color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="font-size: 13px; opacity: 0.8; margin-bottom: 6px;">↗ Issues</div>
        <div style="font-size: 28px; font-weight: 700;">{{ $movements->where('type', 'issued')->count() }}</div>
        <div style="font-size: 11px; opacity: 0.7; margin-top: 4px;">-{{ $movements->where('type', 'issued')->sum('quantity') }} units issued</div>
    </div>
    
    <div style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); padding: 20px; border-radius: 12px; color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="font-size: 13px; opacity: 0.8; margin-bottom: 6px;">↩ Returns</div>
        <div style="font-size: 28px; font-weight: 700;">{{ $movements->where('type', 'returned')->count() }}</div>
        <div style="font-size: 11px; opacity: 0.7; margin-top: 4px;">+{{ $movements->where('type', 'returned')->sum('quantity') }} units returned</div>
    </div>
</div>

<div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e9ecef;">
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Date</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Product</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Type</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Quantity</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Reference</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Created By</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Notes</th>
                <th style="padding: 14px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #495057; text-transform: uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
            <tr style="border-bottom: 1px solid #f0f0f0; transition: background 0.2s;">
                <td style="padding: 14px 16px; font-size: 14px; white-space: nowrap;">
                    {{ $movement->created_at->format('M d, Y') }}<br>
                    <span style="font-size: 12px; color: #999;">{{ $movement->created_at->format('H:i') }}</span>
                </td>
                <td style="padding: 14px 16px; font-size: 14px; font-weight: 500;">{{ $movement->product->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">
                    @if($movement->type == 'incoming')
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">
                                📥 Incoming
                            </span>
                        </div>
                        <small style="color: #28a745; font-size: 11px; display: block; margin-top: 4px;">✓ Adds to main stock</small>
                    @elseif($movement->type == 'issued')
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #856404;">
                                ↗ Issued
                            </span>
                        </div>
                        <small style="color: #d97706; font-size: 11px; display: block; margin-top: 4px;">⚠ Removes from stock</small>
                    @elseif($movement->type == 'transfer_to_catering')
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #dbeafe; color: #1e40af;">
                                🔄 Transfer
                            </span>
                        </div>
                        <small style="color: #0891b2; font-size: 11px; display: block; margin-top: 4px;">→ Main to Catering</small>
                    @else
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #e0e7ff; color: #4338ca;">
                                ↩ Returns
                            </span>
                        </div>
                        <small style="color: #6366f1; font-size: 11px; display: block; margin-top: 4px;">✓ Adds back to stock</small>
                    @endif
                </td>
                <td style="padding: 14px 16px; font-size: 16px; text-align: center; font-weight: 700; 
                    @if($movement->type == 'incoming' || $movement->type == 'returned')
                        color: #28a745;
                    @elseif($movement->type == 'transfer_to_catering')
                        color: #0891b2;
                    @else
                        color: #dc3545;
                    @endif">
                    @if($movement->type == 'incoming' || $movement->type == 'returned')
                        +{{ $movement->quantity }}
                    @else
                        -{{ $movement->quantity }}
                    @endif
                </td>
                <td style="padding: 14px 16px; font-size: 14px; color: #666;">{{ $movement->reference_number ?? '-' }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $movement->user->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px; color: #666; max-width: 200px;">
                    {{ Str::limit($movement->notes ?? '-', 50) }}
                </td>
                <td style="padding: 14px 16px; text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <form action="{{ route('inventory-supervisor.approvals.movements.approve', $movement) }}" method="POST">
                            @csrf
                            <button type="submit" style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                ✓ Approve
                            </button>
                        </form>
                        <form action="{{ route('inventory-supervisor.approvals.movements.reject', $movement) }}" method="POST">
                            @csrf
                            <button type="submit" style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                ✗ Reject
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div style="margin-top: 24px;">
        {{ $movements->links() }}
    </div>
</div>
@else
<div style="background: white; border-radius: 16px; padding: 60px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <svg style="width: 64px; height: 64px; color: #cbd5e0; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <h3 style="font-size: 20px; color: #4a5568; margin-bottom: 8px;">No Pending Movements</h3>
    <p style="color: #718096;">All stock movements have been reviewed!</p>
</div>
@endif

<style>
tr:hover {
    background: #f8f9fa !important;
}

button:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
