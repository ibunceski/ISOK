<?php

namespace App\Providers;

use App\Repositories\ReportRepository;
use App\Services\AIClient;
use App\Services\ReportAnalysisService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AIClient::class, function ($app) {
            return new AIClient();
        });

        $this->app->singleton(ReportRepository::class, function ($app) {
            return new ReportRepository();
        });

        $this->app->singleton(ReportAnalysisService::class, function ($app) {
            return new ReportAnalysisService(
                $app->make(AIClient::class),
                $app->make(ReportRepository::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
