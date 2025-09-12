@extends('layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">

            <!-- Cancellation Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center fade-in">

                <!-- Icon -->
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-800 mb-3">Order Cancelled</h1>

                <!-- Message -->
                <p class="text-gray-600 mb-8">
                    Your payment was cancelled and no charges were made to your account.
                </p>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="/"
                       class="block w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        Try Again
                    </a>

                    <a href="https://flutemeet.org/" class="block w-full text-gray-600 py-2 hover:text-gray-800 transition-colors">
                        Return To Main Site
                    </a>
                </div>

                <!-- Help -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Need help?
                        <a href="mailto:flutemeet@gmail.com" class="text-indigo-600 hover:underline">
                            Contact Support
                        </a>
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection
