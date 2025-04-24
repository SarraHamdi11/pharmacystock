<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="form-select" id="supplier_id" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="picture" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#createProductForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createProductModal').modal('hide');
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

    $('#createProductModal').on('hidden.bs.modal', function() {
        let form = $('#createProductForm');
        form.trigger('reset');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').empty();
    });
});
</script>
@endpush