@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Medication Inventory</h1>
            <p class="text-slate-500 dark:text-slate-400">Manage your stock, prices, and suppliers in one place.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-secondary text-sm" onclick="window.location.reload()">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
            <a href="{{ route('products.create') }}" class="btn-primary text-sm">
                <i class="fas fa-plus mr-2"></i> Add Medication
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card-glass p-4">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="term" value="{{ request('term') }}" 
                       placeholder="Search by name, generic, or SKU..." 
                       class="input-modern pl-10">
            </div>
            
            <div class="flex flex-wrap gap-3">
                <select name="category" class="input-modern w-full md:w-48 bg-slate-50 border-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn-primary px-6">
                    Filter
                </button>
                
                @if(request()->hasAny(['term', 'category']))
                    <a href="{{ route('products.index') }}" class="btn-secondary px-4 text-rose-600 border-rose-100 hover:bg-rose-50">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="card-glass p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Category</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Stock</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $stockCount = $product->stocks->sum('quantity_stock');
                            $isLowStock = $stockCount <= $product->min_stock;
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        @if($product->picture)
                                            <img src="{{ asset('storage/' . $product->picture) }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="fas fa-pills"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $product->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $product->generic_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="text-right font-mono font-bold text-slate-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="text-right">
                                <div class="font-bold {{ $isLowStock ? 'text-rose-600' : 'text-slate-900 dark:text-white' }}">
                                    {{ $stockCount }}
                                </div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Min: {{ $product->min_stock }}</div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-600 dark:text-slate-300">{{ $product->supplier->first_name }} {{ $product->supplier->last_name }}</div>
                            </td>
                            <td>
                                @if($isLowStock)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                        Low Stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Healthy
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @role('Admin')
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Archive this medication?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <div class="text-slate-500 font-medium">No medications found matching your criteria.</div>
                                    <a href="{{ route('products.create') }}" class="text-brand-600 font-bold hover:underline">Add your first medication</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
