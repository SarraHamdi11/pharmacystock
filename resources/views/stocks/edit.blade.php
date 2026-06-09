@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('stocks.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Stock Entry</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm">Update medication quantity for {{ $stock->product->name }} in {{ $stock->store->name }}.</p>
    </div>

    <!-- Form Card -->
    <div class="card-glass p-6 md:p-8">
        <form action="{{ route('stocks.update', $stock->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product (Read-only for edit) -->
                <div class="col-span-1">
                    <label class="label-modern">Medication</label>
                    <input type="text" class="input-modern bg-slate-50 text-slate-500 cursor-not-allowed" value="{{ $stock->product->name }}" disabled>
                    <input type="hidden" name="product_id" value="{{ $stock->product_id }}">
                </div>

                <!-- Store (Read-only for edit) -->
                <div class="col-span-1">
                    <label class="label-modern">Store Location</label>
                    <input type="text" class="input-modern bg-slate-50 text-slate-500 cursor-not-allowed" value="{{ $stock->store->name }}" disabled>
                    <input type="hidden" name="store_id" value="{{ $stock->store_id }}">
                </div>

                <!-- Quantity -->
                <div class="col-span-1">
                    <label for="quantity_stock" class="label-modern">Quantity</label>
                    <div class="relative">
                        <i class="fas fa-boxes absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="number" name="quantity_stock" id="quantity_stock" value="{{ old('quantity_stock', $stock->quantity_stock) }}" 
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
                    Update Quantity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
