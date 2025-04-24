@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Delete Customer</h2>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Back to Customer List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-danger">
                <h4 class="alert-heading">Confirm Deletion</h4>
                <p>Are you sure you want to delete the following customer?</p>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                    <p class="card-text"><strong>Email:</strong> {{ $customer->email }}</p>
                    <p class="card-text"><strong>Phone:</strong> {{ $customer->phone }}</p>
                    <p class="card-text"><strong>Address:</strong> {{ $customer->address }}</p>
                </div>
            </div>

            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection