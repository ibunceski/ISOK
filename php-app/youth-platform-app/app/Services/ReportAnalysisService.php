<?php

namespace App\Services;

use App\DTOs\ReportDTO;
use App\Repositories\ReportRepository;

class ReportAnalysisService
{
    protected AIClient $aiClient;
    protected ReportRepository $repository;

    public function __construct(AIClient $aiClient, ReportRepository $repository)
    {
        $this->aiClient = $aiClient;
        $this->repository = $repository;
    }

    public function process(string $content, ?string $ipAddress = null): ReportDTO
    {
        $analysis = $this->aiClient->analyze($content);

        $urgencyScore = $analysis['urgency_score'] ?? $analysis['score'] ?? 0.0;
        $riskLevel = $this->mapRiskLevel($urgencyScore);
        $category = $analysis['category'] ?? null;
        $isPriority = in_array($riskLevel, ['HIGH', 'CRITICAL']);

        $dto = new ReportDTO(
            $content,
            $riskLevel,
            $category,
            $urgencyScore,
            $isPriority,
            $ipAddress
        );

        $this->repository->create($dto);

        return $dto;
    }

    protected function mapRiskLevel(float $score): string
    {
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
