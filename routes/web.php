<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutodeskAuthController;

Route::view('/', 'welcome');


// Autodesk Auth Routes
Route::middleware(['auth'])->prefix('auth/autodesk')->group(function () {
    Route::get('/connect-master', [AutodeskAuthController::class, 'connectMaster'])->name('autodesk.connect.master');
    Route::get('/connect', [AutodeskAuthController::class, 'connectUser'])->name('autodesk.connect.user');
    Route::get('/callback', [AutodeskAuthController::class, 'callback'])->name('autodesk.callback');
    Route::get('/disconnect', [AutodeskAuthController::class, 'disconnect'])->name('autodesk.disconnect');
});

Route::get('dashboard', \Modules\Project\Livewire\ExecutiveDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
