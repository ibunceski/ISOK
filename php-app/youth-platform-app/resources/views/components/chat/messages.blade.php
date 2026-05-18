@props(['messages', 'emptyMessage' => 'No messages yet. Our trained support team will respond to your report soon.'])

<div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth">
    @forelse($messages as $message)
        <div class="flex {{ $message->sender_type === 'admin' ? 'justify-start' : 'justify-end' }} animate-fade-in">
            <div class="max-w-xs sm:max-w-sm lg:max-w-md {{ $message->sender_type === 'admin' ? 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 border border-gray-200' : 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md' }} rounded-2xl p-4 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-2 mb-2">
                    @if($message->sender_type === 'admin')
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-purple-600">Support Team</span>
                    @else
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-white/90">You</span>
                    @endif
                    <span class="text-xs {{ $message->sender_type === 'admin' ? 'text-gray-500' : 'text-blue-100' }}">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                </div>
                <p class="text-sm leading-relaxed">{{ $message->content }}</p>
            </div>
        </div>
    @empty
        <div class="flex items-center justify-center h-full">
            <div class="text-center max-w-md px-4">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-gray-700 font-semibold text-lg mb-2">No messages yet</h3>
                <p class="text-gray-500 text-sm">{{ $emptyMessage }}</p>
            </div>
        </div>
    @endforelse
    
    <!-- Loading indicator (hidden by default) -->
    <div id="loading-indicator" class="hidden flex justify-start animate-fade-in">
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600">Support Team</span>
            </div>
            <div class="flex gap-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Typing indicator (hidden by default) -->
<div id="typing-indicator" class="hidden px-6 pb-2">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <div class="flex gap-1">
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse"></div>
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 200ms;"></div>
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 400ms;"></div>
        </div>
        <span>Support team is typing...</span>
    </div>
</div>
