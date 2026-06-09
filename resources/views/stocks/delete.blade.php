@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="card-glass p-8 text-center">
        <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-6 text-3xl">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Confirm Removal</h1>
        <p class="text-slate-500 mb-8">Are you sure you want to remove the stock entry for <strong>{{ $stock->product->name }}</strong> in <strong>{{ $stock->store->name }}</strong>? This action cannot be undone.</p>
        
        <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" class="flex items-center justify-center gap-4">
            @csrf
            @method('DELETE')
            <a href="{{ route('stocks.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-rose-600/20">
                Delete Record
            </button>
        </form>
    </div>
</div>
@endsection
