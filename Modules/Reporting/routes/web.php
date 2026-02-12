<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Livewire\ProjectDashboard;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('projects/{project}/dashboard', ProjectDashboard::class)
        ->name('reporting.dashboard');

    Route::get('reporting/workforce-analytics', \Modules\Reporting\Livewire\WorkforcePage::class)
        ->middleware(['role:Superadmin|Manager'])
        ->name('reporting.workforce-analytics');
});
