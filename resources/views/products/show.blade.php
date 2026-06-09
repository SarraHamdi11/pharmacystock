@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('products.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
                <i class="fas fa-arrow-left"></i>
                Back to Medications
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $product->name }}</h1>
            <p class="text-slate-500">{{ $product->generic_name ?? 'No generic name' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('products.edit', $product->id) }}" class="btn-primary text-sm">
                <i class="fas fa-edit mr-2"></i> Edit Medication
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="card-glass p-6 md:p-8">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-slate-400">Category</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400">Barcode</span>
                            <p class="text-sm font-mono text-slate-900 dark:text-white">{{ $product->code_bar ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400">Manufacturer / Supplier</span>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $product->supplier->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-slate-400">Price</span>
                            <p class="text-lg font-black text-brand-600">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400">Expiry Tracking</span>
                            <p class="text-sm">
                                @if($product->track_expiry)
                                    <span class="text-emerald-600 font-bold"><i class="fas fa-check-circle mr-1"></i> Enabled</span>
                                @else
                                    <span class="text-slate-400 font-medium">Disabled</span>
                                @endif
                            </p>
                        </div>
                        @if($product->track_expiry && $product->expiry_date)
                            <div>
                                <span class="text-xs text-slate-400">Expiry Date</span>
                                <p class="text-sm font-bold {{ $product->is_expired ? 'text-rose-600' : ($product->is_expiring_soon ? 'text-amber-600' : 'text-slate-900 dark:text-white') }}">
                                    {{ $product->expiry_date->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Description</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $product->description ?? 'No description provided.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stock Status -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card-glass p-6 text-center">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Current Inventory</h3>
                <div class="text-4xl font-black mb-2 {{ $product->is_out_of_stock ? 'text-rose-600' : ($product->is_low_stock ? 'text-amber-600' : 'text-emerald-600') }}">
                    {{ $product->current_stock }}
                </div>
                <p class="text-xs text-slate-500 mb-6">units available across all stores</p>
                
                <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Min. Stock Level</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $product->min_stock }} units</span>
                    </div>
                </div>
            </div>

            <!-- Stores breakdown -->
            <div class="card-glass p-6">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Stock by Store</h3>
                <div class="space-y-4">
                    @forelse($product->stocks as $stock)
                        <div class="flex items-center justify-between border-b border-slate-50 dark:border-slate-800 pb-2 last:border-0">
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">{{ $stock->store->name }}</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $stock->quantity_stock }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No stock records found for this product.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
