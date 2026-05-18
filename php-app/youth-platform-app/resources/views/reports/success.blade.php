@extends('layouts.app')

@section('title', 'Report Submitted - Youth Safety Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center p-4 py-12">
    <div class="w-full max-w-2xl">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-12 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Report Submitted Successfully</h1>
                <p class="text-green-100 text-lg">Thank you for helping keep youth safe</p>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <!-- Report Details -->
                <div class="mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"/>
                        </svg>
                        Your Report Information
                    </h2>

                    @if(session('anonymous_tag'))
                        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border-2 border-blue-300">
                            <p class="text-gray-700 text-sm mb-2 font-medium">Your Anonymous ID (Save this to access your chat later):</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-700 tracking-wider font-mono">{{ session('anonymous_tag') }}</span>
                                <button
                                    type="button"
                                    onclick="copyToClipboard('{{ session('anonymous_tag') }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                >
                                    📋 Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        @if(session('risk_level'))
                            <div>
                                <span class="text-gray-600 text-sm font-medium">Risk Level:</span>
                                @php
                                    $riskColor = match(session('risk_level')) {
                                        'CRITICAL' => 'bg-red-100 text-red-800',
                                        'HIGH' => 'bg-orange-100 text-orange-800',
                                        'MEDIUM' => 'bg-yellow-100 text-yellow-800',
                                        'LOW' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $riskColor }}">
                                        {{ session('risk_level') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if(session('category'))
                            <div>
                                <span class="text-gray-600 text-sm font-medium">Category:</span>
                                <p class="mt-1 text-gray-900 font-medium capitalize">{{ str_replace('_', ' ', session('category')) }}</p>
                            </div>
                        @endif

                        @if(session('urgency_score'))
                            <div>
                                <span class="text-gray-600 text-sm font-medium">Urgency Score:</span>
                                <div class="mt-1 flex items-center">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                        <div
                                            class="h-2 rounded-full {{ session('urgency_score') >= 0.7 ? 'bg-red-500' : (session('urgency_score') >= 0.4 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                            style="width: {{ min(100, session('urgency_score') * 100) }}%"
                                        ></div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ number_format(session('urgency_score') * 100, 0) }}%</span>
                                </div>
                            </div>
                        @endif

                        @if(session('is_priority'))
                            <div>
                                <span class="text-gray-600 text-sm font-medium">Status:</span>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Priority Review
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alert for Urgent Reports -->
                @if(session('is_priority') || session('risk_level') === 'CRITICAL')
                    <div class="mb-8 bg-red-50 border-2 border-red-300 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-red-800 mb-2">⚠️ URGENT - Emergency Support Available</h3>
                                <p class="text-red-700 mb-4">
                                    Your report has been flagged as urgent. Our team will review this with high priority. However, response times may vary.
                                </p>
                                <div class="bg-white rounded-lg p-4 border border-red-300">
                                    <p class="text-red-800 font-semibold mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502 0l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                                        </svg>
                                        If you or someone is in immediate danger:
                                    </p>
                                    <div class="text-center py-3">
                                        <a href="tel:112" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg text-xl transition-colors">
                                            📞 Call 112 Immediately
                                        </a>
                                    </div>
                                    <p class="text-sm text-red-600 text-center mt-2">Available 24/7 for life-threatening emergencies</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- What Happens Next -->
                <div class="mb-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        What Happens Next
                    </h3>
                    <ol class="space-y-3">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-semibold mr-3 flex-shrink-0">1</span>
                            <span class="text-gray-700"><strong>Report Review:</strong> Our team will review your report and assess the urgency level.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-semibold mr-3 flex-shrink-0">2</span>
                            <span class="text-gray-700"><strong>Analysis:</strong> We'll analyze your situation and determine the appropriate response.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-semibold mr-3 flex-shrink-0">3</span>
                            <span class="text-gray-700"><strong>Chat Support:</strong> An admin may reach out to you via chat to provide support, ask clarifying questions, or offer guidance.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-semibold mr-3 flex-shrink-0">4</span>
                            <span class="text-gray-700"><strong>Ongoing Support:</strong> You can chat with our team anytime using your Anonymous ID to get the help you need.</span>
                        </li>
                    </ol>
                </div>

                <!-- Privacy Notice -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-700">
                                <strong>Your Privacy:</strong> Your report is completely anonymous. Your identity is protected and will never be shared. All communications are encrypted and confidential.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                @if(session('anonymous_tag'))
                    <a
                        href="{{ route('chat.view', ['tag' => session('anonymous_tag')]) }}"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        💬 Access Chat Now
                    </a>
                @endif
                <a
                    href="{{ route('reports.create') }}"
                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Submit Another Report
                </a>
                <a
                    href="{{ route('reports.create') }}"
                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 010-18 9 9 0 010 18zM3 12h18"/>
                    </svg>
                    Return Home
                </a>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center text-gray-600 text-sm">
            <p>Need immediate help? Call <strong class="text-red-600">112</strong></p>
            <p class="mt-2 text-xs text-gray-500">This platform is monitored 24/7 by trained professionals dedicated to your safety.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Anonymous ID copied! Save it safely to access your chat later.');
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
</script>
@endpush
@endsection

