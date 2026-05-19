@extends('layouts.app')

@section('title', 'Submit a Report - Youth Safety Platform')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-12 mb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Submit a Safety Report</h1>
            <p class="text-xl text-blue-100">Your voice matters. Help us keep youth safe by reporting concerns anonymously.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Anonymous</h3>
                <p class="text-gray-600 text-sm">Your identity is protected. No personal information is required.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Fast Response</h3>
                <p class="text-gray-600 text-sm">Reports are analyzed immediately and prioritized by urgency.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure</h3>
                <p class="text-gray-600 text-sm">All reports are encrypted and handled with strict confidentiality.</p>
            </div>
        </div>

        <!-- Report Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Report Details</h2>
                <p class="text-sm text-gray-600 mt-1">Please provide as much detail as possible about your concern.</p>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800 mb-2">Please correct the following errors:</h4>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('reports.store') }}" class="space-y-6">
                    @csrf

                    <!-- Content Field -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            What would you like to report? <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            rows="8"
                            required
                            maxlength="5000"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('content') border-red-300 @enderror"
                            placeholder="Describe the situation in detail. Include any relevant information such as locations, people involved, dates, times, and specific concerns..."
                        >{{ old('content') }}</textarea>
                        <div class="mt-2 flex justify-between items-center">
                            <p class="text-xs text-gray-500">Be as specific as possible while avoiding sharing personal identifying information.</p>
                            <span id="charCount" class="text-xs text-gray-500"><span id="currentChars">0</span>/5000</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Your submission is anonymous and secure
                        </p>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Come Back to Your Chat -->
        <div class="mt-8 bg-white rounded-xl shadow-lg border border-green-200 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-teal-50 border-b border-green-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Already shared something with us?</h2>
                        <p class="text-sm text-gray-600 mt-0.5">Enter your secret code to go back to your chat and see our reply.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if(session('chat_lookup_error'))
                    <div class="mb-5 bg-orange-50 border border-orange-200 rounded-lg p-4 flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-orange-800">Hmm, we couldn't find that code!</p>
                            <p class="text-sm text-orange-700 mt-1">Please check the letters and numbers and try again. Your secret code looks something like <strong>USER-ABCD1234</strong>.</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('chat.lookup') }}">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                        <div class="flex-1 w-full">
                            <label for="tag" class="block text-sm font-semibold text-gray-700 mb-2">
                                Your secret code
                            </label>
                            <input
                                type="text"
                                id="tag"
                                name="tag"
                                value="{{ old('tag') }}"
                                placeholder="e.g. USER-ABCD1234"
                                class="w-full px-4 py-3 text-lg border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors tracking-widest font-mono"
                                autocomplete="off"
                                maxlength="20"
                                oninput="this.value = this.value.toUpperCase()"
                            >
                            <p class="mt-2 text-xs text-gray-500">This is the code you saved when you first shared your report with us.</p>
                        </div>
                        <button
                            type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-7 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center gap-2 text-base whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Go to my chat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Emergency Notice -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="text-lg font-semibold text-yellow-800 mb-2">Emergency Situation?</h4>
                    <p class="text-yellow-700 text-sm">
                        If you or someone else is in immediate danger, please contact emergency services right away. 
                        This reporting system is not monitored 24/7. Call <strong>911</strong> or your local emergency number for urgent situations.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const textarea = document.getElementById('content');
    const currentChars = document.getElementById('currentChars');
    
    textarea.addEventListener('input', function() {
        currentChars.textContent = this.value.length;
    });
    
    // Initialize counter on page load
    currentChars.textContent = textarea.value.length;
</script>
@endpush
@endsection
