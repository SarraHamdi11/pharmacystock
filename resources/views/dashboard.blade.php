@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="dashboard">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Pharmacy Overview</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Welcome back, <span class="text-brand-600 font-semibold">{{ auth()->user()->name ?? 'Manager' }}</span>. Here's your pharmacy's performance today.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold animate-pulse">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                Live Updates Active
            </div>
            <button class="btn-secondary text-sm shadow-sm">
                <i class="fas fa-calendar-alt mr-2"></i> {{ now()->format('M d, Y') }}
            </button>
            <a href="{{ route('orders.create') }}" class="btn-primary text-sm shadow-lg shadow-brand-600/20">
                <i class="fas fa-plus mr-2"></i> New Order
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach([
            ['label' => 'Monthly Revenue', 'value' => '$'.number_format($stats['revenue_month'] ?? 0, 0), 'icon' => 'fa-dollar-sign', 'color' => 'brand', 'trend' => '+12.5%', 'desc' => 'vs last month'],
            ['label' => 'Orders Processed', 'value' => $stats['orders'] ?? 0, 'icon' => 'fa-shopping-cart', 'color' => 'blue', 'trend' => '+5.2%', 'desc' => 'this month'],
            ['label' => 'Low Stock Items', 'value' => $stats['low_stock_count'] ?? 0, 'icon' => 'fa-exclamation-triangle', 'color' => 'orange', 'trend' => $stats['low_stock_count'] > 5 ? 'High Risk' : 'Manageable', 'desc' => 'needs attention'],
            ['label' => 'New Patients', 'value' => $stats['customers'] ?? 0, 'icon' => 'fa-users', 'color' => 'emerald', 'trend' => '+18', 'desc' => 'this week'],
        ] as $stat)
            <div class="stat-card hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] === 'brand' ? 'brand' : $stat['color'] }}-50 dark:bg-{{ $stat['color'] === 'brand' ? 'brand' : $stat['color'] }}-900/20 flex items-center justify-center text-{{ $stat['color'] === 'brand' ? 'brand' : $stat['color'] }}-600 dark:text-{{ $stat['color'] === 'brand' ? 'brand' : $stat['color'] }}-400">
                        <i class="fas {{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg {{ str_contains($stat['trend'], '+') || $stat['trend'] === 'Manageable' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $stat['trend'] }}
                        </span>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">{{ $stat['desc'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stat['value'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach([
            ['label' => 'Add Product', 'icon' => 'fa-plus-circle', 'route' => 'products.create', 'color' => 'brand'],
            ['label' => 'Inventory', 'icon' => 'fa-boxes', 'route' => 'stocks.index', 'color' => 'blue'],
            ['label' => 'Suppliers', 'icon' => 'fa-truck', 'route' => 'suppliers.index', 'color' => 'indigo'],
            ['label' => 'Reports', 'icon' => 'fa-chart-bar', 'route' => 'reports.index', 'color' => 'amber'],
            ['label' => 'Settings', 'icon' => 'fa-cog', 'route' => 'dashboard.index', 'color' => 'slate'],
            ['label' => 'Help', 'icon' => 'fa-question-circle', 'route' => 'dashboard.index', 'color' => 'slate'],
        ] as $action)
            <a href="{{ route($action['route']) }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:bg-{{ $action['color'] }}-50 dark:hover:bg-{{ $action['color'] }}-900/20 hover:border-{{ $action['color'] }}-200 transition-all group">
                <i class="fas {{ $action['icon'] }} text-xl text-slate-400 group-hover:text-{{ $action['color'] === 'brand' ? 'brand' : $action['color'] }}-600 mb-2"></i>
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white">{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-glass p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-900 dark:text-white">Revenue Trend</h3>
                <select class="bg-transparent text-sm border-none focus:ring-0 text-slate-500 cursor-pointer">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                </select>
            </div>
            <div class="h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-900 dark:text-white">Inventory by Category</h3>
                <i class="fas fa-ellipsis-h text-slate-400"></i>
            </div>
            <div class="h-[300px]">
                <canvas id="inventoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Action Tasks -->
        <div class="lg:col-span-1 space-y-4">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fas fa-tasks text-brand-600"></i>
                Urgent Actions
            </h3>
            <div class="space-y-3">
                @foreach($tasks as $task)
                    <a href="{{ $task['url'] }}" class="block p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-brand-500/50 transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">{{ $task['title'] }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ $task['detail'] }}</p>
                            </div>
                            <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $task['urgency'] === 'high' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $task['urgency'] }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity/Alerts -->
        <div class="lg:col-span-2 card-glass p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white">Stock Alerts</h3>
                <a href="{{ route('reports.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Category</th>
                            <th>Stock Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="stockAlertsBody">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', () => ({
            init() {
                this.loadCharts();
                this.loadStockAlerts();
            },

            async loadCharts() {
                const salesRes = await fetch('/api/dashboard/sales-analytics');
                const salesData = await salesRes.json();
                
                const inventoryRes = await fetch('/api/dashboard/inventory-analytics');
                const inventoryData = await inventoryRes.json();

                new Chart(document.getElementById('revenueChart'), {
                    type: 'line',
                    data: {
                        labels: salesData.data.labels,
                        datasets: [{
                            label: 'Revenue',
                            data: salesData.data.revenue,
                            borderColor: '#0d9488',
                            backgroundColor: 'rgba(13, 148, 136, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#0d9488',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                new Chart(document.getElementById('inventoryChart'), {
                    type: 'doughnut',
                    data: {
                        labels: inventoryData.data.labels,
                        datasets: [{
                            data: inventoryData.data.values,
                            backgroundColor: [
                                '#0d9488', '#0ea5e9', '#6366f1', '#f59e0b', '#ef4444', '#10b981'
                            ],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { 
                                position: 'bottom',
                                labels: { usePointStyle: true, padding: 20 }
                            } 
                        }
                    }
                });
            },

            async loadStockAlerts() {
                const res = await fetch('/api/dashboard/low-stock');
                const data = await res.json();
                const body = document.getElementById('stockAlertsBody');
                
                body.innerHTML = data.data.map(item => `
                    <tr>
                        <td class="font-semibold text-slate-900 dark:text-white">${item.name}</td>
                        <td class="text-slate-500">${item.category || 'N/A'}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-24 h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-rose-500" style="width: ${Math.min(item.stock * 10, 100)}%"></div>
                                </div>
                                <span class="text-xs font-bold text-rose-600">${item.stock} left</span>
                            </div>
                        </td>
                        <td>
                            <a href="/products/${item.id}/edit" class="text-slate-400 hover:text-brand-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                `).join('');
            }
        }));
    });
</script>
@endpush
@endsection
