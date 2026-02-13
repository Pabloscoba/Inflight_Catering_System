@php $isPdf = request()->has('pdf'); @endphp
@extends($isPdf ? 'layouts.pdf' : 'layouts.app')

@section('title', 'Cabin Crew Service Report')

@section('content')
<div class="content-header" style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:700;">🛫 Cabin Crew Service Report</h1>
    <p style="color:#718096;">Comprehensive summary of today’s service activities.</p>
</div>

<!-- Summary Section -->
<div class="section" style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-bottom:24px;">
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Date</div>
            <div style="font-weight:600;color:#2d3748;">{{ $date }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Total Flights</div>
            <div style="font-weight:600;color:#2d3748;">{{ $totalFlights }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Requests Served</div>
            <div style="font-weight:600;color:#2d3748;">{{ $deliveredRequests }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Items Delivered</div>
            <div style="font-weight:600;color:#2d3748;">{{ $todayItemsServed }}</div>
        </div>
        <div>
            <div style="font-size:13px;color:#718096;margin-bottom:4px;">Service Rate</div>
            <div style="font-weight:600;color:#2d3748;">{{ $serviceRate }}%</div>
        </div>
    </div>
</div>

<!-- Detailed Flights Table -->
<div class="section" style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:24px;">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 20px 0;">Detailed Flights & Requests</h2>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Flight</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Route</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Requests</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Items Delivered</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Crew</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flights as $flight)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:16px;font-weight:600;color:#2d3748;">{{ $flight->flight_number }}</td>
                    <td style="padding:16px;color:#718096;">{{ $flight->origin }} → {{ $flight->destination }}</td>
                    <td style="padding:16px;text-align:center;">{{ $flight->requests_count }}</td>
                    <td style="padding:16px;text-align:center;">{{ $flight->items_delivered }}</td>
                    <td style="padding:16px;text-align:center;">{{ $flight->crew_names }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
