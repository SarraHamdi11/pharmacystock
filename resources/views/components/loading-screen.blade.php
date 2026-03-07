@if(session()->has('show_loading'))
<div id="loadingScreen" class="fixed inset-0 bg-gradient-to-br from-teal-50 to-blue-50 z-50 flex items-center justify-center">
    <div class="text-center">
        <!-- Logo/Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 bg-teal-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                <i class="fas fa-pills text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pharmacy Stock</h1>
            <p class="text-gray-600">Management System</p>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-64 mx-auto mb-4">
            <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                <div id="progressBar" class="bg-gradient-to-r from-teal-500 to-cyan-500 h-3 rounded-full transition-all duration-100 ease-out" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Loading Text -->
        <div class="text-gray-600">
            <p id="loadingText" class="text-sm">Initializing system...</p>
        </div>
        
        <!-- Loading Dots -->
        <div class="flex justify-center mt-4 space-x-2">
            <div class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
            <div class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingScreen = document.getElementById('loadingScreen');
    const progressBar = document.getElementById('progressBar');
    const loadingText = document.getElementById('loadingText');
    
    const loadingSteps = [
        { text: 'Initializing system...', progress: 20 },
        { text: 'Loading inventory data...', progress: 40 },
        { text: 'Checking stock levels...', progress: 60 },
        { text: 'Preparing dashboard...', progress: 80 },
        { text: 'Almost ready...', progress: 100 }
    ];
    
    let currentStep = 0;
    
    function updateLoading() {
        if (currentStep < loadingSteps.length) {
            const step = loadingSteps[currentStep];
            progressBar.style.width = step.progress + '%';
            loadingText.textContent = step.text;
            currentStep++;
            
            setTimeout(updateLoading, 1000);
        } else {
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                loadingScreen.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 500);
        }
    }
    
    updateLoading();
});
</script>
@endif
