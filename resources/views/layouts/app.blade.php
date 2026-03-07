<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Pharmacy Management</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal': {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                        },
                        'gold': {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-inter">
    <div id="app" class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg transition-all duration-300 fixed lg:relative h-full z-30 transform -translate-x-full lg:translate-x-0 {{ request()->cookie('sidebar_collapsed') ? 'lg:-ml-64' : '' }}">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pills text-white text-xl"></i>
                        </div>
                        <h1 class="text-xl font-bold text-gray-800">PharmaStock Pro</h1>
                    </div>
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('products.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-pills w-5"></i>
                            <span>Medications</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('customers.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Patients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('orders.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-prescription w-5"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-truck w-5"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('categories.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-tags w-5"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stores.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('stores.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-store w-5"></i>
                            <span>Stores</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors {{ request()->routeIs('reports.*') ? 'bg-teal-50 text-teal-600' : '' }}">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b px-6 py-4 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Global Search -->
                        <div class="relative hidden md:block">
                            <input type="text" 
                                   id="globalSearch" 
                                   placeholder="Search medications, patients, orders..." 
                                   class="w-64 lg:w-96 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- Quick Add -->
                        <button onclick="showQuickAddModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span class="hidden sm:inline">Quick Add</span>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors">
                                <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-teal-600"></i>
                                </div>
                                <span class="hidden sm:inline">Pharmacist</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2 z-50">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-2">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-50 text-red-600 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Quick Add Modal -->
    <div id="quickAddModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Quick Add</h3>
                <button onclick="hideQuickAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('products.create') }}" class="block p-3 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-pills text-teal-600"></i>
                        <span class="font-medium">Add New Medication</span>
                    </div>
                </a>
                <a href="{{ route('customers.create') }}" class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user-plus text-blue-600"></i>
                        <span class="font-medium">Add New Patient</span>
                    </div>
                </a>
                <a href="{{ route('orders.create') }}" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-prescription text-green-600"></i>
                        <span class="font-medium">Create Order</span>
                    </div>
                </a>
                <a href="{{ route('suppliers.create') }}" class="block p-3 bg-gold-50 rounded-lg hover:bg-gold-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-gold-600"></i>
                        <span class="font-medium">Add Supplier</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar visibility
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            
            // Toggle overlay on mobile
            if (window.innerWidth < 1024) {
                overlay.classList.toggle('hidden');
            }
            
            // Save state to cookie
            const isCollapsed = sidebar.classList.contains('-translate-x-full');
            document.cookie = `sidebar_collapsed=${isCollapsed}; path=/; max-age=86400`;
        }
        
        function closeSidebarOnResize() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 1024) {
                // On desktop, always show sidebar and hide overlay
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.add('hidden');
            } else {
                // On mobile, hide sidebar by default
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', closeSidebarOnResize);
        
        // Initialize on load
        document.addEventListener('DOMContentLoaded', closeSidebarOnResize);
        
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
            
            // Close menu when clicking outside
            if (!menu.classList.contains('hidden')) {
                setTimeout(() => {
                    document.addEventListener('click', closeUserMenu);
                }, 100);
            }
        }
        
        function closeUserMenu(e) {
            const menu = document.getElementById('userMenu');
            if (!menu.contains(e.target) && !e.target.closest('button[onclick="toggleUserMenu()"]')) {
                menu.classList.add('hidden');
                document.removeEventListener('click', closeUserMenu);
            }
        }
        
        function showQuickAddModal() {
            document.getElementById('quickAddModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function hideQuickAddModal() {
            document.getElementById('quickAddModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Global search functionality
        document.getElementById('globalSearch')?.addEventListener('input', async (e) => {
            const query = e.target.value;
            if (query.length > 2) {
                // Implement search functionality
                console.log('Searching for:', query);
            }
        });
        
        // Close modals on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                hideQuickAddModal();
                document.getElementById('userMenu').classList.add('hidden');
            }
        });
    </script>
    
    <!-- Stack Scripts -->
    @stack('scripts')
</body>
</html>
