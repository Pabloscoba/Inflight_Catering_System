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
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Total Products in Stock</div>
            <div style="font-size: 36px; font-weight: 700; color: #1a202c;">{{ $stockSummary->count() }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">Total Available Units</div>
            <div style="font-size: 36px; font-weight: 700; color: #28a745;">{{ $stockSummary->sum('catering_stock') }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">⚠️ Low Stock Items</div>
            <div style="font-size: 36px; font-weight: 700; color: #ffc107;">{{ $lowStockCount }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 14px; color: #718096; margin-bottom: 8px;">🚨 Out of Stock Items</div>
            <div style="font-size: 36px; font-weight: 700; color: #dc3545;">{{ $outOfStockCount }}</div>
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
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Current Stock</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reorder Level</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockSummary as $product)
                    @php
                        $isLowStock = $product->catering_stock <= $product->catering_reorder_level;
                        $isOutOfStock = $product->catering_stock == 0;
                        $stockPercentage = $product->catering_reorder_level > 0 ? ($product->catering_stock / $product->catering_reorder_level) * 100 : 100;
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $product->category->name }}</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="font-size: 18px; font-weight: 700; color: {{ $isOutOfStock ? '#dc3545' : ($isLowStock ? '#ffc107' : '#28a745') }};">{{ $product->catering_stock }}</span>
                            <span style="font-size: 12px; color: #6c757d; margin-left: 4px;">{{ $product->unit_of_measure ?? 'units' }}</span>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d; text-align: center;">{{ $product->catering_reorder_level }} {{ $product->unit_of_measure ?? 'units' }}</td>
                        <td style="padding: 14px;">
                            @if($isOutOfStock)
                            <span style="background: #f8d7da; color: #721c24; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">🚨 Out of Stock</span>
                            @elseif($isLowStock)
                            <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">⚠️ Low Stock</span>
                            @elseif($stockPercentage < 200)
                            <span style="background: #d1ecf1; color: #0c5460; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">Moderate</span>
                            @else
                            <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">✅ Good Stock</span>
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
        <h3 style="font-size: 20px; font-weight: 600; color: #1a202c; margin: 0 0 24px 0;">Real-Time Stock Levels</h3>
        @if($stocks->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">SKU</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Current Stock</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Reorder Level</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $product)
                    @php
                        $isLowStock = $product->catering_stock <= $product->catering_reorder_level;
                        $isOutOfStock = $product->catering_stock == 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $product->category->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $product->sku ?? 'N/A' }}</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="font-size: 18px; font-weight: 700; color: {{ $isOutOfStock ? '#dc3545' : ($isLowStock ? '#ffc107' : '#28a745') }};">{{ $product->catering_stock }}</span>
                            <span style="font-size: 12px; color: #6c757d; margin-left: 4px;">{{ $product->unit_of_measure ?? 'units' }}</span>
                        </td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d; text-align: center;">{{ $product->catering_reorder_level }} {{ $product->unit_of_measure ?? 'units' }}</td>
                        <td style="padding: 14px;">
                            @if($isOutOfStock)
                            <span style="background: #f8d7da; color: #721c24; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">🚨 Out of Stock</span>
                            @elseif($isLowStock)
                            <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">⚠️ Low Stock</span>
                            @else
                            <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">✅ Good Stock</span>
                            @endif
                        </td>
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
            <p style="color: #718096; margin: 0;">No products in stock currently.</p>
        </div>
        @endif
    </div>
</div>

@endsection
