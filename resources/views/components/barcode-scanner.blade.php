@props(['placeholder' => 'Scan barcode or type product name'])

<div class="barcode-scainer">
    <div class="input-group">
        <span class="input-group-text">
            <i class="fas fa-barcode"></i>
        </span>
        <input 
            type="text" 
            class="form-control" 
            placeholder="{{ $placeholder }}"
            id="barcode-input"
            autocomplete="off"
        >
        <button class="btn btn-outline-primary" type="button" id="scan-btn">
            <i class="fas fa-camera"></i> {{ __('Scan') }}
        </button>
    </div>
    
    <!-- Scanner Modal -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Barcode Scanner') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="scanner-container">
                        <video id="scanner-video" style="width: 100%; max-width: 400px;"></video>
                        <canvas id="scanner-canvas" style="display: none;"></canvas>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success" id="start-scan">
                            <i class="fas fa-play"></i> {{ __('Start Scanning') }}
                        </button>
                        <button class="btn btn-danger" id="stop-scan" style="display: none;">
                            <i class="fas fa-stop"></i> {{ __('Stop Scanning') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeReader = new ZXing.BrowserMultiFormatReader();
    let scanning = false;
    
    const scanBtn = document.getElementById('scan-btn');
    const scannerModal = new bootstrap.Modal(document.getElementById('scannerModal'));
    const startScanBtn = document.getElementById('start-scan');
    const stopScanBtn = document.getElementById('stop-scan');
    const videoElement = document.getElementById('scanner-video');
    
    scanBtn.addEventListener('click', function() {
        scannerModal.show();
    });
    
    startScanBtn.addEventListener('click', async function() {
        try {
            scanning = true;
            startScanBtn.style.display = 'none';
            stopScanBtn.style.display = 'inline-block';
            
            await codeReader.decodeFromVideoDevice(undefined, videoElement, (result, err) => {
                if (result && scanning) {
                    document.getElementById('barcode-input').value = result.text;
                    scannerModal.hide();
                    stopScanning();
                    
                    // Trigger search or form submission
                    const event = new Event('change', { bubbles: true });
                    document.getElementById('barcode-input').dispatchEvent(event);
                }
            });
        } catch (err) {
            console.error('Error starting scanner:', err);
            alert('{{ __("Error accessing camera. Please ensure camera permissions are granted.") }}');
            stopScanning();
        }
    });
    
    stopScanBtn.addEventListener('click', function() {
        stopScanning();
    });
    
    function stopScanning() {
        scanning = false;
        codeReader.reset();
        startScanBtn.style.display = 'inline-block';
        stopScanBtn.style.display = 'none';
    }
    
    // Auto-submit on barcode input (for handheld scanners)
    let barcodeTimer;
    document.getElementById('barcode-input').addEventListener('input', function(e) {
        clearTimeout(barcodeTimer);
        barcodeTimer = setTimeout(() => {
            if (e.target.value.length > 5) {
                // Trigger search
                const event = new Event('change', { bubbles: true });
                e.target.dispatchEvent(event);
            }
        }, 500);
    });
    
    // Clean up on modal hide
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function() {
        stopScanning();
    });
});
</script>
@endpush
