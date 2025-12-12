@extends('layouts.app')

@section('title', 'Product Management - Request #' . $request->id)

@section('content')
<div class="content-header">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h1>Product Management - Request #{{ $request->id }}</h1>
            <p>Flight: {{ $request->flight->flight_number }} | {{ $request->flight->origin }} → {{ $request->flight->destination }}</p>
        </div>
        <div style="display:flex;gap:12px;">
            <a href="{{ route('cabin-crew.products.request-additional', $request) }}" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                ➕ Request Additional Products
            </a>
            <a href="{{ route('cabin-crew.products.report', $request) }}" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
                📊 Generate Report
            </a>
            <a href="{{ route('cabin-crew.dashboard') }}" style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;">
                ← Back
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:16px;border-radius:8px;margin-bottom:24px;">
    {{ session('success') }}</div>
@endif

@if(session('error'))
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:16px;border-radius:8px;margin-bottom:24px;">
    {{ session('error') }}</div>
@endif

<!-- Summary Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:32px;">
    @php
        $totalApproved = $request->items->sum(fn($i) => $i->quantity_approved ?? $i->quantity_requested);
        $totalUsed = $request->items->sum('quantity_used');
        $totalDefect = $request->items->sum('quantity_defect');
        $totalRemaining = $request->items->sum('quantity_remaining');
    @endphp
    
    <div style="background:white;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Total Approved</div>
        <div style="font-size:28px;font-weight:700;color:#2d3748;">{{ $totalApproved }}</div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Used</div>
        <div style="font-size:28px;font-weight:700;color:#38a169;">{{ $totalUsed }}</div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Defect</div>
        <div style="font-size:28px;font-weight:700;color:#e53e3e;">{{ $totalDefect }}</div>
    </div>
    
    <div style="background:white;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <div style="font-size:13px;color:#718096;margin-bottom:8px;">Remaining</div>
        <div style="font-size:28px;font-weight:700;color:#667eea;">{{ $totalRemaining ?? ($totalApproved - $totalUsed - $totalDefect) }}</div>
    </div>
</div>

