@props(['report', 'messages'])

<div class="lg:col-span-2">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 border-b border-gray-200">
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
        <div id="chat-messages" class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4 scroll-smooth">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }} animate-fade-in">
                    <div class="max-w-xs lg:max-w-md {{ $message->sender_type === 'admin' ? 'bg-blue-600 text-white rounded-l-lg rounded-br-lg' : 'bg-white text-gray-800 border border-gray-200 rounded-r-lg rounded-bl-lg' }} px-4 py-3 shadow-sm">
                        <div class="flex items-center mb-1">
                            <span class="text-xs font-semibold {{ $message->sender_type === 'admin' ? 'text-blue-100' : 'text-gray-500' }}">
                                {{ $message->sender_type === 'admin' ? 'Admin' : ($report->anonymous_tag ?? 'User') }}
                            </span>
                            <span class="ml-2 text-xs {{ $message->sender_type === 'admin' ? 'text-blue-200' : 'text-gray-400' }}">
                                {{ $message->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm">{{ $message->content }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="mt-2 text-gray-500">No messages yet</p>
                    <p class="text-sm text-gray-400">Start a conversation to provide support</p>
                </div>
            @endforelse
        </div>

        <!-- Send Message Form -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <form id="admin-chat-form" class="flex space-x-3">
                @csrf
                <input
                    type="text"
                    name="content"
                    id="admin-message-input"
                    placeholder="Type your message to provide help and support..."
                    class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                    required
                    maxlength="1000"
                >
                <button
                    type="submit"
                    id="admin-send-button"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg disabled:shadow-none disabled:cursor-not-allowed transform hover:-translate-y-0.5 disabled:transform-none"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    function createMessageElement(content, isAdmin, timeAgo) {
        const div = document.createElement('div');
        div.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'} animate-fade-in`;
        div.innerHTML = `
            <div class="max-w-xs lg:max-w-md ${isAdmin ? 'bg-blue-600 text-white rounded-l-lg rounded-br-lg' : 'bg-white text-gray-800 border border-gray-200 rounded-r-lg rounded-bl-lg'} px-4 py-3 shadow-sm">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-semibold ${isAdmin ? 'text-blue-100' : 'text-gray-500'}">
                        ${isAdmin ? 'Admin' : '${reportTag}'}
                    </span>
                    <span class="ml-2 text-xs ${isAdmin ? 'text-blue-200' : 'text-gray-400'}">
                        ${timeAgo || 'just now'}
                    </span>
                </div>
                <p class="text-sm">${escapeHtml(content)}</p>
            </div>
        `;
        return div;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Sending...</span>
        `;

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

            // Clear input
            input.value = '';
            input.focus();

            // Poll immediately to get the new message
            await pollMessages();
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
            isSending = false;
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
                const emptyState = container.querySelector('.text-center.py-12');
                if (emptyState) {
                    emptyState.remove();
                }

                // Add new messages
                for (let i = lastMessageCount; i < messages.length; i++) {
                    const msg = messages[i];
                    const isAdmin = msg.sender_type === 'admin';
                    const messageEl = createMessageElement(msg.content, isAdmin, 'just now');
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

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
        
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
