@extends('layouts.app')

@section('page-title', 'Stock Movement History')
@section('page-description', 'View all stock movements')

@section('content')
<style>
    .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-block; transition: all 0.2s; }
    .btn-primary { background: #0b1a68; color: white; }
    .btn-primary:hover { background: #091352; }
    .btn-success { background: #059669; color: white; }
    .btn-success:hover { background: #047857; }
    .btn-warning { background: #d97706; color: white; }
    .btn-warning:hover { background: #b45309; }
    .btn-info { background: #0891b2; color: white; }
    .btn-info:hover { background: #0e7490; }
    .filters { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #475569; }
    .filter-group input, .filter-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
    .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th { padding: 14px; text-align: left; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; }
    tr:hover { background: #f8fafc; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-incoming { background: #d1fae5; color: #065f46; }
    .badge-issued { background: #fef3c7; color: #92400e; }
    .badge-returned { background: #dbeafe; color: #1e40af; }
    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #059669; }
    .pagination { display: flex; gap: 8px; justify-content: center; padding: 20px; }
    .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; }
    .pagination .active { background: #0b1a68; color: white; border-color: #0b1a68; }
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .action-buttons { display: flex; gap: 10px; margin-bottom: 20px; }
</style>

@if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('admin.stock-movements.incoming') }}" class="btn btn-success">+ Incoming Stock</a>
                <a href="{{ route('admin.stock-movements.issue') }}" class="btn btn-warning">↗ Issue Stock</a>
                <a href="{{ route('admin.stock-movements.returns') }}" class="btn btn-info">↩ Returns</a>
            </div>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" action="{{ route('admin.stock-movements.index') }}">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Search Reference</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, Flight number...">
                        </div>
                        <div class="filter-group">
                            <label>Movement Type</label>
                            <select name="type">
                                <option value="">All Types</option>
                                <option value="incoming" {{ request('type') == 'incoming' ? 'selected' : '' }}>Incoming</option>
                                <option value="issued" {{ request('type') == 'issued' ? 'selected' : '' }}>Issued</option>
                                <option value="returned" {{ request('type') == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Product</label>
                            <select name="product_id">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div class="filter-group" style="flex: 0;">
                            <a href="{{ route('admin.stock-movements.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="card">
                @if($movements->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Reference</th>
                                <th>Performed By</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>{{ $movement->movement_date->format('d M Y') }}</td>
                                    <td>
                                        @if($movement->type == 'incoming')
                                            <span class="badge badge-incoming">Incoming</span>
                                        @elseif($movement->type == 'issued')
                                            <span class="badge badge-issued">Issued</span>
                                        @else
                                            <span class="badge badge-returned">Returned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $movement->product->name }}</strong><br>
                                        <small style="color: #94a3b8;">SKU: {{ $movement->product->sku }}</small>
                                    </td>
                                    <td>
                                        @if($movement->type == 'issued')
                                            <span style="color: #dc2626; font-weight: 600;">-{{ $movement->quantity }}</span>
                                        @else
                                            <span style="color: #059669; font-weight: 600;">+{{ $movement->quantity }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->reference_number ?? '-' }}</td>
                                    <td>{{ $movement->user->name }}</td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $movement->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="pagination">
                        {{ $movements->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <svg width="64" height="64" fill="#cbd5e1" viewBox="0 0 24 24" style="margin: 0 auto 20px;">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                        </svg>
                        <h3 style="margin-bottom: 8px; color: #475569;">No stock movements found</h3>
                        <p>Start recording incoming stock, issues, or returns.</p>
                    </div>
                @endif
            </div>
@endsection
