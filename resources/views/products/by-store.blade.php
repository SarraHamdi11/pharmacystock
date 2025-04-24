@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Products by Store</h2>
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
                <div class="col-md-6">
                    <select id="store-select" class="form-select">
                        <option value="">Select a Store</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="loading" class="d-none">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div id="products-container">
                <!-- Products will be loaded here via Axios -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('store-select').addEventListener('change', function() {
        const storeId = this.value;
        const loadingElement = document.getElementById('loading');
        const productsContainer = document.getElementById('products-container');
        productsContainer.innerHTML = '';
        if (storeId) {
            loadingElement.classList.remove('d-none');


            axios.get(`/api/products-by-store/${storeId}`)
                .then(response => {
                    const products = response.data;
                    let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr>';
                    html += '<th>Name</th><th>Category</th><th>Description</th><th>Price</th><th>Stock</th></tr></thead><tbody>';

                    if (products.length > 0) {
                        products.forEach(product => {
                            html += `<tr>
                                <td>${product.name}</td>
                                <td>${product.category.name}</td>
                                <td>${product.description}</td>
                                <td>$${parseFloat(product.price).toFixed(2)}</td>
                                <td>${product.stock ? product.stock.quantity_stock : 0}</td>
                            </tr>`;
                        });
                    } else {
                        html += '<tr><td colspan="5" class="text-center">No products found in this store</td></tr>';
                    }

                    html += '</tbody></table></div>';
                    productsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    productsContainer.innerHTML = '<div class="alert alert-danger">Error loading products</div>';
                })
                .finally(() => {
                    loadingElement.classList.add('d-none');
                });
        }
        else
        {
            productsContainer.innerHTML = 'there is no products for this store';
        }
    });
</script>
@endpush

@endsection
