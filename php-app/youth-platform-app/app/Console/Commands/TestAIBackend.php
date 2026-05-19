<?php

namespace App\Console\Commands;

use App\Services\AIClient;
use Illuminate\Console\Command;

class TestAIBackend extends Command
{
    protected $signature = 'test:ai-backend';
    protected $description = 'Test if the AI backend is reachable';

    public function handle()
    {
        $this->info('Testing AI Backend Connection...');

        $aiClient = app(AIClient::class);

        $testText = 'This is a test message to check if the backend is reachable';

        $this->info('Sending request to AI backend...');
        $result = $aiClient->analyze($testText);

        $this->info('Result:');
        $this->info(json_encode($result, JSON_PRETTY_PRINT));

        if ($result === null) {
            $this->error('ERROR: AI backend returned null!');
            return 1;
        }

        $this->info('SUCCESS: AI backend is responding correctly');
        return 0;
    }
}

