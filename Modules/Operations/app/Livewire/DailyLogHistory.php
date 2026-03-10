<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Modules\Operations\Models\DailyLog;
use Modules\Project\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyLogHistory extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $filterProjectId = '';
    public $filterUserId = '';
    public $filterStatus = '';

    public function mount()
    {
        $this->startDate = Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now('Asia/Jakarta')->endOfMonth()->format('Y-m-d');
    }

    public function updating($property)
    {
        if (in_array($property, ['startDate', 'endDate', 'filterProjectId', 'filterUserId', 'filterStatus'])) {
            $this->resetPage();
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $user = Auth::user();
        
        // 1. Prepare options for dropdowns based on role
        if ($user->hasAnyRole(['Superadmin', 'Manager'])) {
            $projectOptions = Project::orderBy('name')->get();
            $userOptions = User::orderBy('name')->get();
        } elseif ($user->hasRole('Team Leader')) {
            $projectOptions = Project::whereHas('tasks.users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->orderBy('name')->get();

            $projectIds = $projectOptions->pluck('id');
            
            $userOptions = User::whereHas('tasks', function($q) use ($projectIds) {
                $q->whereIn('project_id', $projectIds);
            })->orderBy('name')->get();
        } else {
            // Regular Employee
            $projectOptions = Project::whereHas('tasks.users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->orderBy('name')->get();
            
            $userOptions = collect([$user]); // Can only filter by themselves
        }

        // 2. Query logs
        $query = DailyLog::with(['user', 'task.project'])->orderBy('log_date', 'desc');

        if (!$user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader'])) {
            // Employee can only see their own logs
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('Team Leader')) {
            // Team Leader can see logs for projects they are involved in
            $query->whereHas('task.project', function($q) use ($user) {
                $q->whereHas('tasks.users', function($q2) use ($user) {
                    $q2->where('users.id', $user->id);
                });
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('log_date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('log_date', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('log_date', '<=', $this->endDate);
        }

        if ($this->filterProjectId) {
            $query->whereHas('task', function($q) {
                $q->where('project_id', $this->filterProjectId);
            });
        }

        if ($this->filterUserId) {
            $query->where('user_id', $this->filterUserId);
        }

        if ($this->filterStatus) {
            $query->where('approval_status', $this->filterStatus);
        }

        // Force user filter for regular employees to prevent bypassing via property manipulation
        if (!$user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader'])) {
            $query->where('user_id', $user->id);
        }

        return view('operations::livewire.daily-log-history', [
            'logs' => $query->paginate(15),
            'projectOptions' => $projectOptions,
            'userOptions' => $userOptions,
        ]);
    }
}
