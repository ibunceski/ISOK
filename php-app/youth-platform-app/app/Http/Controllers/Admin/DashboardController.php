<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Repositories\ReportRepository;
use App\Services\ChatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected ReportRepository $repository;
    protected ChatService $chatService;

    public function __construct(ReportRepository $repository, ChatService $chatService)
    {
        $this->repository = $repository;
        $this->chatService = $chatService;
    }

    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search', ''),
            'risk_level' => $request->get('risk_level', ''),
            'category' => $request->get('category', ''),
            'show_archived' => $request->has('show_archived'),
        ];

        $reports = $this->repository->filterAndPaginate($filters);
        $stats = $this->repository->getStats();

        return view('admin.dashboard', compact('reports', 'stats', 'filters'));
    }

    /**
     * Show a specific report details.
     */
    public function showReport(int $id): View
    {
        $report = $this->repository->findById($id);

        if (!$report) {
            abort(404, 'Report not found');
        }

        $messages = $this->chatService->getReportMessages($report);

        return view('admin.report-show', compact('report', 'messages'));
    }

    /**
     * Archive a report.
     */
    public function archiveReport(int $id): RedirectResponse
    {
        $this->repository->archive($id);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Report archived successfully.');
    }

    /**
     * Unarchive a report.
     */
    public function unarchiveReport(int $id): RedirectResponse
    {
        $this->repository->unarchive($id);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Report unarchived successfully.');
    }

    /**
     * Send admin response to a report.
     */
    public function sendResponse(Request $request, int $reportId): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $report = $this->repository->findById($reportId);

        if (!$report) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Report not found.',
                ], 404);
            }
            
            return redirect()->back()
                ->with('error', 'Report not found.');
        }

        $message = $this->chatService->adminRespond(
            $report,
            auth()->id(),
            $validated['content']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Response sent successfully.',
                'data' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at,
                ],
            ], 201);
        }

        return redirect()->back()
            ->with('success', 'Response sent successfully.');
    }
}
