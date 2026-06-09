@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-teal-500">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="bg-teal-100 p-2 rounded-lg">
                            <i class="fas fa-clock text-teal-600 text-xl"></i>
                        </span>
                        Expiry Report
                    </h1>
                    <p class="text-gray-600 mt-2">Monitor medication expiry dates and stock management</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Filters</h2>
            <form method="GET" action="{{ route('reports.expiry') }}">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Period</label>
                        <select name="expiry_period" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            <option value="">All Medications</option>
                            <option value="7" {{ request('expiry_period') == '7' ? 'selected' : '' }}>Expiring in 7 days</option>
                            <option value="30" {{ request('expiry_period') == '30' ? 'selected' : '' }}>Expiring in 30 days</option>
                            <option value="60" {{ request('expiry_period') == '60' ? 'selected' : '' }}>Expiring in 60 days</option>
                            <option value="90" {{ request('expiry_period') == '90' ? 'selected' : '' }}>Expiring in 90 days</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('reports.expiry') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-2">Expired</p>
                        <p class="text-3xl font-bold text-red-600">{{ $expired->count() }}</p>
                        <p class="text-gray-500 text-xs mt-1">Need immediate disposal</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <span class="text-2xl">⚠️</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-2">Expiring Soon</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $expiringSoon->count() }}</p>
                        <p class="text-gray-500 text-xs mt-1">Plan for replacement</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <span class="text-2xl">⏰</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-2">Good Stock</p>
                        <p class="text-3xl font-bold text-green-600">{{ $good->count() }}</p>
                        <p class="text-gray-500 text-xs mt-1">No expiry concerns</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Expiry Results ({{ $products->count() }} medications)
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->supplier->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->current_stock ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->expiry_date ? $product->expiry_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($product->is_expired ?? false)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Expired
                                        </span>
                                    @elseif($product->is_expiring_soon ?? false)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Expiring Soon
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Good
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
