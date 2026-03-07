{{-- Master Form Template - Use this for all forms --}}
@php
    $formTitle = $formTitle ?? 'Form';
    $formDescription = $formDescription ?? 'Fill in the form below';
    $formIcon = $formIcon ?? 'fas fa-edit';
    $submitText = $submitText ?? 'Save';
    $submitIcon = $submitIcon ?? 'fas fa-save';
    $cancelRoute = $cancelRoute ?? 'dashboard.index';
    $cancelText = $cancelText ?? 'Cancel';
    $cancelIcon = $cancelIcon ?? 'fas fa-times';
@endphp

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-teal-500">
            <div class="flex items-center gap-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="{{ $formIcon }} text-teal-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $formTitle }}</h1>
                    <p class="text-gray-600 mt-1">{{ $formDescription }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>
                    Information
                </h2>
            </div>
            
            <form action="{{ $formAction ?? '#' }}" method="{{ $formMethod ?? 'POST' }}" class="p-6">
                @csrf
                @if(isset($formMethod) && $formMethod === 'PUT')
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Form fields will be injected here --}}
                    {{ $slot ?? '' }}
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 border-t pt-6">
                    <a href="{{ route($cancelRoute) }}" 
                       class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="{{ $cancelIcon }}"></i>
                        {{ $cancelText }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="{{ $submitIcon }}"></i>
                        {{ $submitText }}
                    </button>
                </div>
            </form>
        </div>

        @if(isset($helpTitle) && isset($helpContent))
        <!-- Help Card -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold mb-2">{{ $helpTitle }}</h3>
                    <div class="text-blue-700 text-sm">
                        {!! $helpContent !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-format phone numbers
        $('input[type="tel"]').on('input', function(e) {
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
    });
</script>
@endpush
@endsection
