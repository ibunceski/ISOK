<?php

namespace App\DTOs;

class ReportDTO
{
    public string $content;
    public string $riskLevel;
    public ?string $category;
    public ?float $urgencyScore;
    public bool $isPriority;
    public ?string $ipAddress;
    public ?string $anonymousTag;

    public function __construct(
        string $content,
        string $riskLevel,
        ?string $category,
        ?float $urgencyScore,
        bool $isPriority,
        ?string $ipAddress,
        ?string $anonymousTag = null
    ) {
        $this->content = $content;
        $this->riskLevel = $riskLevel;
        $this->category = $category;
        $this->urgencyScore = $urgencyScore;
        $this->isPriority = $isPriority;
        $this->ipAddress = $ipAddress;
        $this->anonymousTag = $anonymousTag;
    }
}
