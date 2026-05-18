@props(['tag'])

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
