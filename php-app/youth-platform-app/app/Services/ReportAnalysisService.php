<?php

namespace App\Services;

use App\DTOs\ReportDTO;
use App\Repositories\ReportRepository;

class ReportAnalysisService
{
    protected AIClient $aiClient;
    protected ReportRepository $repository;
    protected ChatService $chatService;

    public function __construct(AIClient $aiClient, ReportRepository $repository, ChatService $chatService)
    {
        $this->aiClient = $aiClient;
        $this->repository = $repository;
        $this->chatService = $chatService;
    }

    public function process(string $content, ?string $ipAddress = null): ReportDTO
    {
        // $analysis is assumed to be an associative array parsed from the Python JSON
        $analysis = $this->aiClient->analyze($content);

        // 1. Get the score
        $urgencyScore = $analysis['score'] ?? 0.0;

        // 2. Map to HIGH/MEDIUM/LOW
        $riskLevel = $this->mapRiskLevel($urgencyScore);

        // 3. Extract the primary category from the nested categories array
        $category = $this->determinePrimaryCategory($analysis['categories'] ?? []);

        // 4. Use the AI's 'action_required' boolean as the source of truth for priority
        // with a fallback to the manual risk level check just in case.
        $isPriority = $analysis['action_required'] ?? in_array($riskLevel, ['HIGH', 'CRITICAL']);

        // 5. Generate an anonymous tag for the report
        $anonymousTag = $this->chatService->generateAnonymousTag();

        $dto = new ReportDTO(
            $content,
            $riskLevel,
            $category,
            $urgencyScore,
            $isPriority,
            $ipAddress,
            $anonymousTag
        );

        $this->repository->create($dto);

        return $dto;
    }

    /**
     * Finds the highest scoring risk category from the AI response
     */
    protected function determinePrimaryCategory(array $categories): ?string
    {
        if (empty($categories)) {
            return null;
        }

        // We don't want 'safe_baseline' to be flagged as an incident category
        unset($categories['safe_baseline']);

        // After removing safe_baseline, check if there are any categories left
        if (empty($categories)) {
            return null;
        }

        $maxScore = max($categories);

        // If the highest risk score is basically 0, there is no threat category
        if ($maxScore <= 0.0) {
            return 'safe'; // Or return null if your database prefers null for safe messages
        }

        // Find and return the key (e.g., 'self_harm', 'danger') that has the highest score
        return array_search($maxScore, $categories) ?: null;
    }

    protected function mapRiskLevel(float $score): string
    {
        // Enforce limits between 0.0 and 1.0 (Python might return 1.2 internally for self_harm,
        // but the main score is capped. This is just a safeguard).
        $score = max(0.0, min(1.0, $score));

        if ($score > 0.95) {
            return 'CRITICAL';
        }

        if ($score >= 0.75) {
            return 'HIGH';
        }

        if ($score >= 0.4) {
            return 'MEDIUM';
        }

        return 'LOW';
    }
}
