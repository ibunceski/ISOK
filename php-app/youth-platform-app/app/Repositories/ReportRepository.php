<?php

namespace App\Repositories;

use App\Models\Report;
use App\DTOs\ReportDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function create(ReportDTO $dto): Report
    {
        return Report::create([
            'anonymous_tag' => $dto->anonymousTag,
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
        return Report::whereNull('archived_at')
            ->orderByDesc('is_priority')
            ->orderByDesc('urgency_score')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    /**
     * Filter and paginate reports based on search criteria.
     */
    public function filterAndPaginate(array $filters = []): LengthAwarePaginator
    {
        $query = Report::whereNull('archived_at');

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('content', 'like', $searchTerm)
                  ->orWhere('category', 'like', $searchTerm);
            });
        }

        if (!empty($filters['risk_level'])) {
            $query->where('risk_level', $filters['risk_level']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->orderByDesc('is_priority')
            ->orderByDesc('urgency_score')
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    /**
     * Get dashboard statistics.
     */
    public function getStats(): array
    {
        $totalReports = Report::count();

        $riskCounts = Report::selectRaw('risk_level, COUNT(*) as count')
            ->groupBy('risk_level')
            ->pluck('count', 'risk_level');

        $criticalCount = Report::where('risk_level', 'CRITICAL')->count();
        $highCount = Report::where('risk_level', 'HIGH')->count();
        $priorityCount = Report::where('is_priority', true)->count();

        $recentReports = Report::orderByDesc('created_at')
            ->limit(5)
            ->get();

        return [
            'total' => $totalReports,
            'critical' => $criticalCount,
            'high' => $highCount,
            'medium' => $riskCounts->get('MEDIUM', 0),
            'low' => $riskCounts->get('LOW', 0),
            'priority' => $priorityCount,
            'recent' => $recentReports,
        ];
    }

    /**
     * Find a report by ID.
     */
    public function findById(int $id): ?Report
    {
        return Report::find($id);
    }

    /**
     * Get all unique categories for filtering.
     */
    public function getCategories(): Collection
    {
        return Report::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();
    }

    /**
     * Archive a report.
     */
    public function archive(int $id): bool
    {
        $report = Report::find($id);
        return $report ? $report->archive() : false;
    }

    /**
     * Unarchive a report.
     */
    public function unarchive(int $id): bool
    {
        $report = Report::withTrashed()->find($id);
        return $report ? $report->unarchive() : false;
    }
}
