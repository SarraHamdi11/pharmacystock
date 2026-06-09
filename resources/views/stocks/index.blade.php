@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Inventory Management</h1>
            <p class="text-slate-500 dark:text-slate-400">Monitor and adjust stock levels across all stores.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('stocks.create') }}" class="btn-primary text-sm">
                <i class="fas fa-plus mr-2"></i> Add Stock Entry
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card-glass p-4">
        <form action="{{ route('stocks.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="term" value="{{ request('term') }}" 
                       placeholder="Search by product name or store..." 
                       class="input-modern pl-10">
            </div>
            <button type="submit" class="btn-primary px-8">Search</button>
            @if(request()->has('term'))
                <a href="{{ route('stocks.index') }}" class="btn-secondary px-4 text-rose-600 border-rose-100">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Stocks Table -->
    <div class="card-glass p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Store Location</th>
                        <th class="text-center">Current Quantity</th>
                        <th>Last Updated</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-brand-600">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $stock->product->name ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-slate-500 uppercase font-bold">{{ $stock->product->category->name ?? 'Uncategorized' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-shop text-xs"></i>
                                    <span class="text-sm font-medium">{{ $stock->store->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="inline-flex items-center px-3 py-1 rounded-full font-bold text-sm
                                    {{ $stock->quantity_stock <= ($stock->product->min_stock ?? 10) ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $stock->quantity_stock }}
                                </div>
                            </td>
                            <td>
                                <div class="text-xs text-slate-500">
                                    {{ $stock->updated_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('stocks.edit', $stock->id) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @role('Admin')
                                        <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this stock entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-boxes text-4xl text-slate-200"></i>
                                    <p>No inventory records found.</p>
                                    <a href="{{ route('stocks.create') }}" class="text-brand-600 font-bold hover:underline">Add your first stock entry</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
@endsection
