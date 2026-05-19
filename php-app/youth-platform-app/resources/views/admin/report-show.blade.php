@extends('layouts.app')

@section('title', 'Report #' . $report->id . ' - Youth Safety Platform')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-white">Report #{{ $report->id }}</h1>
                    @if($report->isArchived())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Archived
                        </span>
                    @endif
                </div>
                <span class="text-blue-100">{{ $report->created_at->format('F d, Y \a\t H:i') }}</span>
            </div>
        </div>

        <div class="p-6">
            <!-- User Tag and Actions Row -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center space-x-4">
                    <!-- Anonymous User Tag -->
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $report->anonymous_tag ?? 'Anonymous User' }}
                    </div>

                    <!-- Risk Level Badge -->
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
                        <span class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Priority
                        </span>
                    @endif
                </div>

                <!-- Chat and Archive/Unarchive Actions -->
                <div class="flex items-center space-x-2 flex-wrap gap-2">
                    <!-- Chat Button -->
                    <a href="#chat-messages" onclick="document.getElementById('chat-messages').scrollIntoView({behavior: 'smooth'}); document.getElementById('message-content').focus(); return false;" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Chat with User
                    </a>

                    @if($report->isArchived())
                        <form method="POST" action="{{ route('admin.reports.unarchive', $report->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Unarchive
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.reports.archive', $report->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Are you sure you want to archive this report?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                Archive
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Report Content -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Report Content</h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $report->content }}</p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                <!-- Submitted At -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Submitted</h3>
                    <p class="text-gray-900">{{ $report->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Chat and Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chat Section (2/3 width) -->
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
                        <span class="text-purple-100 text-sm">{{ count($messages) }} messages</span>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="chat-messages" class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md {{ $message->sender_type === 'admin' ? 'bg-blue-600 text-white rounded-l-lg rounded-br-lg' : 'bg-white text-gray-800 border border-gray-200 rounded-r-lg rounded-bl-lg' }} px-4 py-3 shadow-sm">
                                <div class="flex items-center mb-1">
                                    <span class="text-xs font-semibold {{ $message->sender_type === 'admin' ? 'text-blue-100' : 'text-gray-500' }}">
                                        {{ $message->sender_type === 'admin' ? 'Admin' : ($report->anonymous_tag ?? 'User') }}
                                    </span>
                                    <span class="ml-2 text-xs {{ $message->sender_type === 'admin' ? 'text-blue-200' : 'text-gray-400' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                </div>
                                <p class="text-sm">{{ $message->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div id="empty-state" class="text-center py-12">
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
                            id="message-content"
                            placeholder="Type your message to provide help and support..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required
                            maxlength="1000"
                            autocomplete="off"
                        >
                        <button
                            type="submit"
                            id="admin-send-btn"
                            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition-colors flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send
                        </button>
                    </form>
                    <p id="admin-chat-error" class="hidden text-xs text-red-600 mt-2">Failed to send. Please try again.</p>
                </div>
            </div>
        </div>

        <!-- Info Sidebar (1/3 width) -->
        <div class="space-y-4">
            <!-- Quick Stats Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="text-sm font-medium {{ $report->is_priority ? 'text-purple-600' : 'text-gray-600' }}">
                            {{ $report->is_priority ? 'Priority Review' : 'Standard Review' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Messages</span>
                        <span class="text-sm font-medium text-gray-900">{{ count($messages) }}</span>
                    </div>
                    @if($report->archived_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Archived</span>
                            <span class="text-sm font-medium text-gray-900">{{ $report->archived_at->diffForHumans() }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Tips Card -->
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
                <h3 class="text-sm font-medium text-blue-800 uppercase tracking-wide mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Help Tips
                </h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Respond promptly to high-risk reports</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Use clear, supportive language</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Archive resolved reports</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Escalate critical cases immediately</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const reportId = {{ $report->id }};
    const anonymousTag = '{{ $report->anonymous_tag ?? '' }}';
    let adminLastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let adminPollInterval = null;
    let adminIsSending = false;

    function scrollChatToBottom() {
        const container = document.getElementById('chat-messages');
        if (container) container.scrollTop = container.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function removeEmptyState() {
        const el = document.getElementById('empty-state');
        if (el) el.remove();
    }

    function appendMessage(msg) {
        removeEmptyState();
        const isAdmin = msg.sender_type === 'admin';
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'}`;
        div.innerHTML = `
            <div class="max-w-xs lg:max-w-md ${isAdmin ? 'bg-blue-600 text-white rounded-l-lg rounded-br-lg' : 'bg-white text-gray-800 border border-gray-200 rounded-r-lg rounded-bl-lg'} px-4 py-3 shadow-sm">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-semibold ${isAdmin ? 'text-blue-100' : 'text-gray-500'}">
                        ${isAdmin ? 'Admin' : escapeHtml(anonymousTag || 'User')}
                    </span>
                    <span class="ml-2 text-xs ${isAdmin ? 'text-blue-200' : 'text-gray-400'}">${escapeHtml(msg.created_at)}</span>
                </div>
                <p class="text-sm">${escapeHtml(msg.content)}</p>
            </div>`;
        container.appendChild(div);
        scrollChatToBottom();
    }

    async function pollMessages() {
        try {
            const response = await fetch(`/admin/reports/${reportId}/messages?after_id=${adminLastMessageId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;

            const data = await response.json();
            for (const msg of (data.data || [])) {
                appendMessage(msg);
                adminLastMessageId = msg.id;
            }
        } catch (e) {
            // silently ignore poll errors
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        scrollChatToBottom();

        document.getElementById('admin-chat-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (adminIsSending) return;

            const input = document.getElementById('message-content');
            const content = input.value.trim();
            if (!content) return;

            adminIsSending = true;
            const btn = document.getElementById('admin-send-btn');
            const errEl = document.getElementById('admin-chat-error');
            btn.disabled = true;
            errEl.classList.add('hidden');

            try {
                const response = await fetch(`/admin/reports/${reportId}/respond`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ content })
                });

                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || 'Failed');

                input.value = '';
                appendMessage(data.data);
                adminLastMessageId = data.data.id;
            } catch (err) {
                console.error('Send error:', err);
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                adminIsSending = false;
                input.focus();
            }
        });

        adminPollInterval = setInterval(pollMessages, 3000);
    });

    window.addEventListener('beforeunload', () => clearInterval(adminPollInterval));
</script>
@endpush
@endsection
