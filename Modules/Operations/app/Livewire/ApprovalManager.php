<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Operations\Models\DailyLog;

class ApprovalManager extends Component
{
    public string $rejectionReason = '';

    // Modals state for revision
    public bool $isRejectModalOpen = false;
    public ?int $rejectLogId = null;
    public int $revisedProgress = 0;
    public string $rejectReason = '';

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
     * Open the reject modal for a specific log and initialize its progress.
     */
    public function openRejectModal(int $logId): void
    {
        abort_if(!$this->canApproveLog($logId), 403);

        $log = DailyLog::findOrFail($logId);
        $this->rejectLogId = $logId;
        // Default to the task's current total progress, or 0 if not set
        $this->revisedProgress = $log->task ? $log->task->total_progress : 0;
        $this->rejectReason = '';
        $this->isRejectModalOpen = true;
    }

    /**
     * Confirm rejection and revise task progress.
     */
    public function confirmReject(): void
    {
        abort_if(!$this->canApproveLog($this->rejectLogId), 403);

        $this->validate([
            'revisedProgress' => 'required|numeric|min:0|max:100',
            'rejectReason' => 'required|string|min:3|max:500',
        ]);

        $log = DailyLog::findOrFail($this->rejectLogId);
        $task = $log->task;
        $revisedProgressInt = (int) $this->revisedProgress;

        if ($task) {
            if ($revisedProgressInt < $task->total_progress) {
                // Give an error/flash message: Revised progress cannot be lower than previous
                session()->flash('error', "Revised progress cannot be lower than the previously approved progress ({$task->total_progress}%).");
                return;
            }

            // Calculate the incremental progress that supervisor allows
            $allowedIncrement = $revisedProgressInt - $task->total_progress;

            // Instead of saving it as rejected, save it as approved
            $log->progress_increment = $allowedIncrement;
            $log->approval_status = 'approved';
            $log->rejection_reason = $this->rejectReason; // keep track of the reason
            $log->notes = "[REVISED by Leader] Claimed 100%, Approved as " . $revisedProgressInt . "% | Reason: " . $this->rejectReason . "\n---\n" . ($log->notes ?? '');
            $log->save();

            // Update & Recalculate Task
            $task->total_progress = $revisedProgressInt;
            if ($revisedProgressInt >= 100) {
                $task->status = 'Completed';
            } elseif ($revisedProgressInt < 100 && $task->status === 'Completed') {
                $task->status = 'In Progress'; // or whatever the active status is
            }
            $task->save();

            // Call recalculate if it exists
            if (method_exists($task, 'recalculateProgress')) {
                $task->recalculateProgress();
            }
        } else {
            // Fallback if there is no task attached to the log
            $log->progress_increment = $revisedProgressInt;
            $log->approval_status = 'approved';
            $log->rejection_reason = $this->rejectReason;
            $log->notes = "[REVISED by Leader] Claimed 100%, Approved as " . $revisedProgressInt . "% | Reason: " . $this->rejectReason . "\n---\n" . ($log->notes ?? '');
            $log->save();
        }

        $this->isRejectModalOpen = false;
        $this->rejectLogId = null;
        $this->rejectReason = '';
        
        session()->flash('message', 'Log revised and approved successfully.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = DailyLog::with(['user', 'task.project'])
            ->pendingApproval()
            ->orderBy('log_date', 'desc');

        if (auth()->check() && auth()->user()->hasRole('Team Leader') && !auth()->user()->hasAnyRole(['Superadmin', 'Manager'])) {
            $query->whereHas('task.project.users', function ($q) {
                $q->where('users.id', auth()->id());
            });
        }

        $pendingLogs = $query->get();


        $recentDecisionsQuery = DailyLog::with(['user', 'task.project'])
            ->whereIn('approval_status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take(10);
            
        if (auth()->check() && auth()->user()->hasRole('Team Leader') && !auth()->user()->hasAnyRole(['Superadmin', 'Manager'])) {
            $recentDecisionsQuery->whereHas('task.project.users', function ($q) {
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
