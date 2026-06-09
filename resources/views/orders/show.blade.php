@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('orders.index') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
                <i class="fas fa-arrow-left"></i>
                Back to Orders
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                Order {{ $order->order_number }}
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                    {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : 
                       ($order->status === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-600') }}">
                    {{ $order->status ?? 'completed' }}
                </span>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="btn-secondary text-sm">
                <i class="fas fa-print mr-2"></i> Print Invoice
            </button>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn-primary text-sm">
                <i class="fas fa-edit mr-2"></i> Edit Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="card-glass p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm uppercase tracking-wider">Order Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Medication</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Qty</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Unit Price</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($order->products as $product)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $product->name }}</div>
                                        <div class="text-[10px] text-slate-500 uppercase">{{ $product->category->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-slate-600 dark:text-slate-400">
                                        {{ $product->pivot->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-slate-600 dark:text-slate-400">
                                        ${{ number_format($product->pivot->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-900 dark:text-white">
                                        ${{ number_format($product->pivot->quantity * $product->pivot->price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-slate-500 uppercase tracking-wider">Grand Total</td>
                                <td class="px-6 py-4 text-right text-xl font-black text-brand-600">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Patient Card -->
            <div class="card-glass p-6">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Patient Information</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                        {{ strtoupper(substr($order->customer->first_name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Walk-in Customer' }}</div>
                        <div class="text-xs text-slate-500">{{ $order->customer->email ?? 'No email' }}</div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Phone</span>
                        <span class="text-xs font-medium text-slate-900 dark:text-white">{{ $order->customer->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Order Date</span>
                        <span class="text-xs font-medium text-slate-900 dark:text-white">{{ $order->order_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes/Status Card -->
            <div class="card-glass p-6">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Transaction Details</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">System Reference</span>
                        <div class="text-xs font-mono bg-slate-50 dark:bg-slate-800 p-2 rounded-lg mt-1 border border-slate-100 dark:border-slate-700">
                            #{{ $order->id }} - {{ $order->created_at->format('Ymd-His') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
