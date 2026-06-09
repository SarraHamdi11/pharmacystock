@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Patient Directory</h1>
            <p class="text-slate-500 dark:text-slate-400">Manage patient profiles, prescriptions, and order history.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.create') }}" class="btn-primary text-sm">
                <i class="fas fa-user-plus mr-2"></i> Register Patient
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card-glass p-4">
        <form action="{{ route('customers.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="term" value="{{ request('term') }}" 
                       placeholder="Search by name, email, or phone..." 
                       class="input-modern pl-10">
            </div>
            <button type="submit" class="btn-primary px-8">Search</button>
            @if(request('term'))
                <a href="{{ route('customers.index') }}" class="btn-secondary px-4 text-rose-600 border-rose-100">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Patients Table -->
    <div class="card-glass p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact Info</th>
                        <th>Address</th>
                        <th>Joined Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center text-brand-600 font-bold">
                                        {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                        <div class="text-xs text-slate-500">ID: #PAT-{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                        <i class="far fa-envelope text-slate-400 w-4"></i>
                                        {{ $customer->email }}
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                        <i class="fas fa-phone-alt text-slate-400 w-4"></i>
                                        {{ $customer->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate">
                                    {{ $customer->address ?? 'No address provided' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $customer->created_at->format('M j, Y') }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('orders.index', ['customer_id' => $customer->id]) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all" title="View Orders">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all" title="Edit Profile">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @role('Admin')
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline" onsubmit="return confirm('Archive this patient record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-user-friends text-2xl"></i>
                                    </div>
                                    <div class="text-slate-500 font-medium">No patients found.</div>
                                    <a href="{{ route('customers.create') }}" class="text-brand-600 font-bold hover:underline">Register your first patient</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
