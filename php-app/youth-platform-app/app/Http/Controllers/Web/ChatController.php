<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ChatService;
use Illuminate\Contracts\View\View;
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
}

