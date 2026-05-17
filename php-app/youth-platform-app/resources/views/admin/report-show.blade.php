@extends('layouts.app')

@section('title', 'Report #' . $report->id . ' - Youth Safety Platform')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Report Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white">Report #{{ $report->id }}</h1>
                <span class="text-blue-100">{{ $report->created_at->format('F d, Y \a\t H:i') }}</span>
            </div>
        </div>

        <div class="p-6">
            <!-- Risk Level Badge -->
            <div class="mb-6">
                @php
                    $riskConfig = match($report->risk_level) {
                        'CRITICAL' => ['color' => 'red', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                        'HIGH' => ['color' => 'orange', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        'MEDIUM' => ['color' => 'yellow', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'LOW' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        default => ['color' => 'gray', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    };
                @endphp
                <div class="inline-flex items-center px-4 py-2 bg-{{ $riskConfig['color'] }}-100 text-{{ $riskConfig['color'] }}-800 rounded-full font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $riskConfig['icon'] }}"/>
                    </svg>
                    {{ $report->risk_level }} RISK
                </div>
                @if($report->is_priority)
                    <span class="inline-flex items-center ml-3 px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Priority
                    </span>
                @endif
            </div>

            <!-- Report Content -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Report Content</h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $report->content }}</p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Category</h3>
                    <p class="text-gray-900 font-medium">{{ $report->category ?? 'Not categorized' }}</p>
                </div>

                <!-- Urgency Score -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Urgency Score</h3>
                    @if($report->urgency_score !== null)
                        <div class="flex items-center">
                            <div class="flex-1 bg-gray-200 rounded-full h-3 mr-3">
                                <div 
                                    class="h-3 rounded-full {{ $report->urgency_score >= 0.7 ? 'bg-red-500' : ($report->urgency_score >= 0.4 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                    style="width: {{ min(100, $report->urgency_score * 100) }}%"
                                ></div>
                            </div>
                            <span class="text-gray-900 font-semibold">{{ number_format($report->urgency_score * 100, 1) }}%</span>
                        </div>
                    @else
                        <p class="text-gray-400">Not assessed</p>
                    @endif
                </div>

                <!-- IP Address -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">IP Address</h3>
                    <p class="text-gray-900 font-mono text-sm">{{ $report->ip_address ?? 'Not recorded' }}</p>
                </div>

                <!-- Submitted At -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Submitted</h3>
                    <p class="text-gray-900">{{ $report->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Created</p>
                    <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Status</p>
                    <p class="text-xs text-gray-500">{{ $report->is_priority ? 'Priority Review' : 'Standard Review' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
