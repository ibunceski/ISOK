@extends('layouts.app')

@section('title', 'Chat Support - Youth Safety Platform')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Chat Header -->
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
                    onclick="copyToClipboard('{{ $tag }}')"
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
                        $statusConfig = match($report->risk_level) {
                            'CRITICAL' => ['color' => 'text-red-700', 'bg' => 'bg-red-100', 'border' => 'border-red-300', 'pulse' => true],
                            'HIGH' => ['color' => 'text-orange-700', 'bg' => 'bg-orange-100', 'border' => 'border-orange-300', 'pulse' => true],
                            'MEDIUM' => ['color' => 'text-yellow-700', 'bg' => 'bg-yellow-100', 'border' => 'border-yellow-300', 'pulse' => false],
                            'LOW' => ['color' => 'text-green-700', 'bg' => 'bg-green-100', 'border' => 'border-green-300', 'pulse' => false],
                            default => ['color' => 'text-gray-700', 'bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'pulse' => false],
                        };
                    @endphp
                    <span class="px-3 py-1.5 rounded-lg font-semibold {{ $statusConfig['color'] }} {{ $statusConfig['bg'] }} border {{ $statusConfig['border'] }} {{ $statusConfig['pulse'] ? 'animate-pulse' : '' }}">
                        {{ $report->risk_level }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-b-2xl shadow-lg overflow-hidden flex flex-col" style="height: 650px;">
            <!-- Messages Area -->
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
                            <p class="text-gray-500 text-sm">Our trained support team will respond to your report soon. Feel free to send a message with any additional details that might help us assist you better.</p>
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

            <!-- Message Input Form -->
            <div class="border-t border-gray-200/50 p-4 bg-white/50 backdrop-blur-sm">
                <form id="chat-form" onsubmit="sendMessage(event, '{{ $tag }}')" class="flex gap-3">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea
                            id="message-input"
                            name="content"
                            rows="1"
                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none bg-white placeholder-gray-400 hover:border-gray-300"
                            placeholder="Type your message here..."
                            style="max-height: 120px; min-height: 48px;"
                        ></textarea>
                        <div class="absolute right-3 bottom-3 text-xs text-gray-400">
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
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-1">How This Works</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Your messages are completely anonymous</li>
                        <li>• Our trained support team monitors chat 24/7</li>
                        <li>• Response times vary based on report urgency</li>
                        <li>• Keep your Anonymous ID ({{ $tag }}) safe to access this chat later</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="mt-6 flex gap-3">
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
<script>
    let isSending = false;
    let pollInterval = null;
    let lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};
    const chatTag = '{{ $tag }}';

    function scrollToBottom(smooth = true) {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTo({ top: container.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
        }
    }

    function formatTime(date) {
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function createMessageElement(content, isUser = true, timeStr = null) {
        if (!timeStr) timeStr = formatTime(new Date());
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'} animate-fade-in`;

        if (isUser) {
            messageDiv.innerHTML = `
                <div class="max-w-xs sm:max-w-sm lg:max-w-md bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md rounded-2xl p-4 transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-white/90">You</span>
                        <span class="text-xs text-blue-100">${timeStr}</span>
                    </div>
                    <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                </div>`;
        } else {
            messageDiv.innerHTML = `
                <div class="max-w-xs sm:max-w-sm lg:max-w-md bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 border border-gray-200 rounded-2xl p-4 transition-all duration-200 hover:shadow-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-purple-600">Support Team</span>
                        <span class="text-xs text-gray-500">${timeStr}</span>
                    </div>
                    <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                </div>`;
        }

        return messageDiv;
    }

    function removeEmptyState() {
        const messagesContainer = document.getElementById('messages-container');
        const emptyState = messagesContainer.querySelector('.text-center.max-w-md');
        if (emptyState && emptyState.parentElement.classList.contains('flex')) {
            emptyState.closest('.flex').remove();
        }
    }

    async function sendMessage(event, tag) {
        event.preventDefault();
        if (isSending) return;

        const input = document.getElementById('message-input');
        const charCount = document.getElementById('char-count');
        const content = input.value.trim();
        if (!content) return;

        isSending = true;
        const submitBtn = document.getElementById('send-button');
        const originalBtnContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Sending...</span>`;

        removeEmptyState();
        const messagesContainer = document.getElementById('messages-container');
        const userMessage = createMessageElement(content, true);
        messagesContainer.appendChild(userMessage);
        input.value = '';
        input.style.height = 'auto';
        if (charCount) charCount.textContent = '0';
        scrollToBottom();

        try {
            const response = await fetch(`/chat/${tag}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ content })
            });

            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || 'Failed to send message');

            // Update lastMessageId so polling skips this message
            if (data.message && data.message.id) lastMessageId = data.message.id;
            scrollToBottom();
        } catch (error) {
            console.error('Error sending message:', error);
            userMessage.classList.add('opacity-75');
            userMessage.querySelector('p:last-child').innerHTML += '<br><span class="text-xs text-red-200 mt-1 block">Failed to send. Please try again.</span>';
            showToast('Failed to send message. Please check your connection and try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            isSending = false;
            input.focus();
        }
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const bgColor = type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in flex items-center gap-2`;
        toast.innerHTML = `<span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function autoExpandTextarea() {
        const textarea = document.getElementById('message-input');
        if (!textarea) return;
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            const charCount = document.getElementById('char-count');
            if (charCount) {
                charCount.textContent = this.value.length;
                charCount.classList.toggle('text-red-500', this.value.length > 900);
            }
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Anonymous ID copied to clipboard!', 'info');
        }).catch(() => {
            showToast('Failed to copy. Please select and copy manually.', 'error');
        });
    }

    function startPolling(tag) {
        pollInterval = setInterval(async () => {
            if (isSending) return;
            try {
                const response = await fetch(`/chat/${tag}/messages?after_id=${lastMessageId}`);
                if (!response.ok) return;

                const data = await response.json();
                const messages = data.data || [];

                if (messages.length > 0) {
                    removeEmptyState();
                    const messagesContainer = document.getElementById('messages-container');
                    for (const msg of messages) {
                        const isAdmin = msg.sender_type === 'admin';
                        const timeStr = msg.created_at
                            ? new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
                            : formatTime(new Date());
                        const messageEl = createMessageElement(msg.content, !isAdmin, timeStr);
                        messagesContainer.appendChild(messageEl);
                        lastMessageId = msg.id;
                    }
                    scrollToBottom();
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }, 3000);
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    window.addEventListener('load', () => {
        scrollToBottom(false);
        autoExpandTextarea();
        startPolling(chatTag);
    });

    window.addEventListener('beforeunload', stopPolling);
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
@endpush
@endsection

