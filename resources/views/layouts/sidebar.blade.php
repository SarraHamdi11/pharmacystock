<div class="sidebar bg-dark text-white p-3" style="min-width: 250px;">
    <h5 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>{{ __('Management') }}</span>
    </h5>
    <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> {{ __('Dashboard') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('products.index') }}">
                <i class="bi bi-box"></i> {{ __('Products') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('categories.index') }}">
                <i class="bi bi-tags"></i> {{ __('Categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('suppliers.index') }}">
                <i class="bi bi-truck"></i> {{ __('Suppliers') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('customers.index') }}">
                <i class="bi bi-people"></i> {{ __('Customers') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('orders.index') }}">
                <i class="bi bi-cart"></i> {{ __('Orders') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('stores.index') }}">
                <i class="bi bi-shop"></i> {{ __('Stores') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('stocks.index') }}">
                <i class="bi bi-box-seam"></i> {{ __('Stocks') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('maladies.index') }}">
                <i class="bi bi-heart-pulse"></i> {{ __('Maladies') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('medicaments.index') }}">
                <i class="bi bi-capsule"></i> {{ __('Medicaments') }}
            </a>
        </li>
    </ul>
</div>