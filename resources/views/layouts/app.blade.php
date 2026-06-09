<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PharmaStock - Professional Pharmacy Management System">
    
    <title>{{ config('app.name', 'PharmaStock') }} - Pharmacy Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('build/assets/app-Bnn96T6-.css') }}">
    @vite(['resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        /* Mobile Table Scrolling Hint */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        @media (max-width: 640px) {
            .table-modern th, .table-modern td {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .card-glass {
                border-radius: 0.75rem !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-sans antialiased transition-colors duration-300" style="background-color: #f8fafc !important;">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:p-4 focus:bg-brand-600 focus:text-white">
        Skip to main content
    </a>
    @include('components.flash-toast')
    @include('components.loading-screen')

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            id="sidebar"
            aria-label="Main Navigation"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 ease-in-out transform bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 lg:relative lg:translate-x-0"
        >
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between px-6 py-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center shadow-lg shadow-brand-600/30" aria-hidden="true">
                            <i class="fas fa-pills text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">PharmaStock</div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-brand-600 dark:text-brand-400">Pro Management</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-500 hover:text-slate-900 dark:hover:text-white" aria-label="Close Sidebar">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Navigation -->
                @include('layouts.sidebar')

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    <button 
                        @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="flex items-center w-full gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"
                        aria-label="Toggle Dark Mode"
                    >
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'" aria-hidden="true"></i>
                        <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>
                    
                    <div class="mt-4 flex items-center gap-3 px-4">
                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0d9488&color=fff" alt="User Avatar">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'Manager' }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@pharma.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <header class="z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-900 dark:hover:text-white" aria-label="Open Sidebar" aria-controls="sidebar">
                            <i class="fas fa-bars text-lg" aria-hidden="true"></i>
                        </button>
                        
                        <!-- Global Search -->
                        <div class="relative hidden sm:block w-96">
                            <label for="global-search" class="sr-only">Search everything</label>
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                            <input type="text" id="global-search" placeholder="Search everything..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-brand-500/20 transition-all outline-none text-slate-600 dark:text-slate-300">
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" aria-label="View Notifications">
                            <i class="far fa-bell text-lg" aria-hidden="true"></i>
                        </button>
                        <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-800 mx-2" aria-hidden="true"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main id="main-content" class="flex-1 overflow-y-auto p-4 sm:p-8" tabindex="-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
