@props(['tag', 'action' => null])

<div class="border-t border-gray-200/50 p-4 bg-white/50 backdrop-blur-sm">
    <form id="chat-form" onsubmit="window.chatUtils.sendMessage(event, '{{ $tag }}')" class="flex gap-3">
        @csrf
        <div class="flex-1 relative">
            <textarea
                id="message-input"
                name="content"
                rows="1"
                class="w-full px-4 py-3 pr-12 border-2 border-gray-300 text-gray-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none bg-white placeholder-gray-400 hover:border-gray-400"
                placeholder="Type your message here..."
                style="max-height: 120px; min-height: 48px;"
            ></textarea>
            <div class="absolute right-3 bottom-3 text-xs text-gray-500">
                <span id="char-count">0</span>/1000
            </div>
        </div>
        <button
            type="submit"
            id="send-button"
            class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 disabled:from-gray-400 disabled:to-gray-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 flex-shrink-0 shadow-md hover:shadow-lg disabled:shadow-none transform hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed"
        >
            <svg class="w-5 h-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <span>Send</span>
        </button>
    </form>
    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        Your messages are completely anonymous and encrypted
    </p>
</div>
