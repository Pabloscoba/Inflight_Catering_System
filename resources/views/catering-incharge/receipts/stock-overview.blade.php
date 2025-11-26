@extends('layouts.app')

@section('title', 'Catering Stock Overview')

@section('content')
<div style="padding: 32px; max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Catering Stock Overview</h1>
                <p style="color: #718096; font-size: 16px; margin: 0;">Monitor all approved products available to Catering Staff</p>
            </div>
            <a href="{{ route('catering-incharge.dashboard') }}" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stock Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Total Products</div>
            <div style="font-size: 36px; font-weight: 700; color: #1a202c;">{{ $stockSummary->count() }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Total Available Units</div>
            <div style="font-size: 36px; font-weight: 700; color: #28a745;">{{ $stockSummary->sum('total_available') }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Total Received</div>
            <div style="font-size: 36px; font-weight: 700; color: #667eea;">{{ $stockSummary->sum('total_received') }}</div>
        </div>
    </div>

    <!-- Stock Summary by Product -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 24px 0;">Stock Summary by Product</h3>
        @if($stockSummary->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: right; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Total Received</th>
                        <th style="text-align: right; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Available</th>
                        <th style="text-align: right; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Used (%)</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockSummary as $summary)
                    @php
                        $usedPercentage = $summary->total_received > 0 ? (($summary->total_received - $summary->total_available) / $summary->total_received) * 100 : 0;
                        $availablePercentage = $summary->total_received > 0 ? ($summary->total_available / $summary->total_received) * 100 : 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $summary->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $summary->product->category->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d; text-align: right;">{{ $summary->total_received }}</td>
                        <td style="padding: 14px; font-size: 16px; color: #212529; font-weight: 700; text-align: right;">{{ $summary->total_available }}</td>
                        <td style="padding: 14px; text-align: right;">
                            <span style="font-size: 14px; color: #6c757d;">{{ number_format($usedPercentage, 1) }}%</span>
                        </td>
                        <td style="padding: 14px;">
                            @if($availablePercentage > 50)
                            <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">Good Stock</span>
                            @elseif($availablePercentage > 20)
                            <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">Moderate</span>
                            @else
                            <span style="background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">Low Stock</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <svg style="width: 64px; height: 64px; color: #cbd5e0; margin-bottom: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 style="font-size: 20px; font-weight: 600; color: #4a5568; margin: 0 0 8px 0;">No Stock Available</h3>
            <p style="color: #718096; margin: 0;">No approved products in catering stock yet.</p>
        </div>
        @endif
    </div>

    <!-- Detailed Stock Records -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 24px 0;">Detailed Stock Records</h3>
        @if($stocks->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reference</th>
                        <th style="text-align: right; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Received</th>
                        <th style="text-align: right; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Available</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Approved By</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Approved Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $stock->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $stock->reference_number ?? 'N/A' }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d; text-align: right;">{{ $stock->quantity_received }}</td>
                        <td style="padding: 14px; font-size: 16px; color: #212529; font-weight: 700; text-align: right;">
                            @if($stock->quantity_available == 0)
                            <span style="color: #dc3545;">0</span>
                            @else
                            {{ $stock->quantity_available }}
                            @endif
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $stock->cateringIncharge->name ?? 'N/A' }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $stock->approved_date ? $stock->approved_date->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 24px;">
            {{ $stocks->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <p style="color: #718096; margin: 0;">No detailed stock records available.</p>
        </div>
        @endif
    </div>
</div>

@endsection
