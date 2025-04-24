@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Products by Category</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <select class="form-select" onchange="window.location.href=this.value">
                        <option value="{{ route('products.by.category') }}" {{ !request()->route('category') ? 'selected' : '' }}>Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ route('products.filter.by.category', $cat) }}" {{ request()->route('category')?->id === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock ?   $product->stock->quantity_stock:0}}</td>
                                <td>{{ $product->supplier->first_name }} {{ $product->supplier->last_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No products found in this category</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
