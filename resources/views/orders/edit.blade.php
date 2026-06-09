@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('orders.show', $order->id) }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i>
            Back to Order Details
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Order #{{ $order->order_number }}</h1>
    </div>

    <div class="card-glass p-8">
        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="col-span-1">
                    <label for="customer_id" class="label-modern">Patient / Customer</label>
                    <select name="customer_id" id="customer_id" class="input-modern" required>
                        @foreach(\App\Models\Customer::all() as $customer)
                            <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Date -->
                <div class="col-span-1">
                    <label for="order_date" class="label-modern">Order Date</label>
                    <input type="datetime-local" name="order_date" id="order_date" 
                           value="{{ $order->order_date->format('Y-m-d\TH:i') }}" 
                           class="input-modern" required>
                </div>

                <!-- Status -->
                <div class="col-span-1">
                    <label for="status" class="label-modern">Status</label>
                    <select name="status" id="status" class="input-modern" required>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('orders.show', $order->id) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary px-8">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
