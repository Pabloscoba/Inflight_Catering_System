@extends('layouts.app')

@section('title', 'Usage Report - Request #' . $request->id)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>📊 Product Usage Report</h1>
            <p>Request #{{ $request->id }} | Flight: {{ $request->flight->flight_number }} | {{ $request->flight->origin }} → {{ $request->flight->destination }}</p>
        </div>
        <div style="display:flex;gap:12px;">
            <button onclick="window.print()" 
                    style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;">
                🖨️ Print Report
            </button>
            <a href="{{ route('cabin-crew.products.view', $request) }}" 
               style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;display:inline-block;">
                ← Back
            </a>
        </div>
    </div>
</div>

<!-- Report Header -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-bottom:24px;">
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Flight Number</div>
            <div style="font-weight:600;color:#2d3748;">{{ $request->flight->flight_number }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Airline</div>
            <div style="font-weight:600;color:#2d3748;">{{ $request->flight->airline }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Route</div>
            <div style="font-weight:600;color:#2d3748;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Departure</div>
            <div style="font-weight:600;color:#2d3748;">{{ \Carbon\Carbon::parse($request->flight->departure_time)->format('M d, Y H:i') }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Requested By</div>
            <div style="font-weight:600;color:#2d3748;">{{ $request->requester->name }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Report Generated</div>
            <div style="font-weight:600;color:#2d3748;">{{ now()->format('M d, Y H:i') }}</div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:28px;">
    <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Total Approved</div>
        <div style="font-size:32px;font-weight:700;color:#2d3748;">{{ $totalApproved }}</div>
        <div style="font-size:12px;color:#a0aec0;margin-top:4px;">Items approved for flight</div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Total Used</div>
        <div style="font-size:32px;font-weight:700;color:#38a169;">{{ $totalUsed }}</div>
        <div style="font-size:12px;color:#a0aec0;margin-top:4px;">
            {{ $totalApproved > 0 ? round(($totalUsed / $totalApproved) * 100, 1) : 0 }}% of approved
        </div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Defective</div>
        <div style="font-size:32px;font-weight:700;color:#e53e3e;">{{ $totalDefect }}</div>
        <div style="font-size:12px;color:#a0aec0;margin-top:4px;">
            {{ $totalApproved > 0 ? round(($totalDefect / $totalApproved) * 100, 1) : 0 }}% of approved
        </div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Remaining</div>
        <div style="font-size:32px;font-weight:700;color:#667eea;">{{ $totalRemaining }}</div>
        <div style="font-size:12px;color:#a0aec0;margin-top:4px;">
            {{ $totalApproved > 0 ? round(($totalRemaining / $totalApproved) * 100, 1) : 0 }}% of approved
        </div>
    </div>
</div>

<!-- Detailed Product Breakdown -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">Detailed Product Breakdown</h2>
    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product Name</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Category</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Used</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Defect</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Remaining</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Usage %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items as $item)
                @php
                    $approved = $item->quantity_approved ?? $item->quantity_requested;
                    $used = $item->quantity_used ?? 0;
                    $defect = $item->quantity_defect ?? 0;
                    $remaining = $item->quantity_remaining ?? ($approved - $used - $defect);
                    $usagePercent = $approved > 0 ? round(($used / $approved) * 100, 1) : 0;
                @endphp
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $item->product->name }}</div>
                    </td>
                    <td style="padding:16px;text-align:center;color:#718096;font-size:13px;">
                        {{ $item->product->category->name ?? 'N/A' }}
                    </td>
                    <td style="padding:16px;text-align:center;font-weight:600;color:#2d3748;">{{ $approved }}</td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:#d4edda;color:#155724;padding:4px 12px;border-radius:12px;font-weight:600;font-size:13px;">
                            {{ $used }}
                        </span>
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:#f8d7da;color:#721c24;padding:4px 12px;border-radius:12px;font-weight:600;font-size:13px;">
                            {{ $defect }}
                        </span>
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:#e3f2fd;color:#1565c0;padding:4px 12px;border-radius:12px;font-weight:600;font-size:13px;">
                            {{ $remaining }}
                        </span>
                    </td>
                    <td style="padding:16px;text-align:center;font-weight:600;color:#2d3748;">
                        {{ $usagePercent }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f7fafc;font-weight:700;">
                    <td colspan="2" style="padding:16px;text-align:right;border-top:2px solid #e2e8f0;">TOTALS:</td>
                    <td style="padding:16px;text-align:center;border-top:2px solid #e2e8f0;">{{ $totalApproved }}</td>
                    <td style="padding:16px;text-align:center;border-top:2px solid #e2e8f0;">{{ $totalUsed }}</td>
                    <td style="padding:16px;text-align:center;border-top:2px solid #e2e8f0;">{{ $totalDefect }}</td>
                    <td style="padding:16px;text-align:center;border-top:2px solid #e2e8f0;">{{ $totalRemaining }}</td>
                    <td style="padding:16px;text-align:center;border-top:2px solid #e2e8f0;">
                        {{ $totalApproved > 0 ? round(($totalUsed / $totalApproved) * 100, 1) : 0 }}%
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Products with Defects -->
@php
    $defectiveItems = $request->items->filter(function($item) {
        return ($item->quantity_defect ?? 0) > 0;
    });
@endphp

@if($defectiveItems->count() > 0)
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">⚠️ Defective Products</h2>
    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#fff5f5;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#742a2a;border-bottom:2px solid #feb2b2;">Product</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#742a2a;border-bottom:2px solid #feb2b2;">Defect Qty</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#742a2a;border-bottom:2px solid #feb2b2;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($defectiveItems as $item)
                <tr style="border-bottom:1px solid #feb2b2;">
                    <td style="padding:16px;font-weight:600;color:#2d3748;">{{ $item->product->name }}</td>
                    <td style="padding:16px;text-align:center;">
                        <span style="background:#f8d7da;color:#721c24;padding:4px 12px;border-radius:12px;font-weight:600;">
                            {{ $item->quantity_defect }}
                        </span>
                    </td>
                    <td style="padding:16px;color:#4a5568;font-size:13px;">{{ $item->defect_notes ?? 'No notes' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Usage Notes -->
@php
    $itemsWithNotes = $request->items->filter(function($item) {
        return !empty($item->usage_notes);
    });
@endphp

@if($itemsWithNotes->count() > 0)
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">📝 Usage Notes</h2>
    
    @foreach($itemsWithNotes as $item)
    <div style="background:#f7fafc;border-radius:8px;padding:16px;margin-bottom:12px;">
        <div style="font-weight:600;color:#2d3748;margin-bottom:8px;">{{ $item->product->name }}</div>
        <div style="color:#4a5568;font-size:14px;">{{ $item->usage_notes }}</div>
    </div>
    @endforeach
</div>
@endif

<style>
@media print {
    .content-header button,
    .content-header a {
        display: none !important;
    }
}
</style>
@endsection
