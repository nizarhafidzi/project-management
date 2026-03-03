<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Operations\Models\DailyLog;

class ApprovalManager extends Component
{
    public string $rejectionReason = '';

    /**
     * Approve a backdate request — marks log as approved and applies progress to task.
     */
    public function approve(int $logId): void
    {
        $log = DailyLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'approved',
        ]);

        // Apply the progress increment to the linked task upon approval
        if ($log->progress_increment > 0) {
            $task = $log->task;
            if ($task) {
                $task->total_progress = min(100, $task->total_progress + $log->progress_increment);
                $task->save();
                $task->recalculateProgress();
            }
        }

        session()->flash('message', 'Backdate log approved successfully. Progress has been applied.');
    }

    /**
     * Reject a backdate request — marks log as rejected with a reason.
     */
    public function reject(int $logId): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:3|max:500',
        ]);

        $log = DailyLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->rejectionReason = '';
        session()->flash('message', 'Backdate log rejected.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = DailyLog::with(['user', 'task.project'])
            ->pendingApproval()
            ->orderBy('log_date', 'desc');

        if (auth()->check() && auth()->user()->hasRole('Employee')) {
            $query->where('user_id', auth()->id());
        }

        $pendingLogs = $query->get();


        $recentDecisions = DailyLog::with(['user', 'task.project'])
            ->where('is_backdate', true)
            ->whereIn('approval_status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('operations::livewire.approval-manager', [
            'pendingLogs' => $pendingLogs,
            'recentDecisions' => $recentDecisions,
        ]);
    }
}
