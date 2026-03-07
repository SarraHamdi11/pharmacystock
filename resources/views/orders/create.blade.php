@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Create New Order</h3>
                <p class="mt-1 text-sm text-gray-500">Create a new prescription order</p>
            </div>
            
            <form action="{{ route('orders.store') }}" method="POST" class="px-4 py-5 sm:p-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Customer -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <option value="">Select a customer</option>
                            @foreach(\App\Models\Customer::all() as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Date -->
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" required
                               value="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                                  placeholder="Add any notes or special instructions..."></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Products Section -->
                <div class="mt-8 border-t pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Order Products</h4>
                    <div id="products-container" class="space-y-4">
                        <div class="product-row grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <select name="products[]" class="product-select rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                <option value="">Select product</option>
                                @foreach(\App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantities[]" placeholder="Quantity" min="1" 
                                   class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <input type="number" name="prices[]" placeholder="Price" step="0.01" min="0"
                                   class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <button type="button" onclick="removeProductRow(this)" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
                                Remove
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addProductRow()" 
                            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        + Add Product
                    </button>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('orders.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addProductRow() {
    const container = document.getElementById('products-container');
    const newRow = document.createElement('div');
    newRow.className = 'product-row grid grid-cols-1 gap-4 sm:grid-cols-4';
    newRow.innerHTML = `
        <select name="products[]" class="product-select rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            <option value="">Select product</option>
        @foreach(\App\Models\Product::all() as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
        </select>
        <input type="number" name="quantities[]" placeholder="Quantity" min="1" 
               class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
        <input type="number" name="prices[]" placeholder="Price" step="0.01" min="0"
               class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
        <button type="button" onclick="removeProductRow(this)" 
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
            Remove
        </button>
    `;
    container.appendChild(newRow);
}

function removeProductRow(button) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length > 1) {
        button.closest('.product-row').remove();
    }
}
</script>
@endsection
