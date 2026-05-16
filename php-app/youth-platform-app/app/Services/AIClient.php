<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIClient
{
    protected string $endpoint;
    protected int $timeout;
    protected int $retries;

    public function __construct()
    {
        $this->endpoint = config('services.ai.analyze_url', 'http://127.0.0.1:8000/analyze');
        $this->timeout = 5;
        $this->retries = 2;
    }

    public function analyze(string $text): ?array
    {
        try {
            $response = Http::retry($this->retries, 100)
                ->timeout($this->timeout)
                ->post($this->endpoint, ['text' => $text]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('AI analysis failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (ConnectionException $e) {
            Log::error('AI service connection error', ['exception' => $e]);
        }

        return null;
    }
}
