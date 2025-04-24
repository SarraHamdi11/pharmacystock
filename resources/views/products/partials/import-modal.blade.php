<!-- Import Products Modal -->
<div class="modal fade" id="importProductModal" tabindex="-1" aria-labelledby="importProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importProductModalLabel">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data"
                id="importProductForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File <span
                                class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-text">Please upload an Excel file with the correct product format.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-file"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    $('#importProductForm').on('submit', function(e) {
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
                $('#importProductModal').modal('hide');
                window.location.reload();
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(field) {
                        let input = form.find(`[name=${field}]`);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(errors[field][0]);
                    });
                } else {
                    alert('An error occurred during import. Please try again.');
                }
            }
        });
    });

    $('#importProductModal').on('hidden.bs.modal', function() {
        let form = $('#importProductForm');
        form.trigger('reset');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').empty();
    });
});
</script>
@endpush