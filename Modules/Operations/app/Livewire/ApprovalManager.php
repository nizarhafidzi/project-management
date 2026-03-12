<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Operations\Models\DailyLog;

class ApprovalManager extends Component
{
    public string $rejectionReason = '';

    public function mount()
    {
        abort_if(!auth()->user()->hasAnyRole(['Superadmin', 'Manager', 'Team Leader']), 403);
    }

    public function canApproveLog($logId)
    {
        $user = auth()->user();
        if ($user->hasAnyRole(['Superadmin', 'Manager'])) {
            return true;
        }

        $log = DailyLog::find($logId);
        if (!$log) {
            return false;
        }

        // Detect whether this is a Backdate (log_date is less than the record's creation date startOfDay)
        // Also respect the existing is_backdate flag on DailyLog if present.
        $isBackdate = \Carbon\Carbon::parse($log->log_date)->lt(\Carbon\Carbon::parse($log->created_at)->startOfDay()) || $log->is_backdate;

        if ($user->hasRole('Team Leader') && $isBackdate) {
            return false; // Team Leader cannot approve Backdate
        }

        return true;
    }

    /**
     * Approve a request — marks log as approved and applies progress to task.
     */
    public function approve(int $logId): void
    {
        abort_if(!$this->canApproveLog($logId), 403);

        $log = DailyLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'approved',
        ]);

        // Apply the progress increment to the linked task upon approval
        if ($log->progress_increment > 0) {
            $task = $log->task;
            if ($task) {
                $newProgress = $task->total_progress + $log->progress_increment;

                if ($newProgress >= 100) {
                    $task->total_progress = 100;
                    $task->status = 'Completed';
                } else {
                    $task->total_progress = $newProgress;
                }
                
                $task->save();
                
                if (method_exists($task, 'recalculateProgress')) {
                    $task->recalculateProgress();
                }
            }
        }

        session()->flash('message', 'Log approved successfully. Progress has been applied.');
    }

    /**
     * Reject a request — marks log as rejected with a reason.
     */
    public function reject(int $logId): void
    {
        abort_if(!$this->canApproveLog($logId), 403);

        $this->validate([
            'rejectionReason' => 'required|string|min:3|max:500',
        ]);

        $log = DailyLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->rejectionReason = '';
        session()->flash('message', 'Log rejected.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = DailyLog::with(['user', 'task.project'])
            ->pendingApproval()
            ->orderBy('log_date', 'desc');

        if (auth()->check() && auth()->user()->hasRole('Team Leader') && !auth()->user()->hasAnyRole(['Superadmin', 'Manager'])) {
            $query->whereHas('task.project.tasks.users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }

        $pendingLogs = $query->get();


        $recentDecisionsQuery = DailyLog::with(['user', 'task.project'])
            ->whereIn('approval_status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take(10);
            
        if (auth()->check() && auth()->user()->hasRole('Team Leader') && !auth()->user()->hasAnyRole(['Superadmin', 'Manager'])) {
            $recentDecisionsQuery->whereHas('task.project.tasks.users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }

        $recentDecisions = $recentDecisionsQuery->get();

        return view('operations::livewire.approval-manager', [
            'pendingLogs' => $pendingLogs,
            'recentDecisions' => $recentDecisions,
        ]);
    }
}
