<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Send a message to a report's chat
     */
    public function sendMessage(Request $request, $tag): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $report = Report::where('anonymous_tag', $tag)->firstOrFail();

        $message = $this->chatService->sendMessage(
            report: $report,
            content: $validated['content'],
            senderType: 'user'
        );

        return response()->json([
            'success' => true,
            'message' => $message,
        ], 201);
    }
}

