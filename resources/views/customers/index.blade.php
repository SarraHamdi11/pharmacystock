@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Customer List</h2>

            <a href="{{ route('customers.create') }}" class="btn btn-success d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
                Add New Customer
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary d-flex align-items-center gap-2 ms-2">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Dashboard
            </a>

        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="customerSearch" class="form-control" placeholder="Search customers...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd"
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pagination d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        @if ($customers->currentPage() == 1)
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $customers->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @endif

                        <!-- Pagination Elements -->
                        @for ($i = 1; $i <= $customers->lastPage(); $i++)
                            <li class="page-item {{ ($customers->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $customers->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor

                            <!-- Next Page Link -->
                            @if ($customers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $customers->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        function loadPage(url, searchTerm) {
            $.ajax({
                url: url || '{{ route("customers.search") }}',
                type: 'GET',
                data: { term: searchTerm },
                success: function(response) {
                    var tableBody = $('#customerTableBody');
                    tableBody.empty();

                    if(response.customers.length > 0) {
                        response.customers.forEach(function(customer) {
                            var row = `<tr>
                                <td>${customer.first_name} ${customer.last_name}</td>
                                <td>${customer.email}</td>
                                <td>${customer.phone}</td>
                                <td>${customer.address}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/customers/${customer.id}/edit" class="btn btn-sm btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="/customers/${customer.id}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>`;
                            tableBody.append(row);
                        });

                        // Update pagination
                        var pagination = response.pagination;
                        var paginationHtml = '';
                        
                        // Previous page link
                        paginationHtml += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${pagination.current_page - 1}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>`;

                        // Page numbers
                        for (var i = 1; i <= pagination.last_page; i++) {
                            paginationHtml += `<li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>`;
                        }

                        // Next page link
                        paginationHtml += `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${pagination.current_page + 1}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>`;

                        $('.pagination').html(paginationHtml);
                    } else {
                        tableBody.html('<tr><td colspan="5" class="text-center">No customers found</td></tr>');
                        $('.pagination').empty();
                    }
                }
            });
        }

        $('#customerSearch').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                var searchTerm = $(this).val();
                if(searchTerm.length > 0) {
                    loadPage(null, searchTerm);
                }
            }
        });

        // Handle pagination clicks
        $(document).on('click', '.pagination .page-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            var searchTerm = $('#customerSearch').val();
            loadPage(`{{ route('customers.search') }}?page=${page}`, searchTerm);
        });
                                // Populate table with search results
                                $.each(response.customers, function(index, customer) {
                                    var row = `<tr>
                                        <td>${customer.first_name} ${customer.last_name}</td>
                                        <td>${customer.email}</td>
                                        <td>${customer.phone}</td>
                                        <td>${customer.address}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="/customers/${customer.id}/edit" class="btn btn-sm btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="/customers/${customer.id}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                        <path fill-rule="evenodd"
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                        </path>
                                                    </svg>
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                        </tr>`;
                                    tableBody.append(row);
                                });

                        // Update pagination with links from response
                                updatePagination(response.pagination);
                            } else {
                                tableBody.append('<tr><td colspan="5" class="text-center">No customers found</td></tr>');
                                // Clear pagination if no results
                                paginationContainer.empty();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error searching customers:', xhr);
                        }
                    });
                } else {
                    // If search field is empty, reload the original data with pagination
                    window.location.href = '{{ route("customers.index") }}';
                }
            }
    });

    // Function to update pagination based on response data
    function updatePagination(pagination) {
        var paginationContainer = $('.pagination');
        paginationContainer.empty();

        // Create pagination element
        var paginationHtml = '<nav><ul class="pagination">';

        // Previous page link
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
        }

        // Page links
        for (var i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                paginationHtml += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        // Next page link
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.html(paginationHtml);

        // Add click event for pagination links
        $('.pagination .page-link').on('click', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page) {
                var searchTerm = $('#customerSearch').val();
                loadCustomers(searchTerm, page);
            }
        });
    }

    // Function to load customers with pagination
    function loadCustomers(searchTerm, page) {
        $.ajax({
            url: '{{ route("customers.search") }}',
            type: 'GET',
            data: {
                term: searchTerm,
                page: page
            },
            success: function(response) {
                var tableBody = $('#customerTableBody');
                tableBody.empty();

                if(response.customers.length > 0) {
                    // Populate table with search results
                    $.each(response.customers, function(index, customer) {
                        var row = `<tr>
                            <td>${customer.first_name} ${customer.last_name}</td>
                            <td>${customer.email}</td>
                            <td>${customer.phone}</td>
                            <td>${customer.address}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/customers/${customer.id}/edit" class="btn btn-sm btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="/customers/${customer.id}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                            <path fill-rule="evenodd"
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                        </svg>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>`;
                        tableBody.append(row);
                    });

                    // Update pagination
                    updatePagination(response.pagination);
                } else {
                    tableBody.append('<tr><td colspan="5" class="text-center">No customers found</td></tr>');
                    // Clear pagination if no results
                    $('.pagination').empty();
                }
            },
            error: function(xhr) {
                console.error('Error searching customers:', xhr);
            }
        });
    }
});
</script>
@endpush
@endsection
