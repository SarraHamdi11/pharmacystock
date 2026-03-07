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
                            <i class="fas fa-pills text-teal-600 text-xl"></i>
                        </span>
                        Medication Management
                    </h1>
                    <p class="text-gray-600 mt-2">Manage medications and inventory</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add New Medication
                    </a>
                    <a href="{{ route('dashboard.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Search Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('products.search') }}" method="GET" class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="term" id="productSearch" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200" placeholder="Search medications by name, category, or supplier..." value="{{ request('term') }}">
                </div>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
                @if(request('term'))
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Clear
                </a>
                @else
                <button type="button" onclick="clearProductSearch()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Clear
                </button>
                @endif
            </form>
        </div>

        <!-- Medications Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-pills"></i>
                    Medication Inventory
                </h2>
            </div>
            
            <!-- Responsive Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $product->stock->quantity_stock ?? 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->supplier->first_name }} {{ $product->supplier->last_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition-colors" onclick="return confirm('Delete this medication?')">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-center mt-4">
        @if($products->lastPage() > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                {{-- Previous page link --}}
                @if($products->currentPage() > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $products->url($products->currentPage() - 1) }}"
                        data-page="{{ $products->currentPage() - 1 }}">
                        <<< /a>
                </li>
                @endif

                {{-- Page numbers --}}
                @for($i = 1; $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $i === $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}" data-page="{{ $i }}">{{ $i }}</a>
                    </li>
                    @endfor

                    {{-- Next page link --}}
                    @if($products->currentPage() < $products->lastPage())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url($products->currentPage() + 1) }}" data-page="{{ $products->currentPage() + 1 }}">
                                Next &raquo;
                            </a>
                        </li>
                    @endif
            </ul>
        </nav>
        @endif
    </div>
</div>
</div>

@include('products.partials.create-modal')
@include('products.partials.edit-modal')
@include('products.partials.import-modal')

@push('scripts')
<script>
// Simple clear function for form-based search
function clearProductSearch() {
    window.location.href = '{{ route("products.index") }}';
}
</script>
@endpush

@endsection