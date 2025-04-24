<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editProductId" name="product_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"
                            required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="editPrice" name="price" step="0.01" min="0"
                            required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editCategoryId" class="form-label">Category <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="editCategoryId" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editSupplierId" class="form-label">Supplier <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="editSupplierId" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->first_name }} {{ $supplier->last_name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editPicture" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="editPicture" name="picture" accept="image/*">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let productId = $('#editProductId').val();
        let formData = new FormData(this);

        $.ajax({
            url: `/products/${productId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editProductModal').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(field) {
                    let input = form.find(`[name=${field}]`);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[field][0]);
                });
            }
        });
    });

    $('#editProductModal').on('hidden.bs.modal', function() {
        let form = $('#editProductForm');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').empty();
    });
});
</script>
@endpush