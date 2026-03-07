@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-teal-500">
            <div class="flex items-center gap-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-teal-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
                    <p class="text-gray-600 mt-1">Comprehensive pharmacy reports and insights</p>
                </div>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <a href="{{ route('reports.inventory') }}" class="group">
                <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-all duration-200 hover:scale-105 border-l-4 border-teal-500">
                    <div class="text-center">
                        <div class="bg-teal-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-teal-200 transition-colors">
                            <i class="fas fa-boxes text-teal-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Inventory Report</h3>
                        <p class="text-gray-600">Stock levels and valuations</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('reports.sales') }}" class="group">
                <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-all duration-200 hover:scale-105 border-l-4 border-green-500">
                    <div class="text-center">
                        <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sales Report</h3>
                        <p class="text-gray-600">Revenue and trends</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('reports.expiry') }}" class="group">
                <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-all duration-200 hover:scale-105 border-l-4 border-red-500">
                    <div class="text-center">
                        <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors">
                            <i class="fas fa-clock text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Expiry Report</h3>
                        <p class="text-gray-600">Medication expiration tracking</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('reports.customers') }}" class="group">
                <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-all duration-200 hover:scale-105 border-l-4 border-blue-500">
                    <div class="text-center">
                        <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Customer Report</h3>
                        <p class="text-gray-600">Patient information and analytics</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('reports.suppliers') }}" class="group">
                <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-all duration-200 hover:scale-105 border-l-4 border-purple-500">
                    <div class="text-center">
                        <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                            <i class="fas fa-truck text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Supplier Report</h3>
                        <p class="text-gray-600">Vendor and supplier information</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Statistics -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-tachometer-alt"></i>
                    Quick Statistics
                </h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-teal-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-pills text-teal-600 text-3xl"></i>
                        </div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Total Medications</h6>
                        <h3 class="text-3xl font-bold text-teal-600">{{ \App\Models\Product::count() }}</h3>
                    </div>

                    <div class="text-center">
                        <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-green-600 text-3xl"></i>
                        </div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Total Patients</h6>
                        <h3 class="text-3xl font-bold text-green-600">{{ \App\Models\Customer::count() }}</h3>
                    </div>

                    <div class="text-center">
                        <div class="bg-orange-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-3xl"></i>
                        </div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Low Stock Items</h6>
                        <h3 class="text-3xl font-bold text-orange-600">{{ \App\Models\Product::withSum('stocks', 'quantity_stock')->get()->filter(fn($p) => $p->stocks_sum_quantity_stock <= ($p->min_stock ?? 10))->count() }}</h3>
                    </div>

                    <div class="text-center">
                        <div class="bg-red-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-red-600 text-3xl"></i>
                        </div>
                        <h6 class="text-gray-600 text-sm font-medium mb-2">Expiring Soon</h6>
                        <h3 class="text-3xl font-bold text-red-600">{{ \App\Models\Product::where('track_expiry', true)->whereNotNull('expiry_date')->where('expiry_date', '<=', now()->addDays(30))->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
