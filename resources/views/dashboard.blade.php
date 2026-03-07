@extends('layouts.app')

@section('content')
@include('components.loading-screen')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ auth()->user()->name ?? 'Pharmacy Manager' }}! 👋
                    </h1>
                    <p class="text-gray-600">Here's what's happening in your pharmacy today</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-lg font-semibold text-teal-600">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-teal-600"></i>
                Quick Actions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('products.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-105">
                    <i class="fas fa-plus"></i>
                    Add Medication
                </a>
                <a href="{{ route('customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-105">
                    <i class="fas fa-user-plus"></i>
                    Add Patient
                </a>
                <a href="{{ route('products.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-105">
                    <i class="fas fa-pills"></i>
                    View Medications
                </a>
                <a href="{{ route('customers.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 transform hover:scale-105">
                    <i class="fas fa-users"></i>
                    View Patients
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Medications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-500 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-teal-100 p-3 rounded-lg">
                        <i class="fas fa-pills text-teal-600 text-2xl"></i>
                    </div>
                    <span class="text-green-600 text-sm font-medium">+12%</span>
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Total Medications</h3>
                <p class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['products'] ?? 0 }}</p>
                <p class="text-gray-500 text-xs">In inventory</p>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                    </div>
                    <span class="text-red-600 text-sm font-medium">-5%</span>
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Low Stock Items</h3>
                <p class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['low_stock_count'] ?? 0 }}</p>
                <p class="text-gray-500 text-xs">Need restock</p>
            </div>

            <!-- Total Patients -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <span class="text-green-600 text-sm font-medium">+8%</span>
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Total Patients</h3>
                <p class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['customers'] ?? 0 }}</p>
                <p class="text-gray-500 text-xs">Registered</p>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-shopping-cart text-purple-600 text-2xl"></i>
                    </div>
                    <span class="text-green-600 text-sm font-medium">+15%</span>
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Total Orders</h3>
                <p class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['orders'] ?? 0 }}</p>
                <p class="text-gray-500 text-xs">This month</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Inventory Alerts Widget -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-bell text-orange-500"></i>
                        Inventory Alerts
                    </h3>
                    <div class="space-y-3">
                        @php
                            $lowStockItems = \App\Models\Product::with(['category', 'stocks'])
                                ->whereHas('stocks', function($query) {
                                    $query->where('quantity_stock', '<=', 10);
                                })
                                ->limit(5)
                                ->get();
                            
                            $expiringItems = \App\Models\Product::where('track_expiry', true)
                                ->whereNotNull('expiry_date')
                                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                                ->limit(3)
                                ->get();
                            
                            $outOfStockItems = \App\Models\Product::with(['category', 'stocks'])
                                ->whereHas('stocks', function($query) {
                                    $query->where('quantity_stock', '=', 0);
                                })
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        <!-- Low Stock Alerts -->
                        @if($lowStockItems->count() > 0)
                            @foreach($lowStockItems as $item)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->stocks->first()->quantity_stock ?? 0 }} units left</p>
                                    </div>
                                    <span class="text-red-600 text-xs font-medium">Low Stock</span>
                                </div>
                            @endforeach
                        @endif
                        
                        <!-- Expiring Items -->
                        @if($expiringItems->count() > 0)
                            @foreach($expiringItems as $item)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                        <p class="text-xs text-gray-500">Expires in {{ $item->expiry_date->diffInDays(now()) }} days</p>
                                    </div>
                                    <span class="text-yellow-600 text-xs font-medium">Expiring</span>
                                </div>
                            @endforeach
                        @endif
                        
                        <!-- Out of Stock Items -->
                        @if($outOfStockItems->count() > 0)
                            @foreach($outOfStockItems as $item)
                                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                        <p class="text-xs text-gray-500">Out of stock</p>
                                    </div>
                                    <span class="text-orange-600 text-xs font-medium">Out of Stock</span>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($lowStockItems->count() == 0 && $expiringItems->count() == 0 && $outOfStockItems->count() == 0)
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                                <p class="text-gray-600 text-sm">All inventory levels are healthy!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-shopping-cart text-purple-500"></i>
                            Recent Orders
                        </h3>
                        <a href="{{ route('orders.index') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">View All</a>
                    </div>
                    
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $recentOrders = \App\Models\Order::with('customer')->latest()->take(5)->get();
                                @endphp
                                @if($recentOrders->count() > 0)
                                    @foreach($recentOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Unknown' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($order->total ?? 0, 2) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    @if($order->status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($order->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-teal-600 hover:text-teal-700">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No recent orders found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-3">
                        @if($recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Unknown' }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            @if($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm font-medium text-gray-900">${{ number_format($order->total ?? 0, 2) }}</p>
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-teal-600 hover:text-teal-700 text-sm">View</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-cart text-gray-300 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">No recent orders found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Analytics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Sales Trend Chart -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-teal-500"></i>
                        Sales Trend (7 Days)
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Medications -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-pills text-purple-500"></i>
                        Top Medications
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="topMedicationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed & Tasks Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Patient Activity Feed -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-heartbeat text-red-500"></i>
                    Patient Activity Feed
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @php
                        $recentActivities = \App\Models\Order::with('customer')
                            ->latest()
                            ->take(10)
                            ->get();
                    @endphp
                    @if($recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="bg-red-100 p-2 rounded-full">
                                    <i class="fas fa-prescription text-red-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        <strong>{{ $activity->customer ? $activity->customer->first_name . ' ' . $activity->customer->last_name : 'Unknown' }}</strong>
                                        placed an order
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-heartbeat text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500 text-sm">No recent patient activity</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tasks & Reminders -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-tasks text-blue-500"></i>
                    Tasks & Reminders
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Check expiring medications</p>
                            <p class="text-xs text-gray-500">Due today</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                        <input type="checkbox" class="w-4 h-4 text-yellow-600 rounded focus:ring-yellow-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Order low stock items</p>
                            <p class="text-xs text-gray-500">Due tomorrow</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                        <input type="checkbox" checked class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 line-through">Update inventory records</p>
                            <p class="text-xs text-gray-500">Completed</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                        <input type="checkbox" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Review patient prescriptions</p>
                            <p class="text-xs text-gray-500">Due this week</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg">
                        <input type="checkbox" class="w-4 h-4 text-orange-600 rounded focus:ring-orange-500">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Schedule supplier deliveries</p>
                            <p class="text-xs text-gray-500">Due next week</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
</div>

<!-- Enhanced Charts Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Sales Trend Chart (7 Days)
    const salesTrendCtx = document.getElementById('salesTrendChart');
    if (salesTrendCtx) {
        new Chart(salesTrendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Daily Sales',
                    data: [1200, 1900, 1500, 2100, 2400, 1800, 2200],
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0d9488',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Sales: $' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { 
                            font: { size: 12 },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    }

    // Top Medications Bar Chart
    const topMedicationsCtx = document.getElementById('topMedicationsChart');
    if (topMedicationsCtx) {
        new Chart(topMedicationsCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Aspirin', 'Ibuprofen', 'Amoxicillin', 'Metformin', 'Lisinopril'],
                datasets: [{
                    label: 'Units Sold',
                    data: [450, 380, 320, 290, 260],
                    backgroundColor: '#8b5cf6',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Units: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // Task checkbox interactions
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.parentElement.querySelector('p.text-sm');
            if (this.checked) {
                label.classList.add('line-through');
                label.style.opacity = '0.6';
            } else {
                label.classList.remove('line-through');
                label.style.opacity = '1';
            }
        });
    });
});
</script>
@endsection
