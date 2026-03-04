<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;
use Modules\Operations\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class ExecutiveDashboard extends Component
{
    public $totalProjects;
    public $activeProjects;
    public $averageProgress;
    public $totalManHours;
    public $syncAlerts;
    public $recentActivities;
    public $manHourTrend = [];
    public $projectStatusData = [];
    public $projectsList;

    public function mount()
    {
        $user = Auth::user();
        $isGlobal = $user->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']);

        // 1. Determine Project Scope
        $projectQuery = Project::query();
        if (!$isGlobal) {
            $projectQuery->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // 2. Calculate KPIs
        $projects = $projectQuery->with(['tasks', 'rootTasks.childrenRecursive'])->get();
        
        $this->totalProjects = $projects->count();
        $this->activeProjects = $projects->where('status', \Modules\Project\Enums\ProjectStatus::Active)->count();
        
        // Calculate progress for each project
        // Note: total_progress is not a column, so we calculate it.
        // We assign it to the model instance so it can be used in the view and for average.
        /** @var \Modules\Project\Models\Project $project */
        foreach ($projects as $project) {
            $project->total_progress = $project->calculateTotalProgress();
        }

        // Average Progress
        $this->averageProgress = $projects->count() > 0 
            ? round($projects->avg('total_progress'), 2) 
            : 0;
            
        // Calculate Total Man-Hours calculation remains the same

        // We need to query DailyLogs based on tasks belonging to these projects.
        // Doing this via relationship might be heavy if not eager loaded, but let's try efficient query.
        $projectIds = $projects->pluck('id');
        
        $this->totalManHours = DailyLog::whereHas('task', function($q) use ($projectIds) {
                $q->whereIn('project_id', $projectIds);
            })
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->get()
            ->reduce(function ($carry, $log) {
                return $carry + $log->man_hours; // Uses safe accessor with cross-midnight handling
            }, 0);

        $this->totalManHours = round($this->totalManHours, 2);

        // Sync Alerts: Count tasks where acc_file_version < acc_latest_version
        $this->syncAlerts = \Modules\Project\Models\Task::whereIn('project_id', $projectIds)
            ->whereNotNull('acc_file_urn')
            ->whereColumn('acc_file_version', '<', 'acc_latest_version')
            ->count();

        // 3. Recent Activities (Feed)
        // Global or Scoped
        $logQuery = DailyLog::with(['user', 'task.project'])
            ->whereNotNull('progress_increment') // only progress updates? or all logs? Prompt says "work logs".
            ->latest('created_at')
            ->limit(10);
            
        if (!$isGlobal) {
            $logQuery->whereHas('task.project.users', function($q) use ($user) {
                 $q->where('user_id', $user->id);
            });
             // A better check: Logs where task's project is in user's projects.
             // OR Logs created by user? "Display ... accessible to users".
             // If I am employee on Project A, I should see logs from other members on Project A?
             // Prompt: "activities accessible to users". Usually implies project contextual visibility.
             // Simplified: Logs linked to tasks in $projectIds.
             $logQuery->whereHas('task', function($q) use ($projectIds) {
                 $q->whereIn('project_id', $projectIds);
             });
        }
        
        $this->recentActivities = $logQuery->get();

        // 4. Charts Data
        // Man-Hour Trend (Last 7 days? or Weekly?) -> "Weekly team productivity"
        // Let's do daily man-hours for last 7 days for the chart.
        $this->manHourTrend = $this->getManHourTrend($projectIds);

        // Project Status Distribution
        $this->projectStatusData = [
            'Active' => $projects->where('status', \Modules\Project\Enums\ProjectStatus::Active)->count(),
            'Completed' => $projects->where('status', \Modules\Project\Enums\ProjectStatus::Completed)->count(),
            'OnHold' => $projects->where('status', \Modules\Project\Enums\ProjectStatus::OnHold)->count(), // Assuming OnHold exists in enum, need to check.
             // If Enum backing values are strings, we count specific values.
             // I'll check enum later or just group by status value.
        ];
        // Dynamic Group By
        $this->projectStatusData = $projects->countBy(fn($project) => $project->status->value)->toArray();
        
        // 5. Quick Links
        $this->projectsList = $projects->take(10); // Limit to 10 for list
    }

    private function getManHourTrend($projectIds)
    {
        // Get logs for last 7 days
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        
        $logs = DailyLog::whereHas('task', function($q) use ($projectIds) {
                $q->whereIn('project_id', $projectIds);
            })
            ->whereDate('log_date', '>=', $startDate)
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->get();
            
        $trend = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->toDateString();
            $dayLogs = $logs->where('log_date', '>=', $date . ' 00:00:00')
                            ->where('log_date', '<=', $date . ' 23:59:59'); // strict usage of log_date column if it's date only
                            // Actually log_date is 'date' cast.
                            
            // Filter by date string matching
            $daySum = $logs->filter(function($log) use ($date) {
                return $log->log_date && $log->log_date->toDateString() === $date;
            })->reduce(function ($carry, $log) {
                return $carry + $log->man_hours; // Uses safe accessor with cross-midnight handling
            }, 0);
            
            $trend['labels'][] = Carbon::parse($date)->format('D, M j');
            $trend['data'][] = round($daySum, 2);
        }
        
        return $trend;
    }

    public function render()
    {
        return view('project::livewire.executive-dashboard');
    }
}
