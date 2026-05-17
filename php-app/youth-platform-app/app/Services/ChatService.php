<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Report;
use Illuminate\Support\Str;

class ChatService
{
    /**
     * Generate a unique anonymous tag for a report.
     */
    public function generateAnonymousTag(): string
    {
        $tag = 'USER-' . strtoupper(Str::random(8));

        while (Report::where('anonymous_tag', $tag)->exists()) {
            $tag = 'USER-' . strtoupper(Str::random(8));
        }

        return $tag;
    }

    /**
     * Send a message to a report's chat.
     */
    public function sendMessage(
        Report $report,
        string $content,
        string $senderType = 'user',
        ?int $userId = null
    ): Message {
        return Message::create([
            'report_id' => $report->id,
            'user_id' => $userId,
            'content' => $content,
            'sender_type' => $senderType,
        ]);
    }

    /**
     * Get all messages for a report.
     */
    public function getReportMessages(Report $report): \Illuminate\Database\Eloquent\Collection
    {
        return $report->messages()->orderBy('created_at', 'asc')->get();
    }

    /**
     * Admin responds to an urgent report.
     */
    public function adminRespond(Report $report, int $adminId, string $content): Message
    {
        return $this->sendMessage($report, $content, 'admin', $adminId);
    }
}

