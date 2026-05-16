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

        $riskLevel = $this->mapRiskLevel($analysis['risk_level'] ?? 'low');
        $category = $analysis['category'] ?? null;
        $urgencyScore = $analysis['urgency_score'] ?? null;
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

    protected function mapRiskLevel(string $level): string
    {
        $level = strtolower($level);
        return match ($level) {
            'critical' => 'CRITICAL',
            'high' => 'HIGH',
            'medium' => 'MEDIUM',
            default => 'LOW',
        };
    }
}
