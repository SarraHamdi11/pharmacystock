@if(session('success') || session('error') || session('warning'))
<div id="flashToast" class="fixed top-20 right-4 z-[100] max-w-sm animate-slide-in">
    <div class="rounded-xl shadow-2xl p-4 flex items-start gap-3
        {{ session('error') ? 'bg-red-50 border border-red-200 text-red-800' : (session('warning') ? 'bg-amber-50 border border-amber-200 text-amber-800' : 'bg-green-50 border border-green-200 text-green-800') }}">
        <i class="fas {{ session('error') ? 'fa-times-circle text-red-500' : (session('warning') ? 'fa-exclamation-circle text-amber-500' : 'fa-check-circle text-green-500') }} text-xl mt-0.5"></i>
        <div class="flex-1">
            <p class="font-semibold text-sm">{{ session('success') ?? session('error') ?? session('warning') }}</p>
        </div>
        <button onclick="document.getElementById('flashToast').remove()" class="opacity-60 hover:opacity-100">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<script>setTimeout(() => document.getElementById('flashToast')?.remove(), 5000);</script>
@endif
