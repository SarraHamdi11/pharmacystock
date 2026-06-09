@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Order Management</h1>
            <p class="text-slate-500 dark:text-slate-400">Track prescriptions, sales, and order status.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.create') }}" class="btn-primary text-sm">
                <i class="fas fa-plus mr-2"></i> Create New Order
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card-glass p-4">
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="term" value="{{ request('term') }}" 
                       placeholder="Search by order # or patient name..." 
                       class="input-modern pl-10">
            </div>
            <button type="submit" class="btn-primary px-8">Search</button>
            @if(request()->hasAny(['term', 'customer_id']))
                <a href="{{ route('orders.index') }}" class="btn-secondary px-4 text-rose-600 border-rose-100">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card-glass p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th class="text-right">Total Amount</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <div class="font-mono font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                                    {{ $order->order_number }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 text-xs font-bold">
                                        {{ strtoupper(substr($order->customer->first_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $order->customer->first_name ?? 'Walk-in' }} {{ $order->customer->last_name ?? 'Customer' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $order->created_at->format('M j, Y H:i') }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="font-bold text-slate-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">
                                    {{ $order->products->count() }} items
                                </div>
                            </td>
                            <td>
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 
                                       ($order->status === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $order->status ?? 'completed' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all" title="Print Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @role('Admin')
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this order?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Cancel Order">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-file-invoice text-2xl"></i>
                                    </div>
                                    <div class="text-slate-500 font-medium">No orders found.</div>
                                    <a href="{{ route('orders.create') }}" class="text-brand-600 font-bold hover:underline">Create your first order</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
