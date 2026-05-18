@props(['tag', 'riskLevel'])

<div class="bg-white/80 backdrop-blur-sm rounded-t-2xl shadow-lg border-b border-gray-200/50 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Chat with Our Support Team
            </h1>
            <p class="text-gray-600 mt-1 text-sm sm:text-base">You're not alone. We're here to help.</p>
        </div>
        <button
            type="button"
            onclick="window.chatUtils.copyToClipboard('{{ $tag }}')"
            class="group flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2.5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
        >
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
            </svg>
            <span class="font-medium">Copy ID: {{ $tag }}</span>
        </button>
    </div>
    
    <div class="flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2 bg-gray-100/80 px-4 py-2 rounded-lg">
            <span class="font-medium text-gray-700">Your Anonymous ID:</span>
            <code class="bg-white px-3 py-1.5 rounded-md font-mono font-bold text-blue-600 border border-blue-200">{{ $tag }}</code>
        </div>
        <div class="flex items-center gap-2">
            <span class="font-medium text-gray-700">Report Status:</span>
            @php
                $statusConfig = match($riskLevel) {
                    'CRITICAL' => ['color' => 'text-red-700', 'bg' => 'bg-red-100', 'border' => 'border-red-300', 'pulse' => true],
                    'HIGH' => ['color' => 'text-orange-700', 'bg' => 'bg-orange-100', 'border' => 'border-orange-300', 'pulse' => true],
                    'MEDIUM' => ['color' => 'text-yellow-700', 'bg' => 'bg-yellow-100', 'border' => 'border-yellow-300', 'pulse' => false],
                    'LOW' => ['color' => 'text-green-700', 'bg' => 'bg-green-100', 'border' => 'border-green-300', 'pulse' => false],
                    default => ['color' => 'text-gray-700', 'bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'pulse' => false],
                };
            @endphp
            <span class="px-3 py-1.5 rounded-lg font-semibold {{ $statusConfig['color'] }} {{ $statusConfig['bg'] }} border {{ $statusConfig['border'] }} {{ $statusConfig['pulse'] ? 'animate-pulse' : '' }}">
                {{ $riskLevel }}
            </span>
        </div>
    </div>
</div>
