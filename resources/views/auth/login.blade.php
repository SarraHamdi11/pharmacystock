@extends('layouts.guest')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="flex justify-center">
        <div class="w-16 h-16 rounded-2xl bg-brand-600 flex items-center justify-center shadow-xl shadow-brand-600/30">
            <i class="fas fa-pills text-white text-3xl"></i>
        </div>
    </div>
    <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 dark:text-white">
        Welcome to PharmaStock
    </h2>
    <p class="mt-2 text-center text-sm text-slate-600 dark:text-slate-400">
        Professional Pharmacy Management System
    </p>
</div>

<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md" x-data="{ 
    loading: false,
    email: '',
    password: '',
    roles: [
        { name: 'Admin', email: 'admin@pharma.com' },
        { name: 'Pharmacist', email: 'pharmacist@pharma.com' },
        { name: 'Manager', email: 'manager@pharma.com' },
        { name: 'Employee', email: 'employee@pharma.com' }
    ],
    fillDemo(email) {
        this.email = email;
        this.password = 'password';
    }
}">
    <div class="card-glass p-8 sm:rounded-3xl">
        <div class="mb-8 p-4 bg-brand-50 border border-brand-100 rounded-2xl">
            <div class="flex items-center gap-3 mb-4">
                <i class="fas fa-id-badge text-brand-600"></i>
                <span class="text-sm font-bold text-brand-900">Select a Demo Profile</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="role in roles" :key="role.email">
                    <button @click="fillDemo(role.email)" 
                            type="button"
                            class="flex flex-col items-start p-2.5 rounded-xl border border-brand-100 bg-white hover:bg-brand-100 hover:border-brand-200 transition-all text-left group">
                        <span class="text-[10px] uppercase tracking-wider font-bold text-brand-500 group-hover:text-brand-600" x-text="role.name"></span>
                        <span class="text-[11px] text-slate-500 truncate w-full" x-text="role.email"></span>
                    </button>
                </template>
            </div>
            <div class="mt-3 pt-3 border-t border-brand-100/50 flex justify-between items-center">
                <span class="text-[10px] text-brand-600 font-medium">Default password: <code class="font-bold">password</code></span>
                <button @click="fillDemo('admin@pharma.com')" class="text-[10px] font-bold text-brand-700 hover:underline">Reset to Admin</button>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6" @submit="loading = true">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Email address
                </label>
                <div class="mt-1 relative">
                    <i class="far fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="email" type="email" name="email" x-model="email" required autocomplete="email" autofocus
                           class="input-modern pl-10 @error('email') border-rose-500 @enderror">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Password
                </label>
                <div class="mt-1 relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="password" type="password" name="password" x-model="password" required autocomplete="current-password"
                           class="input-modern pl-10 @error('password') border-rose-500 @enderror">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded-md">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-bold text-brand-600 hover:text-brand-500">
                            Forgot password?
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <button type="submit" 
                        :disabled="loading"
                        class="w-full btn-primary py-3 text-base shadow-xl shadow-brand-600/20 flex items-center justify-center gap-2">
                    <template x-if="loading">
                        <i class="fas fa-circle-notch animate-spin"></i>
                    </template>
                    <span x-text="loading ? 'Signing in...' : 'Sign in to Dashboard'"></span>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-500">Contact Admin</a>
            </p>
        </div>
    </div>
    
    <div class="mt-8 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} PharmaStock Pro. All rights reserved.
    </div>
</div>
@endsection
