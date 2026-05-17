<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Services\ReportAnalysisService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    protected ReportAnalysisService $service;

    public function __construct(ReportAnalysisService $service)
    {
        $this->service = $service;
    }

    public function store(StoreReportRequest $request): JsonResponse
    {
        $dto = $this->service->process(
            $request->validated()['content'],
            $request->ip()
        );

        return response()->json([
            'message' => 'Report submitted successfully',
            'data' => [
                'anonymous_tag' => $dto->anonymousTag,
                'risk_level' => $dto->riskLevel,
                'category' => $dto->category,
                'urgency_score' => $dto->urgencyScore,
                'is_priority' => $dto->isPriority,
            ],
        ], Response::HTTP_CREATED);
    }
}
