<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteProductForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="deleteProductId" name="product_id">
                <div class="modal-body">
                    <p>Are you sure you want to delete the product: <strong><span id="productName"></span></strong>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#deleteProductForm').on('submit', function(e) {
        e.preventDefault();
        let productId = $('#deleteProductId').val();

        $.ajax({
            url: `/products/${productId}`,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#deleteProductModal').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                console.error('Error deleting product:', xhr);
            }
        });
    });
});
</script>
@endpush