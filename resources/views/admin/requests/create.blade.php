@extends('layouts.app')

@section('page-title', 'Create New Request')

@section('content')
<div style="padding: 32px;">
    <!-- Page Header -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1a202c; margin-bottom: 8px;">Create New Request</h1>
            <p style="color: #718096;">Submit a new catering request for a flight</p>
        </div>
        <button onclick="showCreateForm()" style="background: #10b981; color: white; padding: 12px 24px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 15px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Request
        </button>
    </div>

    @if($errors->any())
    <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <ul style="margin-left: 20px; margin: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Scheduled Flights Section -->
    <div id="scheduledFlightsSection">
        <h2 style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 16px;">Scheduled Flights with Products</h2>
        <p style="color: #6b7280; margin-bottom: 24px; font-size: 14px;">Select a flight below to quickly create a request with pre-scheduled products</p>
        
        @if($flights->isEmpty())
        <div class="card" style="text-align: center; padding: 48px 24px;">
            <svg style="width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <h3 style="font-size: 16px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">No Scheduled Flights</h3>
            <p style="color: #9ca3af; font-size: 14px;">There are no flights scheduled. Please create flights first.</p>
        </div>
        @else
        <div style="display: grid; gap: 20px;">
            @foreach($flights as $flight)
            @php
                $scheduledProducts = \App\Models\RequestItem::where('is_scheduled', true)
                    ->whereHas('request', function($q) use ($flight) {
                        $q->where('flight_id', $flight->id);
                    })
                    ->with('product')
                    ->get()
                    ->groupBy('product_id');
            @endphp
            
            <div class="card" style="position: relative; overflow: hidden;">
                <!-- Flight Header -->
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #e5e7eb;">
                    <div style="display: flex; gap: 16px; align-items: start;">
                        <div style="background: #eff6ff; padding: 12px; border-radius: 10px;">
                            <svg style="width: 32px; height: 32px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size: 20px; font-weight: 600; color: #1f2937; margin-bottom: 6px;">{{ $flight->flight_number }}</h3>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <span style="color: #6b7280; font-size: 15px; font-weight: 500;">{{ $flight->origin }}</span>
                                <svg style="width: 16px; height: 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span style="color: #6b7280; font-size: 15px; font-weight: 500;">{{ $flight->destination }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; color: #9ca3af; font-size: 13px;">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $flight->departure_time->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="padding: 6px 16px; background: {{ $flight->getStatusBackground() }}; color: {{ $flight->getStatusColor() }}; border-radius: 16px; font-size: 13px; font-weight: 600; display: inline-block; margin-bottom: 12px;">
                            {{ ucfirst($flight->status) }}
                        </span>
                        <button onclick="selectFlightQuick({{ $flight->id }}, '{{ $flight->flight_number }}', '{{ $flight->origin }}', '{{ $flight->destination }}', '{{ $flight->departure_time->format('d M Y H:i') }}')" style="background: #0b1a68; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; margin-left: auto;">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Request
                        </button>
                    </div>
                </div>

                <!-- Scheduled Products -->
                @if($scheduledProducts->isEmpty())
                <div style="background: #fef3c7; border-left: 3px solid #f59e0b; padding: 12px 16px; border-radius: 6px;">
                    <p style="color: #92400e; font-size: 13px; margin: 0;">
                        <strong>No products scheduled yet.</strong> Click "Add Request" to schedule products for this flight.
                    </p>
                </div>
                @else
                <div>
                    <h4 style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px;">Scheduled Products ({{ $scheduledProducts->count() }}):</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                        @foreach($scheduledProducts as $productId => $items)
                        @php
                            $product = $items->first()->product;
                            $totalQty = $items->sum('quantity_requested');
                        @endphp
                        <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 8px; padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span style="font-weight: 600; color: #166534; font-size: 13px;">{{ $product->name }}</span>
                            </div>
                            <div style="font-size: 12px; color: #15803d;">
                                Qty: <strong>{{ $totalQty }}</strong> units
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Create Form Modal (Initially Hidden) -->
    <div id="createFormModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; overflow-y: auto; padding: 20px;">
        <div style="max-width: 1200px; margin: 20px auto; background: white; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 22px; font-weight: 600; color: #1f2937;">Create New Request</h2>
                    <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Select flight and add products</p>
                </div>
                <button onclick="hideCreateForm()" style="background: #f3f4f6; color: #374151; width: 36px; height: 36px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div style="padding: 24px; max-height: calc(100vh - 200px); overflow-y: auto;">

                <!-- Step 1: Select Flight -->
                <div class="card" style="margin-bottom: 24px;">
        <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
            Step 1: Select Flight
        </h2>
        
        @if($flights->isEmpty())
        <div style="text-align: center; padding: 48px 24px; background: #f9fafb; border-radius: 8px;">
            <svg style="width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <h3 style="font-size: 16px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">No Available Flights</h3>
            <p style="color: #9ca3af; font-size: 14px; margin-bottom: 16px;">There are no flights scheduled. Please create flights first.</p>
            <a href="{{ route('admin.flights.create') }}" style="background: #0b1a68; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-block; font-weight: 500;">
                Create New Flight
            </a>
        </div>
        @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 16px;">
            @foreach($flights as $flight)
            <div class="flight-card" data-flight-id="{{ $flight->id }}" onclick="selectFlight({{ $flight->id }}, '{{ $flight->flight_number }}', '{{ $flight->origin }}', '{{ $flight->destination }}', '{{ $flight->departure_time->format('d M Y H:i') }}')" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.2s; background: white;">
                <div style="display: flex; align-items: start; gap: 16px;">
                    <div style="background: #eff6ff; padding: 12px; border-radius: 10px; flex-shrink: 0;">
                        <svg style="width: 32px; height: 32px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">{{ $flight->flight_number }}</h3>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="color: #6b7280; font-size: 14px; font-weight: 500;">{{ $flight->origin }}</span>
                            <svg style="width: 16px; height: 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            <span style="color: #6b7280; font-size: 14px; font-weight: 500;">{{ $flight->destination }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; color: #9ca3af; font-size: 13px;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $flight->departure_time->format('d M Y H:i') }}
                        </div>
                        <div style="margin-top: 12px;">
                            <span style="padding: 4px 12px; background: {{ $flight->getStatusBackground() }}; color: {{ $flight->getStatusColor() }}; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                {{ ucfirst($flight->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Step 2: Form (hidden until flight selected) -->
    <form method="POST" action="{{ route('admin.requests.store') }}" id="requestForm" style="display: none;">
        @csrf
        <input type="hidden" name="flight_id" id="selectedFlightId">

        <!-- Selected Flight Info -->
        <div class="card" style="margin-bottom: 24px; background: #eff6ff; border: 2px solid #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h3 style="font-size: 14px; font-weight: 500; color: #1e40af; margin-bottom: 8px;">Selected Flight</h3>
                    <div id="selectedFlightInfo" style="font-size: 16px; font-weight: 600; color: #1f2937;"></div>
                </div>
                <button type="button" onclick="clearSelection()" style="background: #fee2e2; color: #dc2626; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; font-size: 13px;">
                    Change Flight
                </button>
            </div>
        </div>

        <!-- Add Products -->
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
                Step 2: Add Products
            </h2>
            
            <div style="display: grid; grid-template-columns: 1fr 150px auto; gap: 12px; align-items: end; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px;">Product</label>
                    <select id="productSelect" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-stock="{{ $product->quantity_in_stock }}">
                                {{ $product->name }} (Stock: {{ $product->quantity_in_stock }})
                            </option>
                        @endforeach
                    </select>
                    <div id="stockInfo" style="color: #6b7280; font-size: 13px; margin-top: 6px;"></div>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px;">Quantity</label>
                    <input type="number" id="quantityInput" min="1" value="1" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <button type="button" onclick="addToCart()" style="background: #10b981; color: white; padding: 12px 24px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 14px;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </button>
            </div>

            <!-- Cart Items -->
            <div style="margin-top: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 12px;">Selected Products</h3>
                <div id="cartItems">
                    <div style="text-align: center; padding: 48px 24px; background: #f9fafb; border-radius: 8px; color: #9ca3af;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p>No products added yet. Add products from above.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="card" style="margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
                Step 3: Additional Notes (Optional)
            </h2>
            <textarea name="notes" placeholder="Any special requirements or notes..." style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; resize: vertical; min-height: 100px; font-family: inherit;">{{ old('notes') }}</textarea>
        </div>

        <!-- Submit -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" id="submitBtn" disabled style="background: #0b1a68; color: white; padding: 12px 32px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 15px; opacity: 0.5;">
                Submit Request
            </button>
            <a href="{{ route('admin.requests.index') }}" style="background: #f3f4f6; color: #374151; padding: 12px 32px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 15px;">
                Cancel
            </a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<style>
    .flight-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }
    .flight-card.selected {
        border-color: #10b981;
        background: #f0fdf4 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    #submitBtn:not(:disabled) {
        opacity: 1 !important;
        cursor: pointer !important;
    }
    #submitBtn:not(:disabled):hover {
        background: #091352 !important;
    }
