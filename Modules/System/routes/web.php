<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\SystemController;

Route::middleware(['auth', 'verified', 'role:Superadmin'])->group(function () {
    Route::get('system/auto-checkout', \Modules\System\Livewire\AutoCheckoutSettings::class)->name('system.auto-checkout');
});
