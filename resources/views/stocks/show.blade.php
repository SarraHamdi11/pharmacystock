@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('stocks.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Stock Entry Details</h1>
    </div>

    <!-- Details Card -->
    <div class="card-glass p-6 md:p-8 space-y-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center text-brand-600 text-3xl">
                <i class="fas fa-pills"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $stock->product->name }}</h2>
                <p class="text-slate-500">{{ $stock->product->category->name ?? 'Uncategorized' }}</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                        {{ $stock->product->code_bar ?? 'No Barcode' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-100 dark:border-slate-800 pt-8">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Storage Info</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Store Location</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $stock->store->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Store Address</span>
                        <span class="text-sm text-slate-900 dark:text-white">{{ $stock->store->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quantity Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Current Stock</span>
                        <span class="px-3 py-1 rounded-full font-bold text-sm {{ $stock->quantity_stock <= ($stock->product->min_stock ?? 10) ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                            {{ $stock->quantity_stock }} units
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Minimum Threshold</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $stock->product->min_stock ?? 10 }} units</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="text-xs text-slate-400">
                Record created on {{ $stock->created_at->format('M d, Y') }}<br>
                Last updated {{ $stock->updated_at->diffForHumans() }}
            </div>
            <div class="flex gap-3">
                <a href="{{ route('stocks.edit', $stock->id) }}" class="btn-secondary text-sm">
                    <i class="fas fa-edit mr-2"></i> Edit Quantity
                </a>
                @role('Admin')
                    <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary text-sm text-rose-600 border-rose-100 hover:bg-rose-50">
                            <i class="fas fa-trash-alt mr-2"></i> Remove Record
                        </button>
                    </form>
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection
