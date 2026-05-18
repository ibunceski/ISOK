<script>
/**
 * Chat utilities for reporter chat interface
 * Handles message sending, polling, and UI updates
 */
window.chatUtils = (function() {
    let isSending = false;
    let pollInterval = null;
    let lastMessageCount = 0;

    // Auto-scroll to latest message with smooth animation
    function scrollToBottom(smooth = true) {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTo({
                top: container.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }
    }

    // Format time for display
    function formatTime(date) {
        return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }

    // Create message HTML element
    function createMessageElement(content, isUser = true) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'} animate-fade-in`;

        const now = new Date();
        const timeStr = formatTime(now);

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
                </div>
            `;
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
                </div>
            `;
        }

        return messageDiv;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Remove empty state when first message is added
    function removeEmptyState() {
        const messagesContainer = document.getElementById('messages-container');
        const emptyState = messagesContainer.querySelector('.text-center.max-w-md');
        if (emptyState && emptyState.parentElement.classList.contains('flex')) {
            emptyState.closest('.flex').remove();
        }
    }

    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const bgColor = type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'error' ? '❌' : 'ℹ️';

        toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in flex items-center gap-2`;
        toast.innerHTML = `<span>${icon}</span><span>${message}</span>`;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Send message via AJAX
    async function sendMessage(event, tag) {
        event.preventDefault();

        if (isSending) return;

        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');
        const charCount = document.getElementById('char-count');
        const content = input.value.trim();

        if (!content) {
            return;
        }

        isSending = true;

        // Disable submit button and show loading state
        const submitBtn = document.getElementById('send-button');
        const originalBtnContent = submitBtn.innerHTML;
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
        const messagesContainer = document.getElementById('messages-container');
        const userMessage = createMessageElement(content, true);
        messagesContainer.appendChild(userMessage);

        // Clear input immediately for better UX
        input.value = '';
        input.style.height = 'auto';
        if (charCount) charCount.textContent = '0';

        scrollToBottom();

        try {
            // Send message to server
            const response = await fetch(`/chat/${tag}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ content: content })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to send message');
            }

            // Message sent successfully - scroll to bottom
            scrollToBottom();
        } catch (error) {
            console.error('Error sending message:', error);

            // Show error indicator on the message
            userMessage.classList.add('opacity-75');
            userMessage.querySelector('p:last-child').innerHTML += '<br><span class="text-xs text-red-200 mt-1 block">⚠️ Failed to send. Please try again.</span>';

            // Show user-friendly error
            showToast('Failed to send message. Please check your connection and try again.', 'error');
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            isSending = false;
            input.focus();
        }
    }

    // Auto-expand textarea
    function initTextareaAutoExpand() {
        const textarea = document.getElementById('message-input');
        if (!textarea) return;

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';

            // Update character count
            const charCount = document.getElementById('char-count');
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

    // Copy to clipboard with better feedback
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Anonymous ID copied to clipboard! ✓', 'info');
        }).catch(err => {
            console.error('Failed to copy:', err);
            showToast('Failed to copy. Please select and copy manually.', 'error');
        });
    }

    // Poll for new messages from admin
    function startPolling(tag) {
        const initialMessages = document.querySelectorAll('#messages-container .flex:not(.hidden)');
        lastMessageCount = initialMessages.length;

        pollInterval = setInterval(async () => {
            if (isSending) return;

            try {
                const response = await fetch(`/api/reports/${tag}/messages`);
                if (!response.ok) return;

                const data = await response.json();
                const messages = data.data || [];

                if (messages.length > lastMessageCount) {
                    // New messages available
                    const messagesContainer = document.getElementById('messages-container');

                    // Add only new messages
                    for (let i = lastMessageCount; i < messages.length; i++) {
                        const msg = messages[i];
                        const isAdmin = msg.sender_type === 'admin';
                        const messageEl = createMessageElement(msg.content, !isAdmin);
                        messagesContainer.appendChild(messageEl);
                    }

                    lastMessageCount = messages.length;
                    scrollToBottom();
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }, 5000); // Poll every 5 seconds
    }

    // Stop polling when leaving page
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Initialize on page load
    function init(tag) {
        scrollToBottom(false);
        initTextareaAutoExpand();
        
        if (tag) {
            startPolling(tag);
        }
    }

    // Public API
    return {
        init,
        sendMessage,
        copyToClipboard,
        scrollToBottom,
        stopPolling
    };
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
