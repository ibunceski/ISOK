<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\Message;
use App\Services\ChatService;
use Tests\TestCase;

class ChatFeatureTest extends TestCase
{
    protected ChatService $chatService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatService = app(ChatService::class);
    }

    public function test_generates_unique_anonymous_tag(): void
    {
        $tag1 = $this->chatService->generateAnonymousTag();
        $tag2 = $this->chatService->generateAnonymousTag();

        $this->assertNotEquals($tag1, $tag2);
        $this->assertStringStartsWith('USER-', $tag1);
        $this->assertStringStartsWith('USER-', $tag2);
    }

    public function test_can_send_user_message(): void
    {
        $report = Report::factory()->create();

        $message = $this->chatService->sendMessage(
            $report,
            'Test user message',
            'user'
        );

        $this->assertEquals('Test user message', $message->content);
        $this->assertEquals('user', $message->sender_type);
        $this->assertDatabaseHas('messages', [
            'report_id' => $report->id,
            'content' => 'Test user message',
        ]);
    }

    public function test_can_get_report_messages(): void
    {
        $report = Report::factory()->create();

        $this->chatService->sendMessage($report, 'Message 1', 'user');
        $this->chatService->sendMessage($report, 'Message 2', 'admin', 1);

        $messages = $this->chatService->getReportMessages($report);

        $this->assertCount(2, $messages);
        $this->assertEquals('Message 1', $messages[0]->content);
        $this->assertEquals('Message 2', $messages[1]->content);
    }

    public function test_admin_can_respond_to_report(): void
    {
        $report = Report::factory()->create();
        $adminId = 1;

        $message = $this->chatService->adminRespond(
            $report,
            $adminId,
            'Admin response'
        );

        $this->assertEquals('Admin response', $message->content);
        $this->assertEquals('admin', $message->sender_type);
        $this->assertEquals($adminId, $message->user_id);
    }

    public function test_can_retrieve_messages_by_anonymous_tag(): void
    {
        $anonymousTag = $this->chatService->generateAnonymousTag();
        $report = Report::factory()->create([
            'anonymous_tag' => $anonymousTag,
        ]);

        $this->chatService->sendMessage($report, 'Test message', 'user');

        $response = $this->getJson("/api/reports/{$anonymousTag}/messages");

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.content', 'Test message');
    }

    public function test_can_send_message_via_api(): void
    {
        $anonymousTag = $this->chatService->generateAnonymousTag();
        $report = Report::factory()->create([
            'anonymous_tag' => $anonymousTag,
        ]);

        $response = $this->postJson("/api/reports/{$anonymousTag}/messages", [
            'content' => 'API test message',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.content', 'API test message');
    }
}

