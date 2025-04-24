@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Customer Orders</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row mb-4" id="customerSearchContainer">
        <div class="col-md-6">
            <div id="customerSearchSection">
                <form id="customer-search-form" class="d-flex gap-2">
                    <div class="form-group flex-grow-1">
                        <label for="customer-search">Search Customer:</label>
                        <input type="text" id="customer-search" class="form-control"
                            placeholder="Type customer's last name..." required>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" id="searchCustomers" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6" id="lstCustomers">
            <!-- Customer list will be loaded here -->
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6" id="lstOrders">
            <!-- Orders will be loaded here -->
        </div>
        <div class="col-md-6" id="orderDetails">
            <!-- Order details will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadOrderDetails(orderId) {
        axios.get(`/api/orders/${orderId}/details`)
            .then(response => {
                $('#orderDetails').html(response.data);
            });
    }

    function displayCustomers(customers) {
        let html = '<div class="d-flex justify-content-between align-items-center mb-3">';
        html += '<h3>Customers Found</h3>';
        html += '<button class="btn btn-outline-secondary btn-sm" onclick="toggleCustomerList()">';
        html += '<i class="bi bi-list"></i> Toggle Customer List</button>';
        html += '</div>';
        html += '<div class="collapse show" id="customerListCollapse"><div class="list-group">';
        customers.forEach(customer => {
            html += `
                <a href="#" class="list-group-item list-group-item-action" onclick="loadCustomerOrders(${customer.id})">
                    ${customer.first_name} ${customer.last_name}
                </a>
            `;
        });
        html += '</div></div>';
        $('#lstCustomers').html(html);
    }

    function toggleCustomerList() {
        $('#customerListCollapse').collapse('toggle');
    }

    function loadCustomerOrders(customerId) {
        // Clear previous order details
        $('#orderDetails').empty();
        $('#lstOrders').empty();
        axios.get(`/api/customers/${customerId}/orders`)
            .then(response => {
                const orders = response.data;
                let html = '<div class="mb-3">';
                html += '<h3>Customer Orders</h3>';
                html += '</div><div class="list-group">';
                orders.forEach(order => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action" onclick="loadOrderDetails(${order.id})">
                            Order #${order.id} - ${order.created_at}
                        </a>
                    `;
                });
                html += '</div>';
                $('#lstOrders').html(html);

                // Automatically collapse the customer list after selection
                $('#customerListCollapse').collapse('hide');
            });
    }

    $(document).ready(function() {
        $('#customer-search-form').on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $('#customer-search').val();
            $('#lstOrders').html('');
            $('#orderDetails').empty();

            axios.get(`/api/customers/search/${searchTerm}`)
                .then(response => {
                    displayCustomers(response.data);
                });
        });
    });
</script>
@endpush

@endsection
