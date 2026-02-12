<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\Livewire\ProjectList;
use Modules\Project\Livewire\ProjectForm;
use Modules\Project\Livewire\ProjectShow;
use Modules\Project\Livewire\WbsManager;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('projects', ProjectList::class)->name('project.index');
    Route::get('projects/create', ProjectForm::class)->name('project.create');
    Route::get('projects/{project}', ProjectShow::class)->name('project.show');
    Route::get('projects/{project}/edit', ProjectForm::class)->name('project.edit');
    Route::get('projects/{project}/wbs', WbsManager::class)->name('project.wbs');
});
