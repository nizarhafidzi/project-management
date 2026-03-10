<?php

namespace Modules\Reporting\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reporting\Services\WorkforceCalculationService;

class WorkforceApiController extends Controller
{
    /**
     * Sync workforce monthly summary data.
     *
     * POST /api/workforce/sync-monthly
     * Body: { "month": 3, "year": 2026 } (optional, defaults to current)
     */
    public function syncMonthly(Request $request, WorkforceCalculationService $service): JsonResponse
    {
        $validated = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|digits:4',
        ]);

        $month = $validated['month'] ?? now()->month;
        $year = $validated['year'] ?? now()->year;

        $syncedCount = $service->calculateAndSyncSummary($month, $year);

        return response()->json([
            'success' => true,
            'message' => "Successfully synced {$syncedCount} user(s) for {$month}/{$year}.",
            'data' => [
                'month' => $month,
                'year' => $year,
                'synced_users' => $syncedCount,
            ],
        ]);
    }
}
