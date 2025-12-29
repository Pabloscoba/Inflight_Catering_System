@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Request #{{ $requestModel->id }} Details</h1>
                <p class="text-gray-600 mt-2">View complete request information</p>
            </div>
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                ← Back
            </a>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">Request Information</h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Request ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $requestModel->id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <p>
                        @php
                            $statusColors = [
                                'pending_catering_incharge' => 'bg-yellow-100 text-yellow-800',
                                'catering_approved' => 'bg-blue-100 text-blue-800',
                                'supervisor_approved' => 'bg-indigo-100 text-indigo-800',
                                'items_issued' => 'bg-purple-100 text-purple-800',
                                'pending_final_approval' => 'bg-orange-100 text-orange-800',
                                'catering_final_approved' => 'bg-green-100 text-green-800',
                                'security_authenticated' => 'bg-teal-100 text-teal-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$requestModel->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $color }}">
                            {{ ucwords(str_replace('_', ' ', $requestModel->status)) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                    <p class="text-gray-900">{{ $requestModel->requester->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Request Date</label>
                    <p class="text-gray-900">{{ $requestModel->created_at->format('d M Y, h:i A') }}</p>
                </div>
                @if($requestModel->approver)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                    <p class="text-gray-900">{{ $requestModel->approver->name }}</p>
                </div>
                @endif
                @if($requestModel->catering_approved_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved Date</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($requestModel->catering_approved_at)->format('d M Y, h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Flight Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">Flight Information</h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Flight Number</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $requestModel->flight->flight_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                    <p class="text-gray-900">{{ $requestModel->flight->origin }} → {{ $requestModel->flight->destination }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($requestModel->flight->departure_time)->format('d M Y, h:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aircraft Type</label>
                    <p class="text-gray-900">{{ $requestModel->flight->aircraft_type }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Items -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">Requested Items</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($requestModel->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->product->category->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($requestModel->rejection_reason)
    <!-- Rejection Reason -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Rejection Reason</h3>
        <p class="text-red-700">{{ $requestModel->rejection_reason }}</p>
    </div>
    @endif
</div>
@endsection
