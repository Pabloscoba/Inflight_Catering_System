@extends('layouts.app')

@section('title', 'Request Details')

@section('content')
<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
<div style="padding: 32px; max-width: 1200px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1a202c; margin: 0 0 8px 0;">Request #{{ $request->id }}</h1>
                <p style="color: #718096; font-size: 16px; margin: 0;">{{ $request->flight->flight_number }} - {{ $request->flight->origin }} → {{ $request->flight->destination }}</p>
            </div>
            <a href="{{ route('inventory-supervisor.requests.pending') }}" style="background: #6c757d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">← Back</a>
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

    <!-- Request Info -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1a202c; margin: 0 0 20px 0;">Request Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">REQUESTED BY</div>
                <div style="font-size: 16px; color: #212529; font-weight: 600;">{{ $request->requester->name }}</div>
                <div style="font-size: 13px; color: #6c757d;">{{ $request->requester->email }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">REQUESTED DATE</div>
                <div style="font-size: 16px; color: #212529; font-weight: 600;">{{ $request->requested_date->format('M d, Y H:i') }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">STATUS</div>
                <div>
                    @if($request->status == 'catering_approved')
                        <span style="background: #10b981; color: white; padding: 8px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">✓ Catering Approved</span>
                    @elseif($request->status == 'supervisor_approved')
                        <span style="background: #3b82f6; color: white; padding: 8px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">✓ Supervisor Approved</span>
                    @else
                        <span style="background: #f59e0b; color: white; padding: 8px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                    @endif
                </div>
            </div>

            @if($request->catering_approved_at)
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">CATERING APPROVAL</div>
                <div style="font-size: 14px; color: #212529;">{{ $request->catering_approved_at->format('M d, Y H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Flight Info -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1a202c; margin: 0 0 20px 0;">Flight Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">FLIGHT NUMBER</div>
                <div style="font-size: 16px; color: #212529; font-weight: 600;">{{ $request->flight->flight_number }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">ROUTE</div>
                <div style="font-size: 16px; color: #212529; font-weight: 600;">{{ $request->flight->origin }} → {{ $request->flight->destination }}</div>
            </div>
            
            <div>
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; font-weight: 600;">DEPARTURE</div>
                <div style="font-size: 16px; color: #212529; font-weight: 600;">{{ $request->flight->departure_time->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Requested Items -->
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1a202c; margin: 0 0 20px 0;">Requested Items ({{ $request->items->count() }})</h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Product</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Category</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Quantity Requested</th>
                        <th style="text-align: center; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Available Stock</th>
                        <th style="text-align: left; padding: 14px; font-size: 14px; font-weight: 600; color: #495057;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($request->items as $item)
                    <tr style="border-bottom: 1px solid #f1f3f5;">
                        <td style="padding: 14px; font-size: 14px; color: #212529; font-weight: 600;">{{ $item->product->name }}</td>
                        <td style="padding: 14px; font-size: 14px; color: #6c757d;">{{ $item->product->category->name }}</td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: #4facfe; color: white; padding: 6px 14px; border-radius: 12px; font-size: 15px; font-weight: 700;">
                                {{ $item->quantity_requested }} {{ $item->product->unit_of_measure ?? 'units' }}
                            </span>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <span style="background: {{ $item->product->quantity_in_stock >= $item->quantity_requested ? '#d4edda' : '#fff3cd' }}; color: {{ $item->product->quantity_in_stock >= $item->quantity_requested ? '#155724' : '#856404' }}; padding: 6px 14px; border-radius: 12px; font-size: 15px; font-weight: 700;">
                                {{ $item->product->quantity_in_stock }} {{ $item->product->unit_of_measure ?? 'units' }}
                            </span>
                        </td>
                        <td style="padding: 14px;">
                            @if($item->product->quantity_in_stock >= $item->quantity_requested)
                                <span style="background: #d4edda; color: #155724; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">✓ Available</span>
                            @else
                                <span style="background: #fff3cd; color: #856404; padding: 6px 14px; border-radius: 12px; font-size: 13px; font-weight: 600;">⚠️ Insufficient Stock</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approval Actions -->
    @if($request->status == 'catering_approved')
    <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <h3 style="font-size: 18px; font-weight: 600; color: #1a202c; margin: 0 0 20px 0;">Approval Actions</h3>
        
        <div style="display: flex; gap: 16px;">
            <button onclick="document.getElementById('approve-modal').style.display='flex'" style="flex: 1; background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 16px 24px; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(40, 167, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 16px rgba(40, 167, 69, 0.3)'">
                ✓ Approve Request
            </button>
            
            <button onclick="document.getElementById('reject-modal').style.display='flex'" style="flex: 1; background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; padding: 16px 24px; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 16px rgba(220, 53, 69, 0.3)'">
                ✗ Reject Request
            </button>
        </div>

        <!-- Approve Confirmation Modal -->
        <div id="approve-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; animation: fadeIn 0.2s;" onclick="this.style.display='none'">
            <div style="background: white; padding: 32px; border-radius: 16px; max-width: 540px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
                <div style="display: flex; align-items: center; margin-bottom: 24px;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-right: 18px;">
                        <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 24px; font-weight: 700; color: #1a202c;">Approve Request</h4>
                        <p style="margin: 4px 0 0 0; font-size: 14px; color: #718096;">Request #{{ $request->id }}</p>
                    </div>
                </div>
                <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0 0 28px 0;">Are you sure you want to approve this request? This will forward it to Inventory Personnel for issuing items.</p>
                <form action="{{ route('inventory-supervisor.requests.approve', $request) }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 14px;">
                        <button type="button" onclick="document.getElementById('approve-modal').style.display='none'" style="flex: 1; background: #e2e8f0; color: #475569; border: none; padding: 14px 28px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">Cancel</button>
                        <button type="submit" style="flex: 1; background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 14px 28px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(40, 167, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(40, 167, 69, 0.3)'">✓ Approve Request</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="reject-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; animation: fadeIn 0.2s;" onclick="this.style.display='none'">
            <div style="background: white; padding: 32px; border-radius: 16px; max-width: 540px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
                <div style="display: flex; align-items: center; margin-bottom: 24px;">
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-right: 18px;">
                        <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 24px; font-weight: 700; color: #1a202c;">Reject Request</h4>
                        <p style="margin: 4px 0 0 0; font-size: 14px; color: #718096;">Request #{{ $request->id }}</p>
                    </div>
                </div>
                <form action="{{ route('inventory-supervisor.requests.reject', $request) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 15px; font-weight: 600; color: #4a5568; margin-bottom: 10px;">Rejection Reason *</label>
                        <textarea name="rejection_reason" required placeholder="Provide detailed reason for rejection..." style="width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; min-height: 130px; font-family: inherit; transition: border-color 0.2s;" onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                    <div style="display: flex; gap: 14px;">
                        <button type="button" onclick="document.getElementById('reject-modal').style.display='none'" style="flex: 1; background: #e2e8f0; color: #475569; border: none; padding: 14px 28px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">Cancel</button>
                        <button type="submit" style="flex: 1; background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; padding: 14px 28px; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(220, 53, 69, 0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.3)'">✗ Reject Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
