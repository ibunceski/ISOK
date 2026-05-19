@props(['report', 'messages', 'emptyMessage' => 'No messages yet. Start a conversation to provide support.'])

<div class="lg:col-span-2">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden flex flex-col" style="height: 650px;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 border-b border-purple-700/30 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Chat with {{ $report->anonymous_tag ?? 'User' }}
                </h2>
                <span class="text-purple-100 text-sm" id="message-count">{{ count($messages) }} messages</span>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth bg-white/50">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }} animate-fade-in">
                    <div class="max-w-xs sm:max-w-sm lg:max-w-md {{ $message->sender_type === 'admin' ? 'bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-md' : 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 border border-gray-200' }} rounded-2xl p-4 transition-all duration-200 hover:shadow-lg">
                        <div class="flex items-center gap-2 mb-2">
                            @if($message->sender_type === 'admin')
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-purple-100">Support Team</span>
                            @else
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-700">{{ $report->anonymous_tag ?? 'User' }}</span>
                            @endif
                            <span class="text-xs {{ $message->sender_type === 'admin' ? 'text-purple-100' : 'text-gray-500' }}">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                        <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <div class="text-center max-w-md px-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-gray-700 font-semibold text-lg mb-2">No messages yet</h3>
                        <p class="text-gray-500 text-sm">{{ $emptyMessage }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Send Message Form -->
        <div class="border-t border-gray-200/50 p-4 bg-white/50 backdrop-blur-sm">
            <form id="admin-chat-form" class="flex gap-3">
                @csrf
                <div class="flex-1 relative">
                    <textarea
                        id="admin-message-input"
                        name="content"
                        rows="1"
                        class="w-full px-4 py-3 pr-12 border-2 border-gray-300 text-gray-900 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 resize-none bg-white placeholder-gray-400 hover:border-gray-400"
                        placeholder="Type your message to provide help and support..."
                        style="max-height: 120px; min-height: 48px;"
                    ></textarea>
                    <div class="absolute right-3 bottom-3 text-xs text-gray-500">
                        <span id="admin-char-count">0</span>/1000
                    </div>
                </div>
                <button
                    type="submit"
                    id="admin-send-button"
                    class="group bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 disabled:from-gray-400 disabled:to-gray-500 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 flex-shrink-0 shadow-md hover:shadow-lg disabled:shadow-none transform hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed"
                >
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Send</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    let isSending = false;
    let pollInterval = null;
    let lastMessageCount = {{ count($messages) }};
    const reportTag = '{{ $report->anonymous_tag }}';
    const reportId = {{ $report->id }};

    function scrollToBottom() {
        const container = document.getElementById('chat-messages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function createMessageElement(content, isAdmin) {
        const div = document.createElement('div');
        div.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'} animate-fade-in`;
        div.innerHTML = `
            <div class="max-w-xs sm:max-w-sm lg:max-w-md ${isAdmin ? 'bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-md' : 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 border border-gray-200'} rounded-2xl p-4 transition-all duration-200 hover:shadow-lg">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 ${isAdmin ? 'bg-white/20' : 'bg-gray-300'} rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 ${isAdmin ? 'text-white' : 'text-gray-700'}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold ${isAdmin ? 'text-purple-100' : 'text-gray-700'}">
                        ${isAdmin ? 'Support Team' : '${reportTag}'}
                    </span>
                    <span class="text-xs ${isAdmin ? 'text-purple-100' : 'text-gray-500'}">
                        ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                    </span>
                </div>
                <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
            </div>
        `;
        return div;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function removeEmptyState() {
        const messagesContainer = document.getElementById('chat-messages');
        const emptyState = messagesContainer.querySelector('.flex.items-center.justify-center');
        if (emptyState) {
            emptyState.remove();
        }
    }

    async function sendMessage(event) {
        event.preventDefault();
        if (isSending) return;

        const form = document.getElementById('admin-chat-form');
        const input = document.getElementById('admin-message-input');
        const content = input.value.trim();

        if (!content) return;

        isSending = true;
        const submitBtn = document.getElementById('admin-send-button');
        const originalContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Sending...</span>
        `;

        // Optimistically add message to UI
        removeEmptyState();
        const messagesContainer = document.getElementById('chat-messages');
        const userMessage = createMessageElement(content, true);
        messagesContainer.appendChild(userMessage);

        // Clear input immediately for better UX
        input.value = '';
        input.style.height = 'auto';
        document.getElementById('admin-char-count').textContent = '0';

        scrollToBottom();

        try {
            // Use web route instead of API route since admin uses session auth
            const response = await fetch(`/admin/reports/${reportId}/respond`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ content: content })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to send message');
            }

            // Poll immediately to refresh messages
            await pollMessages();
        } catch (error) {
            console.error('Error sending message:', error);

            // Show error indicator on the message
            userMessage.classList.add('opacity-75');
            userMessage.querySelector('p').innerHTML += '<br><span class="text-xs text-red-200 mt-1 block">⚠️ Failed to send. Please try again.</span>';

            alert('Failed to send message. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
            isSending = false;
            input.focus();
        }
    }

    async function pollMessages() {
        try {
            const response = await fetch(`/api/reports/${reportTag}/messages`);
            if (!response.ok) return;

            const data = await response.json();
            const messages = data.data || [];

            if (messages.length > lastMessageCount) {
                const container = document.getElementById('chat-messages');

                // Remove empty state if exists
                const emptyState = container.querySelector('.flex.items-center.justify-center');
                if (emptyState) {
                    emptyState.remove();
                }

                // Add new messages
                for (let i = lastMessageCount; i < messages.length; i++) {
                    const msg = messages[i];
                    const isAdmin = msg.sender_type === 'admin';
                    const messageEl = createMessageElement(msg.content, isAdmin);
                    container.appendChild(messageEl);
                }

                lastMessageCount = messages.length;
                document.getElementById('message-count').textContent = `${lastMessageCount} messages`;
                scrollToBottom();
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    // Auto-expand textarea
    function initTextareaAutoExpand() {
        const textarea = document.getElementById('admin-message-input');
        if (!textarea) return;

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';

            // Update character count
            const charCount = document.getElementById('admin-char-count');
            if (charCount) {
                charCount.textContent = this.value.length;
                if (this.value.length > 900) {
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            }
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
        initTextareaAutoExpand();

        // Attach form submit handler
        const form = document.getElementById('admin-chat-form');
        if (form) {
            form.addEventListener('submit', sendMessage);
        }

        // Start polling every 5 seconds
        pollInterval = setInterval(pollMessages, 5000);
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (pollInterval) {
            clearInterval(pollInterval);
        }
    });
})();
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}
</style>
