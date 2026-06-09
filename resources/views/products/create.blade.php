@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-teal-500">
            <div class="flex items-center gap-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="fas fa-pills text-teal-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Medication</h1>
                    <p class="text-gray-600 mt-1">Create a new medication entry in the inventory</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>
                    Medication Information
                </h2>
            </div>
            
            <form action="{{ route('products.store') }}" method="POST" class="p-6" id="productCreateForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="space-y-2">
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-pills mr-2 text-teal-600" aria-hidden="true"></i>
                            Product Name <span class="text-red-500 ml-1" aria-hidden="true">*</span>
                            <span class="sr-only">(required)</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   aria-required="true"
                                   aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                   aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Enter product name">
                            <i class="fas fa-pills absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm flex items-center mt-1" id="name-error" role="alert">
                                <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Generic Name -->
                    <div class="space-y-2">
                        <label for="generic_name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-flask mr-2 text-teal-600" aria-hidden="true"></i>
                            Generic Name
                        </label>
                        <div class="relative">
                            <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name') }}"
                                   aria-invalid="{{ $errors->has('generic_name') ? 'true' : 'false' }}"
                                   aria-describedby="{{ $errors->has('generic_name') ? 'generic-error' : '' }}"
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Enter generic name">
                            <i class="fas fa-flask absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        </div>
                        @error('generic_name')
                            <p class="text-red-500 text-sm flex items-center mt-1" id="generic-error" role="alert">
                                <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="category_id" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-tags mr-2 text-teal-600" aria-hidden="true"></i>
                            Category <span class="text-red-500 ml-1" aria-hidden="true">*</span>
                            <span class="sr-only">(required)</span>
                        </label>
                        <div class="relative">
                            <select name="category_id" id="category_id" required
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('category_id') ? 'true' : 'false' }}"
                                    aria-describedby="{{ $errors->has('category_id') ? 'category-error' : '' }}"
                                    class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                        </div>
                        @error('category_id')
                            <p class="text-red-500 text-sm flex items-center mt-1" id="category-error" role="alert">
                                <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Supplier -->
                    <div class="space-y-2">
                        <label for="supplier_id" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-truck mr-2 text-teal-600"></i>
                            Supplier <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="supplier_id" id="supplier_id" required
                                    class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none">
                                <option value="">Select a supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->first_name }} {{ $supplier->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-truck absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('supplier_id')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Barcode -->
                    <div class="space-y-2">
                        <label for="code_bar" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-barcode mr-2 text-teal-600"></i>
                            Barcode
                        </label>
                        <div class="relative">
                            <input type="text" name="code_bar" id="code_bar" value="{{ old('code_bar') }}"
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Enter barcode">
                            <i class="fas fa-barcode absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('code_bar')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <label for="price" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-dollar-sign mr-2 text-teal-600"></i>
                            Price <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="0.00">
                            <i class="fas fa-dollar-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Minimum Stock -->
                    <div class="space-y-2">
                        <label for="min_stock" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-exclamation-triangle mr-2 text-teal-600"></i>
                            Minimum Stock <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock') }}" min="0" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="0">
                            <i class="fas fa-exclamation-triangle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('min_stock')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div class="space-y-2">
                        <label for="expiry_date" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar mr-2 text-teal-600"></i>
                            Expiry Date
                        </label>
                        <div class="relative">
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}"
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                            <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('expiry_date')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Track Expiry -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-bell mr-2 text-teal-600"></i>
                            Track Expiry
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" name="track_expiry" id="track_expiry" value="1" {{ old('track_expiry') ? 'checked' : '' }}
                                   class="w-5 h-5 text-teal-600 border-2 border-gray-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            <label for="track_expiry" class="ml-3 text-gray-700">Enable expiry tracking alerts</label>
                        </div>
                        @error('track_expiry')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-alt mr-2 text-teal-600"></i>
                            Description
                        </label>
                        <div class="relative">
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                      placeholder="Enter medication description and usage instructions">{{ old('description') }}</textarea>
                            <i class="fas fa-file-alt absolute left-3 top-4 text-gray-400"></i>
                        </div>
                        @error('description')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 border-t pt-6">
                    <a href="{{ route('products.index') }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Medication
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold mb-2">Medication Registration Help</h3>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• All fields marked with <span class="text-red-500">*</span> are required</li>
                        <li>• Barcode helps with inventory tracking and scanning</li>
                        <li>• Minimum stock triggers low inventory alerts</li>
                        <li>• Expiry tracking helps prevent expired medication sales</li>
                        <li>• Description should include dosage and usage instructions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation feedback
        $('form').on('submit', function(e) {
            var isValid = true;
            
            // Check required fields
            $('input[required], textarea[required], select[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).addClass('border-red-500');
                    isValid = false;
                } else {
                    $(this).removeClass('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
        });

        // Remove error styling on input
        $('input, textarea, select').on('focus', function() {
            $(this).removeClass('border-red-500');
        });

        // Debug form submission
        $('#productCreateForm').on('submit', function(e) {
            console.log('Form submitted');
            console.log('Form data:', $(this).serialize());
        });
    });
</script>
@endpush
@endsection
