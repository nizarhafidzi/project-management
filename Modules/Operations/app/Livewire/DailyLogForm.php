<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Operations\Models\DailyLog;
use Modules\Project\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyLogForm extends Component
{
    public string $todayDate = '';
    public string $currentTime = '';

    public $taskId = '';
    public $clockIn = '';
    public $clockOut = '';
    public $progressIncrement = 0;
    public bool $isBackdateMode = false;
    public string $backdateDate = '';

    public $myTasks = [];

    protected function rules(): array
    {
        $rules = [
            'taskId' => 'required|exists:tasks,id',
            'progressIncrement' => 'required|numeric|min:0|max:100',
        ];

        if ($this->isBackdateMode) {
            $rules['backdateDate'] = 'required|date|before:today';
            $rules['clockIn'] = 'required';
            $rules['clockOut'] = 'required';
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->todayDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->currentTime = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->backdateDate = Carbon::yesterday('Asia/Jakarta')->format('Y-m-d');

        $this->loadMyTasks();
    }

    public function loadMyTasks(): void
    {
        $user = Auth::user();

        $this->myTasks = Task::with('project')
            ->whereHas('project', function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            // Strict filtering: User can ONLY see tasks assigned to them
            ->where('user_id', $user->id)
            ->take(50)
            ->get();
    }

    /**
     * Clock In — Creates a new daily log entry for today with the current system time.
     */
    public function clockIn(): void
    {
        $this->validate([
            'taskId' => 'required|exists:tasks,id',
        ]);

        // Security Hardening: Ensure task is assigned to current user
        abort_if(! $this->validateTaskOwnership($this->taskId), 403, 'Unauthorized action.');

        $now = Carbon::now('Asia/Jakarta');

        DailyLog::create([
            'user_id' => Auth::id(),
            'task_id' => $this->taskId,
            'log_date' => $now->toDateString(),
            'clock_in' => $now->toTimeString(),
            'is_backdate' => false,
            'approval_status' => 'approved',
        ]);

        session()->flash('message', 'Clocked In Successfully at ' . $now->format('H:i'));
    }

    /**
     * Clock Out — Records the end time on an existing log.
     */
    public function clockOut(int $logId): void
    {
        $log = DailyLog::where('user_id', Auth::id())->find($logId);

        if ($log) {
            $now = Carbon::now('Asia/Jakarta');
            $log->update([
                'clock_out' => $now->toTimeString(),
            ]);
            session()->flash('message', 'Clocked Out Successfully at ' . $now->format('H:i'));
        }
    }

    /**
     * Save Progress — Records progress increment and updates the linked task.
     */
    public function saveProgress(int $logId): void
    {
        $this->validate([
            'progressIncrement' => 'required|numeric|min:0|max:100',
        ]);

        $log = DailyLog::where('user_id', Auth::id())->find($logId);

        if ($log && $log->isApproved()) {
            $log->update([
                'progress_increment' => $this->progressIncrement,
            ]);

            // Update the task's cumulative progress
            $task = $log->task;
            if ($task) {
                $task->total_progress = min(100, $task->total_progress + $this->progressIncrement);
                $task->save();
                $task->recalculateProgress();
            }

            $this->progressIncrement = 0;
            session()->flash('message', 'Progress Saved Successfully.');
        }
    }

    /**
     * Submit Backdate — Creates a backdate request that requires Manager approval.
     * The model's boot() method auto-sets is_backdate=true and approval_status=pending.
     */
    public function submitBackdate(): void
    {
        $this->validate([
            'taskId' => 'required|exists:tasks,id',
            'backdateDate' => 'required|date|before:today',
            'clockIn' => 'required',
            'clockOut' => 'required',
            'progressIncrement' => 'required|numeric|min:0|max:100',
        ]);


        // Security Hardening: Ensure task is assigned to current user
        abort_if(! $this->validateTaskOwnership($this->taskId), 403, 'Unauthorized action.');

        DailyLog::create([
            'user_id' => Auth::id(),
            'task_id' => $this->taskId,
            'log_date' => $this->backdateDate,
            'clock_in' => $this->clockIn,
            'clock_out' => $this->clockOut,
            'progress_increment' => $this->progressIncrement,
            'is_backdate' => true,
            'approval_status' => 'pending',
        ]);

        session()->flash('message', 'Backdate Request Submitted for Manager Approval.');
        $this->reset(['taskId', 'clockIn', 'clockOut', 'progressIncrement']);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $todaysLogs = DailyLog::with('task')
            ->where('user_id', Auth::id())
            ->where('log_date', $this->todayDate)
            ->approved()
            ->latest()
            ->get();

        $myBackdateRequests = DailyLog::with('task')
            ->where('user_id', Auth::id())
            ->where('is_backdate', true)
            ->latest()
            ->take(10)
            ->get();

        return view('operations::livewire.daily-log-form', [
            'todaysLogs' => $todaysLogs,
            'myBackdateRequests' => $myBackdateRequests,
        ]);
    }

    private function validateTaskOwnership($taskId): bool
    {
        $task = Task::find($taskId);
        return $task && $task->user_id === Auth::id();
    }
}
