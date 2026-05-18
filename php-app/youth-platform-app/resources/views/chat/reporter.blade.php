@extends('layouts.app')

@section('title', 'Report Chat - Youth Safety Platform')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Chat Header -->
        <div class="bg-white rounded-t-xl shadow-md border-b border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-2xl font-bold text-gray-900">Chat with Our Support Team</h1>
                <button
                    type="button"
                    onclick="copyToClipboard('{{ $tag }}')"
                    class="text-sm bg-blue-100 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-200 transition-colors"
                >
                    📋 Copy ID: {{ $tag }}
                </button>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <span class="font-medium mr-2">Your Anonymous ID:</span>
                    <code class="bg-gray-100 px-3 py-1 rounded font-mono font-bold">{{ $tag }}</code>
                </div>
                <div class="flex items-center">
                    <span class="font-medium mr-2">Report Status:</span>
                    @php
                        $statusColor = match($report->risk_level) {
                            'CRITICAL' => 'text-red-600 bg-red-50',
                            'HIGH' => 'text-orange-600 bg-orange-50',
                            'MEDIUM' => 'text-yellow-600 bg-yellow-50',
                            'LOW' => 'text-green-600 bg-green-50',
                            default => 'text-gray-600 bg-gray-50',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded font-medium {{ $statusColor }}">{{ $report->risk_level }}</span>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-b-xl shadow-md overflow-hidden flex flex-col" style="height: 600px;">
            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                @forelse($messages as $message)
                    <div class="flex {{ $message->sender_type === 'admin' ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs lg:max-w-md {{ $message->sender_type === 'admin' ? 'bg-gray-100 text-gray-900' : 'bg-blue-600 text-white' }} rounded-lg p-4">
                            @if($message->sender_type === 'admin')
                                <p class="text-xs font-semibold text-gray-600 mb-1">👤 Support Team</p>
                            @else
                                <p class="text-xs font-semibold opacity-75 mb-1">You</p>
                            @endif
                            <p class="text-sm">{{ $message->content }}</p>
                            <p class="text-xs {{ $message->sender_type === 'admin' ? 'text-gray-500' : 'text-blue-200' }} mt-2">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <h3 class="text-gray-500 font-medium mb-1">No messages yet</h3>
                            <p class="text-gray-400 text-sm">Our team will respond to your report soon. Send a message to provide additional details.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200"></div>

            <!-- Message Input Form -->
            <div class="p-4 bg-gray-50">
                <form id="chat-form" onsubmit="sendMessage(event, '{{ $tag }}')">
                    @csrf
                    <div class="flex gap-3">
                        <textarea
                            id="message-input"
                            name="content"
                            rows="1"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Type your message here..."
                            style="max-height: 100px; min-height: 45px;"
                        ></textarea>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2 flex-shrink-0"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send
                        </button>
                    </div>
                </form>
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
    // Auto-scroll to latest message
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    }

    // Send message via AJAX
    function sendMessage(event, tag) {
        event.preventDefault();

        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');
        const content = input.value.trim();

        if (!content) {
            return;
        }

        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        // Send message
        fetch(`/chat/${tag}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear input
                input.value = '';
                input.style.height = 'auto';

                // Add message to display
                const messagesContainer = document.getElementById('messages-container');

                // Remove "no messages" state if it exists
                const noMessages = messagesContainer.querySelector('[class*="flex-1 overflow-y-auto"] > div:has([class*="text-gray-500"])');
                if (noMessages) {
                    messagesContainer.innerHTML = '';
                }

                // Add new message
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-end';
                messageDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md bg-blue-600 text-white rounded-lg p-4">
                        <p class="text-xs font-semibold opacity-75 mb-1">You</p>
                        <p class="text-sm">${content}</p>
                        <p class="text-xs text-blue-200 mt-2">${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</p>
                    </div>
                `;
                messagesContainer.appendChild(messageDiv);

                // Scroll to bottom
                scrollToBottom();
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            input.focus();
        });
    }

    // Auto-expand textarea
    document.getElementById('message-input').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Anonymous ID copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }

    // Initial scroll
    window.addEventListener('load', scrollToBottom);
</script>
@endpush
@endsection

