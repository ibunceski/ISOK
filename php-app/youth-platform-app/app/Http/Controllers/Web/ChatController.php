<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ChatService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Display the chat interface for a reporter
     */
    public function view($tag): View
    {
        $report = Report::where('anonymous_tag', $tag)->firstOrFail();
        $messages = $this->chatService->getReportMessages($report);

        return view('chat.reporter', compact('report', 'messages', 'tag'));
    }

    /**
     * Look up a report by its anonymous tag and redirect to the chat.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $tag = strtoupper(trim($request->input('tag', '')));

        $report = Report::where('anonymous_tag', $tag)->first();

        if (!$report) {
            return redirect()->route('reports.create')
                ->withInput()
                ->with('chat_lookup_error', true);
        }

        return redirect()->route('chat.view', $report->anonymous_tag);
    }

    /**
     * Return new messages since the given ID (for polling).
     */
    public function getMessages(Request $request, $tag): JsonResponse
    {
        $report = Report::where('anonymous_tag', $tag)->firstOrFail();
        $afterId = (int) $request->get('after_id', 0);

        $query = $report->messages()->orderBy('created_at', 'asc');
        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get()->map(fn($m) => [
            'id' => $m->id,
            'content' => $m->content,
            'sender_type' => $m->sender_type,
            'created_at' => $m->created_at,
        ]);

        return response()->json(['data' => $messages]);
    }
}

