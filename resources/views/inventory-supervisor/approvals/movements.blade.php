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
                    <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;
                        background: {{ $movement->type == 'incoming' ? '#d4edda' : ($movement->type == 'issued' ? '#fff3cd' : '#d1ecf1') }};
                        color: {{ $movement->type == 'incoming' ? '#155724' : ($movement->type == 'issued' ? '#856404' : '#0c5460') }};">
                        {{ ucfirst($movement->type) }}
                    </span>
                </td>
                <td style="padding: 14px 16px; font-size: 14px; text-align: center; font-weight: 600;">{{ $movement->quantity }}</td>
                <td style="padding: 14px 16px; font-size: 14px; color: #666;">{{ $movement->reference_number ?? '-' }}</td>
                <td style="padding: 14px 16px; font-size: 14px;">{{ $movement->user->name }}</td>
                <td style="padding: 14px 16px; font-size: 14px; color: #666; max-width: 200px;">
                    {{ Str::limit($movement->notes ?? '-', 50) }}
                </td>
                <td style="padding: 14px 16px; text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <form action="{{ route('inventory-supervisor.approvals.movements.approve', $movement) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Approve this stock movement? This will update inventory levels.')" style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                ✓ Approve
                            </button>
                        </form>
                        <form action="{{ route('inventory-supervisor.approvals.movements.reject', $movement) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Reject this stock movement? Inventory will NOT be updated.')" style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
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
