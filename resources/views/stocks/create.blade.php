@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('stocks.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Add Stock Entry</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm">Register a new medication quantity for a specific store location.</p>
    </div>

    <!-- Form Card -->
    <div class="card-glass p-6 md:p-8">
        <form action="{{ route('stocks.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Selection -->
                <div class="col-span-1">
                    <label for="product_id" class="label-modern">Medication</label>
                    <select name="product_id" id="product_id" class="input-modern @error('product_id') border-rose-500 @enderror" required>
                        <option value="">Select Medication...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->category->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Store Selection -->
                <div class="col-span-1">
                    <label for="store_id" class="label-modern">Store Location</label>
                    <select name="store_id" id="store_id" class="input-modern @error('store_id') border-rose-500 @enderror" required>
                        <option value="">Select Store...</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('store_id')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div class="col-span-1">
                    <label for="quantity_stock" class="label-modern">Quantity</label>
                    <div class="relative">
                        <i class="fas fa-boxes absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="number" name="quantity_stock" id="quantity_stock" value="{{ old('quantity_stock', 0) }}" 
                               class="input-modern pl-10 @error('quantity_stock') border-rose-500 @enderror" 
                               placeholder="0" min="0" required>
                    </div>
                    @error('quantity_stock')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('stocks.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary px-8">
                    Save Entry
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
