<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
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

        return view('admin.report-show', compact('report'));
    }
}
