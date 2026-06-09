<!-- Sidebar Navigation -->
<nav class="flex-1 px-4 space-y-1 overflow-y-auto py-4">
    @foreach([
        ['route' => 'dashboard.index', 'match' => 'dashboard.*', 'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard', 'can' => null],
        ['route' => 'products.index', 'match' => 'products.*', 'icon' => 'fa-pills', 'label' => 'Medications', 'can' => 'manage products'],
        ['route' => 'customers.index', 'match' => 'customers.*', 'icon' => 'fa-users', 'label' => 'Patients', 'can' => 'manage patients'],
        ['route' => 'orders.index', 'match' => 'orders.*', 'icon' => 'fa-file-invoice', 'label' => 'Orders', 'can' => 'manage orders'],
        ['route' => 'suppliers.index', 'match' => 'suppliers.*', 'icon' => 'fa-truck-field', 'label' => 'Suppliers', 'can' => 'manage products'],
        ['route' => 'categories.index', 'match' => 'categories.*', 'icon' => 'fa-tags', 'label' => 'Categories', 'can' => 'manage products'],
        ['route' => 'reports.index', 'match' => 'reports.*', 'icon' => 'fa-chart-pie', 'label' => 'Analytics', 'can' => 'manage reports'],
        ['route' => 'activities.index', 'match' => 'activities.*', 'icon' => 'fa-history', 'label' => 'Audit Trail', 'can' => 'manage reports'],
        ['route' => 'stores.index', 'match' => 'stores.*', 'icon' => 'fa-shop', 'label' => 'Stores', 'can' => 'manage products'],
        ['route' => 'stocks.index', 'match' => 'stocks.*', 'icon' => 'fa-boxes-stacked', 'label' => 'Inventory', 'can' => 'manage products'],
    ] as $item)
        @if(!$item['can'] || auth()->user()->can($item['can']))
            <a href="{{ route($item['route']) }}"
               @click="if(window.innerWidth < 1024) sidebarOpen = false"
               aria-label="{{ __($item['label']) }}"
               class="{{ request()->routeIs($item['match']) ? 'nav-item-active' : 'nav-item' }} group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                <i class="fas {{ $item['icon'] }} w-5 text-center mr-3 transition-transform group-hover:scale-110" aria-hidden="true"></i>
                <span>{{ __($item['label']) }}</span>
            </a>
        @endif
    @endforeach
</nav>
