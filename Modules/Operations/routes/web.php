<?php

use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OperationsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('operations/daily-log', \Modules\Operations\Livewire\DailyLogForm::class)->name('operations.daily-log');
    Route::get('operations/daily-log-history', \Modules\Operations\Livewire\DailyLogHistory::class)->name('operations.daily-log-history');
    Route::get('operations/approvals', \Modules\Operations\Livewire\ApprovalManager::class)->name('operations.approvals');
    Route::get('operations/bim-viewer', \Modules\Operations\Livewire\BimViewer::class)->name('operations.bim-viewer');

    Route::resource('operations', OperationsController::class)->names('operations');
});
