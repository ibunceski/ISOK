@extends('layouts.app')

@section('title', 'Chat Support - Youth Safety Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Chat Header Component -->
        <x-chat.header :tag="$tag" :riskLevel="$report->risk_level" />

        <!-- Chat Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-b-2xl shadow-lg overflow-hidden flex flex-col" style="height: 650px;">
            <!-- Messages Component -->
            <x-chat.messages :messages="$messages" />

            <!-- Input Component -->
            <x-chat.input :tag="$tag" />
        </div>

        <!-- Info Box Component -->
        <x-chat.info-box :tag="$tag" />

        <!-- Navigation -->
        <div class="mt-6 flex gap-3 flex-wrap">
            <a
                href="{{ route('reports.create') }}"
                class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold rounded-lg transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Submit Another Report
            </a>
            <a
                href="{{ route('reports.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 010-18 9 9 0 010 18zM3 12h18"/>
                </svg>
                Home
            </a>
        </div>
    </div>
</div>

@push('scripts')
    <x-chat.scripts />
    <script>
        // Initialize chat on page load
        document.addEventListener('DOMContentLoaded', function() {
            const tagElement = document.querySelector('code.font-mono');
            if (tagElement) {
                window.chatUtils.init(tagElement.textContent);
            }
        });

        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            window.chatUtils.stopPolling();
        });
    </script>
@endpush
@endsection
