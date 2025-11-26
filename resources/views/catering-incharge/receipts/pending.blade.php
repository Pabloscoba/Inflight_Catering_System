@extends('layouts.app')

@section('title', 'Pending Product Receipts')

@section('content')
<div style="padding: 32px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Pending Product Receipts</h1>
                <p style="color: #718096; font-size: 16px; margin: 0;">Approve products received from Inventory Personnel</p>
            </div>
            <a href="{{ route('catering-incharge.dashboard') }}" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
    @endif

    <!-- Receipts Table -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        @if($receipts->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Quantity</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reference</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Received By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Date</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Notes</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipts as $receipt)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $receipt->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->product->category->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 700;">{{ $receipt->quantity_received }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->reference_number ?? 'N/A' }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->receivedBy->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->received_date->format('M d, Y H:i') }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $receipt->notes ?? '-' }}</td>
                        <td style="padding: 14px;">
                            <div style="display: flex; gap: 8px;">
                                <form action="{{ route('catering-incharge.receipts.approve', $receipt) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approve this receipt? Products will be available to Catering Staff.')" style="background: #28a745; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                                        ✓ Approve
                                    </button>
                                </form>
                                <button onclick="document.getElementById('reject-{{ $receipt->id }}').style.display='flex'" style="background: #dc3545; color: white; border: none; padding: 6px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">
                                    ✗ Reject
                                </button>
                            </div>
                            <!-- Reject Modal -->
                            <div id="reject-{{ $receipt->id }}" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;" onclick="event.stopPropagation()">
                                    <h4 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">Reject Receipt</h4>
                                    <p style="color: #6c757d; margin-bottom: 16px;">Product: <strong>{{ $receipt->product->name }}</strong></p>
                                    <form action="{{ route('catering-incharge.receipts.reject', $receipt) }}" method="POST">
                                        @csrf
                                        <textarea name="rejection_reason" required placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; min-height: 100px; margin-bottom: 16px; font-family: inherit;"></textarea>
                                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('reject-{{ $receipt->id }}').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Cancel</button>
                                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Reject Receipt</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 24px;">
            {{ $receipts->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <svg style="width: 64px; height: 64px; color: #cbd5e0; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 style="font-size: 20px; font-weight: 600; color: #4a5568; margin: 0 0 8px 0;">No Pending Receipts</h3>
            <p style="color: #718096; margin: 0;">All product receipts have been processed.</p>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('click', function(event) {
    if (event.target.style.background === 'rgba(0, 0, 0, 0.5)') {
        event.target.style.display = 'none';
    }
});
</script>

@endsection
