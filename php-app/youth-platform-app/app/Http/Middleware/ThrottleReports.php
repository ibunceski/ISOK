<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleReports extends ThrottleRequests
{
    // Uses Laravel's built‑in throttle logic.
}
