@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">System Audit Trail</h1>
            <p class="text-slate-500 dark:text-slate-400">Track all system activities and user actions for compliance.</p>
        </div>
    </div>

    <div class="card-glass p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Entity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td class="whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $activity->created_at->format('M j, Y') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $activity->created_at->format('H:i:s') }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ strtoupper(substr($activity->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">{{ $activity->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                    {{ $activity->action === 'created' ? 'bg-emerald-50 text-emerald-600' : 
                                       ($activity->action === 'updated' ? 'bg-amber-50 text-amber-600' : 
                                       ($activity->action === 'deleted' ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600')) }}">
                                    {{ $activity->action }}
                                </span>
                            </td>
                            <td>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $activity->description }}</p>
                            </td>
                            <td>
                                @if($activity->model_type)
                                    <div class="text-[10px] font-mono text-slate-400">
                                        {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                    </div>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No activity recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
