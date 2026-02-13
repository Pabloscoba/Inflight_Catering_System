@extends('layouts.app')

@section('title', 'Edit Staff Request')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Request #{{ $requestModel->id }}</h1>
        <p class="text-gray-600">Adjust items and quantities before approval.</p>
    </div>
    <form action="{{ route('catering-incharge.requests.update', $requestModel) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Requested Items</h2>
            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requestModel->items as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                        <td class="px-4 py-2">{{ $item->product->category->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <input type="number" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="1" class="border rounded px-2 py-1 w-24" required />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Save Changes</button>
            <a href="{{ route('catering-incharge.requests.pending') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
