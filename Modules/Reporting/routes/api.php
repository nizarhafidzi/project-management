<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Http\Controllers\ReportingController;
use Modules\Reporting\Http\Controllers\WorkforceApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('reportings', ReportingController::class)->names('reporting');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/workforce/sync-monthly', [WorkforceApiController::class, 'syncMonthly'])
        ->name('api.workforce.sync-monthly');
});
