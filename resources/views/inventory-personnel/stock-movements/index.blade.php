@extends('layouts.app')

@section('page-title', 'Stock Movement History')
@section('page-description', 'View all stock movements')

@section('content')
@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">Stock Movement History</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Track all incoming and outgoing inventory changes
            </p>
        </div>
        <div style="display: flex; gap: 12px;" class="no-print">
            <a href="{{ route('inventory-personnel.stock-movements.incoming') }}" class="btn-atcl btn-atcl-primary">Add
                Incoming Stock</a>
            <button onclick="window.print()" class="btn-atcl btn-atcl-secondary">Print Report</button>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .card-atcl {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }

            body {
                background: white !important;
            }
        }
    </style>

    @if(session('success'))
        <div
            style="background: #d1fae5; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px; border-left: 4px solid #059669; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-atcl no-print" style="padding: 24px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('inventory-personnel.stock-movements.index') }}">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Search Reference</label>
                    <input type="text" name="search" class="input-atcl" value="{{ request('search') }}"
                        placeholder="Reference, notes...">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Movement Type</label>
                    <select name="type" class="input-atcl">
                        <option value="">All Types</option>
                        <option value="incoming" {{ request('type') == 'incoming' ? 'selected' : '' }}>Incoming</option>
                        <option value="issued" {{ request('type') == 'issued' ? 'selected' : '' }}>Issued</option>
                        <option value="returned" {{ request('type') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label class="label-atcl">Product</label>
                    <select name="product_id" class="input-atcl">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-atcl btn-atcl-primary" style="flex: 1;">Apply Filter</button>
                    <a href="{{ route('inventory-personnel.stock-movements.index') }}" class="btn-atcl btn-atcl-secondary"
                        style="display: flex; align-items: center; justify-content: center; width: 80px;">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-atcl" style="overflow-x: auto;">
        @if($movements->count() > 0)
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #f3f4f6;">
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">Date
                        </th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">Type
                        </th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">Product
                        </th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">Quantity
                        </th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">
                            Reference</th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">
                            Performed By</th>
                        <th style="padding: 16px; text-align: left; font-size: 13px; font-weight: 700; color: #1e3a8a;">Notes
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 16px; font-size: 14px; color: #6b7280; white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($movement->movement_date)->format('M d, Y') }}
                            </td>
                            <td style="padding: 16px;">
                                @if($movement->type == 'incoming')
                                    <span
                                        style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #d1fae5; color: #065f46;">Incoming</span>
                                @elseif($movement->type == 'issued')
                                    <span
                                        style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #fef3c7; color: #92400e;">Issued</span>
                                @else
                                    <span
                                        style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #dbeafe; color: #1e40af;">Returned</span>
                                @endif
                            </td>
                            <td style="padding: 16px; vertical-align: middle;">
                                <div style="font-weight: 700; color: #111827; font-size: 14px;">{{ $movement->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $movement->product->sku }}</div>
                            </td>
                            <td style="padding: 16px; font-weight: 700; font-size: 15px;">
                                @if($movement->type == 'issued' || $movement->type == 'transfer_to_catering' || $movement->type == 'outgoing')
                                    <span style="color: #dc2626;">-{{ $movement->quantity }}</span>
                                @else
                                    <span style="color: #059669;">+{{ $movement->quantity }}</span>
                                @endif
                                <span
                                    style="font-size: 12px; color: #6b7280; font-weight: normal; margin-left: 4px;">{{ $movement->product->unit_of_measure }}</span>
                            </td>
                            <td style="padding: 16px; font-size: 14px; color: #374151;">{{ $movement->reference_number ?? '-' }}
                            </td>
                            <td style="padding: 16px; font-size: 14px; color: #374151;">{{ $movement->user->name }}</td>
                            <td style="padding: 16px; font-size: 13px; color: #6b7280; max-width: 250px;">
                                {{ $movement->notes ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($movements->hasPages())
                <div style="padding: 24px; border-top: 1px solid #f3f4f6;" class="no-print">
                    {{ $movements->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg width="64" height="64" fill="#cbd5e1" viewBox="0 0 24 24" style="margin: 0 auto 20px;">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z" />
                </svg>
                <h3 style="margin-bottom: 8px; color: #475569;">No stock movements found</h3>
                <p>Start recording incoming stock, issues, or returns.</p>
            </div>
        @endif
    </div>
@endsection