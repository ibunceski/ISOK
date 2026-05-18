<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    protected ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        $reports = $this->repository->allSortedByPriority();
        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        return view('reports.create');
    }

    public function success(): View
    {
        return view('reports.success');
    }
}