</style>

<script>
    let cart = [];
    let selectedFlight = null;

    function showCreateForm() {
        document.getElementById('createFormModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideCreateForm() {
        document.getElementById('createFormModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        clearSelection();
    }

    function selectFlightQuick(id, number, origin, destination, datetime) {
        showCreateForm();
        setTimeout(() => {
            selectFlight(id, number, origin, destination, datetime);
        }, 100);
    }

    function selectFlight(id, number, origin, destination, datetime) {
        // Remove previous selection
        document.querySelectorAll('.flight-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Mark as selected
        const targetCard = document.querySelector(`.flight-card[data-flight-id="${id}"]`);
        if (targetCard) {
            targetCard.classList.add('selected');
        }

        // Store selection
        selectedFlight = { id, number, origin, destination, datetime };
        document.getElementById('selectedFlightId').value = id;
        document.getElementById('selectedFlightInfo').innerHTML = `
            <strong>${number}</strong> - ${origin} → ${destination}<br>
            <span style="font-size: 13px; color: #6b7280; font-weight: normal;">${datetime}</span>
        `;

        // Show form
        document.getElementById('requestForm').style.display = 'block';

        // Scroll to form
        setTimeout(() => {
            document.getElementById('requestForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }

    function clearSelection() {
        selectedFlight = null;
        document.getElementById('selectedFlightId').value = '';
        document.getElementById('requestForm').style.display = 'none';
        document.querySelectorAll('.flight-card').forEach(card => {
            card.classList.remove('selected');
        });
        cart = [];
        renderCart();

        // Scroll back to flights
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function addToCart() {
        const select = document.getElementById('productSelect');
        const quantityInput = document.getElementById('quantityInput');
        const productId = select.value;
        const quantity = parseInt(quantityInput.value);

        if (!productId) {
            alert('Please select a product');
            return;
        }

        if (!quantity || quantity < 1) {
            alert('Please enter valid quantity');
            return;
        }

        const option = select.options[select.selectedIndex];
        const productName = option.dataset.name;
        const stock = parseInt(option.dataset.stock);

        if (quantity > stock) {
            alert('Quantity cannot exceed available stock (' + stock + ')');
            return;
        }

        const existing = cart.find(item => item.id === productId);
        if (existing) {
            const newQty = existing.quantity + quantity;
            if (newQty > stock) {
                alert('Total quantity would exceed available stock (' + stock + ')');
                return;
            }
            existing.quantity = newQty;
        } else {
            cart.push({ id: productId, name: productName, quantity: quantity, stock: stock, isScheduled: false });
        }

        renderCart();
        select.value = '';
        quantityInput.value = 1;
        document.getElementById('stockInfo').innerHTML = '';
    }

    function toggleSchedule(index) {
        cart[index].isScheduled = !cart[index].isScheduled;
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateQuantity(index, change) {
        const item = cart[index];
        const newQty = item.quantity + change;

        if (newQty < 1) {
            if (confirm('Remove this product from cart?')) {
                removeFromCart(index);
            }
            return;
        }

        if (newQty > item.stock) {
            alert('Cannot exceed available stock (' + item.stock + ')');
            return;
        }

        item.quantity = newQty;
        renderCart();
    }

    function renderCart() {
        const cartDiv = document.getElementById('cartItems');
        const submitBtn = document.getElementById('submitBtn');

        if (cart.length === 0) {
            cartDiv.innerHTML = `
                <div style="text-align: center; padding: 48px 24px; background: #f9fafb; border-radius: 8px; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p>No products added yet. Add products from above.</p>
                </div>
            `;
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            return;
        }

        let html = '';
        let totalItems = 0;
        let scheduledItems = 0;
        cart.forEach((item, index) => {
            totalItems += item.quantity;
            if (item.isScheduled) scheduledItems++;
            
            const scheduleBadge = item.isScheduled ? 
                `<span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    <svg style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Scheduled
                </span>` : 
                `<span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                    Not Scheduled
                </span>`;

            html += `
                <div style="padding: 16px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1f2937; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                                ${item.name}
                                ${scheduleBadge}
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">Available Stock: ${item.stock} units</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px; background: #f3f4f6; border-radius: 6px; padding: 4px;">
                                <button type="button" onclick="updateQuantity(${index}, -1)" style="background: white; border: 1px solid #d1d5db; color: #374151; width: 32px; height: 32px; border-radius: 4px; cursor: pointer; font-weight: 600;">−</button>
                                <span style="min-width: 40px; text-align: center; font-weight: 600; color: #1f2937;">${item.quantity}</span>
                                <button type="button" onclick="updateQuantity(${index}, 1)" style="background: white; border: 1px solid #d1d5db; color: #374151; width: 32px; height: 32px; border-radius: 4px; cursor: pointer; font-weight: 600;">+</button>
                            </div>
                            <button type="button" onclick="removeFromCart(${index})" style="background: #fee2e2; color: #dc2626; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; font-size: 13px;">
                                Remove
                            </button>
                        </div>
                    </div>
                    
                    <!-- Schedule Checkbox -->
                    <div style="background: ${item.isScheduled ? '#f0fdf4' : '#fef3c7'}; border: 1px solid ${item.isScheduled ? '#86efac' : '#fcd34d'}; border-radius: 6px; padding: 12px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" 
                                   ${item.isScheduled ? 'checked' : ''} 
                                   onchange="toggleSchedule(${index})" 
                                   style="width: 18px; height: 18px; cursor: pointer; accent-color: #10b981;">
                            <div style="flex: 1;">
                                <span style="font-weight: 600; color: ${item.isScheduled ? '#166534' : '#92400e'}; font-size: 13px;">Schedule this product with flight</span>
                                <p style="font-size: 11px; color: ${item.isScheduled ? '#15803d' : '#a16207'}; margin: 4px 0 0 0;">
                                    ${item.isScheduled ? '✓ This product will be prepared and loaded for the selected flight' : 'Product will be requested but not automatically scheduled'}
                                </p>
                            </div>
                            <svg style="width: 20px; height: 20px; color: ${item.isScheduled ? '#10b981' : '#f59e0b'};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </label>
                    </div>

                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][is_scheduled]" value="${item.isScheduled ? '1' : '0'}">
                </div>
            `;
        });

        html += `
            <div style="background: #eff6ff; padding: 16px; border-radius: 8px; margin-top: 16px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Total Products</div>
                        <div style="font-weight: 700; color: #1e40af; font-size: 20px;">${cart.length}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Total Units</div>
                        <div style="font-weight: 700; color: #1e40af; font-size: 20px;">${totalItems}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Scheduled Items</div>
                        <div style="font-weight: 700; color: ${scheduledItems > 0 ? '#10b981' : '#f59e0b'}; font-size: 20px;">
                            ${scheduledItems} / ${cart.length}
                        </div>
                    </div>
                </div>
            </div>
        `;

        cartDiv.innerHTML = html;
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    }

    // Product select change handler
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        if (productSelect) {
            productSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                const stock = option.dataset.stock;
                const infoDiv = document.getElementById('stockInfo');
                
                if (stock && this.value) {
                    infoDiv.innerHTML = `<span style="color: #059669; font-weight: 500;">✓ Available stock: ${stock} units</span>`;
                } else {
                    infoDiv.innerHTML = '';
                }
            });
        }
    });
</script>
@endsection
