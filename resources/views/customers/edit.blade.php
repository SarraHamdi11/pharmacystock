@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-teal-500">
            <div class="flex items-center gap-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="fas fa-user-edit text-teal-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Patient</h1>
                    <p class="text-gray-600 mt-1">Update patient information</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>
                    Patient Information
                </h2>
            </div>
            
            <form action="{{ route('customers.update', $customer) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="space-y-2">
                        <label for="first_name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-teal-600"></i>
                            First Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Enter first name">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('first_name')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-2">
                        <label for="last_name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-teal-600"></i>
                            Last Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Enter last name">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('last_name')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-envelope mr-2 text-teal-600"></i>
                            Email Address <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="patient@example.com">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <label for="phone" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-phone mr-2 text-teal-600"></i>
                            Phone Number <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required
                                   class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="+1 (555) 123-4567">
                            <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="address" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-home mr-2 text-teal-600"></i>
                            Address <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <textarea name="address" id="address" rows="3" required
                                      class="w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                      placeholder="Enter complete address including street, city, and postal code">{{ old('address', $customer->address) }}</textarea>
                            <i class="fas fa-home absolute left-3 top-4 text-gray-400"></i>
                        </div>
                        @error('address')
                            <p class="text-red-500 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 border-t pt-6">
                    <a href="{{ route('customers.index') }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Update Patient
                    </button>
                </div>
            </form>
        </div>

        <!-- Patient Info Card -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold mb-2">Current Patient Information</h3>
                    <div class="text-blue-700 text-sm space-y-1">
                        <p><strong>Patient ID:</strong> #{{ $customer->id }}</p>
                        <p><strong>Registered:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                        <p><strong>Last Updated:</strong> {{ $customer->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-format phone number
        $('#phone').on('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            var formattedValue = '';
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    formattedValue = value;
                } else if (value.length <= 6) {
                    formattedValue = '(' + value.slice(0, 3) + ') ' + value.slice(3);
                } else {
                    formattedValue = '(' + value.slice(0, 3) + ') ' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            
            e.target.value = formattedValue;
        });

        // Form validation feedback
        $('form').on('submit', function(e) {
            var isValid = true;
            
            // Check required fields
            $('input[required], textarea[required]').each(function() {
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
        $('input, textarea').on('focus', function() {
            $(this).removeClass('border-red-500');
        });
    });
</script>
@endpush
@endsection