<!-- Products List -->
<div style="background:white;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
    <h2 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 24px 0;">📦 Product List & Usage Tracking</h2>
    
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr style="background:#f7fafc;">
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Product</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Meal Type</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Approved</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Used</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Defect</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Remaining</th>
                    <th style="padding:14px 16px;text-align:left;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Notes</th>
                    <th style="padding:14px 16px;text-align:center;font-size:13px;font-weight:600;color:#4a5568;border-bottom:2px solid #e2e8f0;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items as $item)
                @php
                    $approved = $item->quantity_approved ?? $item->quantity_requested;
                    $used = $item->quantity_used ?? 0;
                    $defect = $item->quantity_defect ?? 0;
                    $remaining = $item->quantity_remaining ?? ($approved - $used - $defect);
                @endphp
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:16px;">
                        <div style="font-weight:600;color:#2d3748;">{{ $item->product->name }}</div>
                        <div style="font-size:12px;color:#718096;">{{ $item->product->category->name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding:16px;text-align:center;">
                        @if($item->meal_type)
                            @php
                                $mealBadges = [
                                    'breakfast' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '🍳', 'label' => 'Breakfast'],
                                    'lunch' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '🍽️', 'label' => 'Lunch'],
                                    'dinner' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '🌙', 'label' => 'Dinner'],
                                    'snack' => ['bg' => '#fce7f3', 'color' => '#9f1239', 'icon' => '🍪', 'label' => 'Snack'],
                                    'VIP_meal' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'icon' => '👑', 'label' => 'VIP'],
                                    'special_meal' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '⭐', 'label' => 'Special'],
                                    'non_meal' => ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'Non-Meal']
                                ];
                                $badge = $mealBadges[$item->meal_type] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'icon' => '📦', 'label' => 'N/A'];
                            @endphp
                            <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;display:inline-block;white-space:nowrap;">
                                {{ $badge['icon'] }} {{ $badge['label'] }}
                            </span>
                        @else
                            <span style="color:#9ca3af;font-size:12px;">—</span>
                        @endif
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
                    <td style="padding:16px;font-size:12px;color:#718096;">
                        @if($item->usage_notes)
                            <div style="margin-bottom:4px;"><strong>Usage:</strong> {{ Str::limit($item->usage_notes, 30) }}</div>
                        @endif
                        @if($item->defect_notes)
                            <div style="color:#e53e3e;"><strong>Defect:</strong> {{ Str::limit($item->defect_notes, 30) }}</div>
                        @endif
                    </td>
                    <td style="padding:16px;text-align:center;">
                        <div style="display:flex;gap:8px;justify-content:center;">
                            <!-- Mark as Used Button -->
                            <button onclick="openUsedModal({{ $item->id }}, '{{ $item->product->name }}', {{ $remaining }})" 
                                    style="background:#38a169;color:white;border:none;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                ✓ Used
                            </button>
                            
                            <!-- Record Defect Button -->
                            <button onclick="openDefectModal({{ $item->id }}, '{{ $item->product->name }}', {{ $remaining }})" 
                                    style="background:#e53e3e;color:white;border:none;padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                ⚠ Defect
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Mark as Used Modal -->
<div id="usedModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:500px;width:90%;">
        <h3 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 24px 0;">Mark Product as Used</h3>
        
        <form id="usedForm" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Product</label>
                <div id="usedProductName" style="font-size:16px;font-weight:700;color:#2d3748;"></div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Quantity Used *</label>
                <input type="number" name="quantity_used" min="1" required 
                       style="width:100%;padding:10px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;" 
                       placeholder="Enter quantity">
                <div id="usedMaxQty" style="font-size:12px;color:#718096;margin-top:4px;"></div>
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Usage Notes (Optional)</label>
                <textarea name="usage_notes" rows="3" 
                          style="width:100%;padding:10px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;" 
                          placeholder="Any notes about the usage..."></textarea>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="closeUsedModal()" 
                        style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background:#38a169;color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    ✓ Mark as Used
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Record Defect Modal -->
<div id="defectModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:500px;width:90%;">
        <h3 style="font-size:20px;font-weight:700;color:#1a202c;margin:0 0 24px 0;">Record Defect Product</h3>
        
        <form id="defectForm" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Product</label>
                <div id="defectProductName" style="font-size:16px;font-weight:700;color:#2d3748;"></div>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Defect Quantity *</label>
                <input type="number" name="quantity_defect" min="1" required 
                       style="width:100%;padding:10px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;" 
                       placeholder="Enter quantity">
                <div id="defectMaxQty" style="font-size:12px;color:#718096;margin-top:4px;"></div>
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:14px;font-weight:600;color:#4a5568;margin-bottom:8px;">Defect Description *</label>
                <textarea name="defect_notes" rows="3" required 
                          style="width:100%;padding:10px 14px;border:1px solid #cbd5e0;border-radius:8px;font-size:14px;" 
                          placeholder="Describe the defect (damaged, expired, etc.)"></textarea>
            </div>
            
            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="closeDefectModal()" 
                        style="background:#e2e8f0;color:#2d3748;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" 
                        style="background:#e53e3e;color:white;border:none;padding:10px 24px;border-radius:8px;font-weight:600;cursor:pointer;">
                    ⚠ Record Defect
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUsedModal(itemId, productName, remaining) {
    document.getElementById('usedModal').style.display = 'flex';
    document.getElementById('usedProductName').textContent = productName;
    document.getElementById('usedMaxQty').textContent = 'Available: ' + remaining;
    document.getElementById('usedForm').action = '{{ url("cabin-crew/items") }}/' + itemId + '/mark-used';
    document.querySelector('#usedForm input[name="quantity_used"]').max = remaining;
}

function closeUsedModal() {
    document.getElementById('usedModal').style.display = 'none';
    document.getElementById('usedForm').reset();
}

function openDefectModal(itemId, productName, remaining) {
    document.getElementById('defectModal').style.display = 'flex';
    document.getElementById('defectProductName').textContent = productName;
    document.getElementById('defectMaxQty').textContent = 'Available: ' + remaining;
    document.getElementById('defectForm').action = '{{ url("cabin-crew/items") }}/' + itemId + '/record-defect';
    document.querySelector('#defectForm input[name="quantity_defect"]').max = remaining;
}

function closeDefectModal() {
    document.getElementById('defectModal').style.display = 'none';
    document.getElementById('defectForm').reset();
}

// Close modals on outside click
document.getElementById('usedModal').addEventListener('click', function(e) {
    if (e.target === this) closeUsedModal();
});

document.getElementById('defectModal').addEventListener('click', function(e) {
    if (e.target === this) closeDefectModal();
});
</script>
@endsection
