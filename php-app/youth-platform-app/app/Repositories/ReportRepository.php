<?php

namespace App\Repositories;

use App\Models\Report;
use App\DTOs\ReportDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository
{
    public function create(ReportDTO $dto): Report
    {
        return Report::create([
            'content' => $dto->content,
            'risk_level' => $dto->riskLevel,
            'category' => $dto->category,
            'urgency_score' => $dto->urgencyScore,
            'is_priority' => $dto->isPriority,
            'ip_address' => $dto->ipAddress,
        ]);
    }

    public function allSortedByPriority(): LengthAwarePaginator
    {
        return Report::orderByDesc('is_priority')
            ->orderByDesc('urgency_score')
            ->orderByDesc('created_at')
            ->paginate(15);
    }
}
