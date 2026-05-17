<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get all messages for a report using anonymous tag.
     */
    public function getMessages(string $anonymousTag): JsonResponse
    {
        $report = Report::where('anonymous_tag', $anonymousTag)->first();

        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $messages = $this->chatService->getReportMessages($report)
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at,
                ];
            });

        return response()->json([
            'data' => $messages,
            'anonymous_tag' => $anonymousTag,
        ], Response::HTTP_OK);
    }

    /**
     * Send a message to a report using anonymous tag.
     */
    public function sendMessage(Request $request, string $anonymousTag): JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $report = Report::where('anonymous_tag', $anonymousTag)->first();

        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $message = $this->chatService->sendMessage(
            $report,
            $validated['content'],
            'user'
        );

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_type' => $message->sender_type,
                'created_at' => $message->created_at,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * Admin responds to a report (requires authentication).
     */
    public function adminRespond(Request $request, int $reportId): JsonResponse
    {
        // This should be protected by middleware to ensure only admins can access
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $report = Report::find($reportId);

        if (!$report) {
            return response()->json([
                'message' => 'Report not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $message = $this->chatService->adminRespond(
            $report,
            auth()->id(),
            $validated['content']
        );

        return response()->json([
            'message' => 'Response sent successfully',
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_type' => $message->sender_type,
                'created_at' => $message->created_at,
            ],
        ], Response::HTTP_CREATED);
    }
}

