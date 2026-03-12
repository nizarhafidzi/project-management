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
    // ──────────────────────────────────────────────
    // Public Properties
    // ──────────────────────────────────────────────

    public string $todayDate = '';
    public string $currentTime = '';

    // Form Fields
    public $taskId = '';
    public $clockIn = '';
    public $clockOut = '';
    public $progressIncrement = 0;
    public $notes = '';
    
    // Backdate State
    public bool $isBackdateMode = false;
    public string $backdateDate = '';

    // Stateful Clock-In tracking
    public $activeLogId = null;
    public $activeClockInTime = null;
    public $activeTaskName = null;
    public $activeLogDate = null;

    public $myTasks = [];    // ──────────────────────────────────────────────
    // Validation Rules
    // ──────────────────────────────────────────────

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
            $rules['notes'] = 'required|string|min:3';
        }

        return $rules;
    }

    // ──────────────────────────────────────────────
    // Lifecycle: mount()
    // ──────────────────────────────────────────────

    public function mount(): void
    {
        $this->todayDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->currentTime = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->backdateDate = Carbon::yesterday('Asia/Jakarta')->format('Y-m-d');

        $this->loadMyTasks();

        // STATE EVALUATION: Check for any active (unclosed) log — not limited to today
        $activeLog = DailyLog::where('user_id', Auth::id())
            ->whereNull('clock_out')
            ->first();

        if ($activeLog) {
            $this->activeLogId = $activeLog->id;
            $this->taskId = $activeLog->task_id;
            $this->activeLogDate = $activeLog->log_date;
            $this->activeClockInTime = Carbon::parse($activeLog->clock_in)->format('H:i');
            $this->activeTaskName = $activeLog->task ? $activeLog->task->name : 'Unknown Task';
        }
    }

    // ──────────────────────────────────────────────
    // Load Tasks
    // ──────────────────────────────────────────────

    public function loadMyTasks(): void
    {
        $user = Auth::user();

        // Only show actionable tasks assigned to this user
        $this->myTasks = Task::with('project')
            ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->whereDoesntHave('children')
            ->take(50)
            ->get();
    }

    // ──────────────────────────────────────────────
    // STATE 1 → Clock In (Morning)
    // ──────────────────────────────────────────────

    /**
     * Start Clock In — Creates a new daily log entry for today.
     * Rewritten to bypass Livewire's complex validation engine which is causing silent failures.
     */
    public function startClockIn(): void
    {
        if (empty($this->taskId)) {
            session()->flash('error', 'Please select a task from the dropdown first.');
            return;
        }

        abort_if(! $this->validateTaskOwnership($this->taskId), 403, 'Unauthorized action. You are not assigned to this task.');

        if ($this->activeLogId) {
            session()->flash('error', 'You already have an active clock-in session.');
            return;
        }

        try {
            $now = Carbon::now('Asia/Jakarta');

            $log = DailyLog::create([
                'user_id'            => Auth::id(),
                'task_id'            => $this->taskId,
                'log_date'           => $now->toDateString(),
                'clock_in'           => $now->toTimeString(),
                'clock_out'          => null, // Not yet clocked out
                'progress_increment' => 0,
                'notes'              => null,
                'is_backdate'        => false,
                'approval_status'    => 'draft', // Not yet completed
            ]);

            // Transition UI to STATE 2
            $this->activeLogId = $log->id;
            $this->activeClockInTime = $now->format('H:i');
            
            $taskName = Task::find($this->taskId)->name ?? 'Unknown Task';
            $this->activeTaskName = $taskName;

            session()->flash('message', 'Clocked In Successfully at ' . $now->format('H:i') . ' WIB.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'System Error: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────
    // STATE 2 → Clock Out (Afternoon/Evening)
    // ──────────────────────────────────────────────

    /**
     * Clock Out — Records the end time, user progress, notes, and auto-approves.
     */
    public function startClockOut(): void
    {
        // Force typecast because Livewire might pass empty inputs as empty strings
        if ($this->progressIncrement === '' || $this->progressIncrement === null) {
            $this->progressIncrement = 0;
        }

        if (!is_numeric($this->progressIncrement) || $this->progressIncrement < 0 || $this->progressIncrement > 100) {
            session()->flash('error', 'Progress must be a number between 0 and 100.');
            return;
        }

        if (empty(trim($this->notes)) || strlen(trim($this->notes)) < 3) {
            session()->flash('error', 'Notes / Description must be at least 3 characters long.');
            return;
        }

        try {
            $log = DailyLog::where('user_id', Auth::id())->find($this->activeLogId);

            if (!$log) {
                session()->flash('error', 'Active log not found.');
                $this->resetFormState();
                return;
            }

            $now = Carbon::now('Asia/Jakarta');
            $logDate = Carbon::parse($log->log_date)->startOfDay();
            $today = Carbon::today('Asia/Jakarta');

            // Determine clock_out time: if overdue (past day), hardcode 17:00
            $isOverdue = $logDate->lt($today);

            if ($isOverdue) {
                $clockOutTime = '17:00:00';
                // Append late clock-out annotation to notes
                $this->notes = $this->notes . "\n[Late Clock-Out: Auto-set to 17:00]";
            } else {
                $clockOutTime = $now->toTimeString();
            }

            $approvalStatus = 'approved';
            $progressToSave = $this->progressIncrement;

            if ($log->task) {
                $projectedProgress = (float)$log->task->total_progress + (float)$this->progressIncrement;
                if ($projectedProgress >= 100) {
                    $progressToSave = max(0, 100 - $log->task->total_progress);
                    $approvalStatus = 'pending';
                    $this->notes = "[FINAL REVIEW] " . ltrim($this->notes);
                }
            }

            // Update the log with clock out time and auto-approve (or pending if 100%)
            $log->update([
                'clock_out'          => $clockOutTime,
                'progress_increment' => $progressToSave,
                'notes'              => $this->notes,
                'approval_status'    => $approvalStatus,
            ]);

            // Update total progress in the Tasks table ONLY if approved
            if ($log->task && $approvalStatus === 'approved') {
                $log->task->total_progress = min(100, $log->task->total_progress + $progressToSave);
                $log->task->save();
                if (method_exists($log->task, 'recalculateProgress')) {
                    $log->task->recalculateProgress();
                }
            }

            session()->flash('message', 'Clocked Out Successfully. Progress saved.');

            // Return to STATE 1
            $this->resetFormState();
            
        } catch (\Exception $e) {
            session()->flash('error', 'System Error: ' . $e->getMessage());
        }
    }

    private function resetFormState(): void
    {
        $this->reset(['activeLogId', 'activeClockInTime', 'activeTaskName', 'activeLogDate', 'taskId', 'progressIncrement', 'notes']);
        $this->resetValidation();
    }

    // ──────────────────────────────────────────────
    // BACKDATE MODE (Correct exactly as requested)
    // ──────────────────────────────────────────────

    public function submitBackdate(): void
    {
        $this->validate([
            'taskId'            => 'required|exists:tasks,id',
            'backdateDate'      => 'required|date|before:today',
            'clockIn'           => 'required',
            'clockOut'          => ['required', function ($attribute, $value, $fail) {
                if (!empty($this->clockIn) && strtotime($value) <= strtotime($this->clockIn)) {
                    $fail('Clock Out time must be after Clock In time.');
                }
            }],
            'progressIncrement' => 'required|numeric|min:0|max:100',
            'notes'             => 'required|string|min:3',
        ]);

        abort_if(! $this->validateTaskOwnership($this->taskId), 403, 'Unauthorized action.');

        $progressToSave = $this->progressIncrement;
        $task = Task::find($this->taskId);
        
        if ($task) {
            $projectedProgress = (float)$task->total_progress + (float)$this->progressIncrement;
            if ($projectedProgress >= 100) {
                $progressToSave = max(0, 100 - $task->total_progress);
                $this->notes = "[FINAL REVIEW] " . ltrim($this->notes);
            }
        }

        DailyLog::create([
            'user_id'            => Auth::id(),
            'task_id'            => $this->taskId,
            'log_date'           => $this->backdateDate,
            'clock_in'           => $this->clockIn,
            'clock_out'          => $this->clockOut,
            'progress_increment' => $progressToSave,
            'notes'              => $this->notes,
            'is_backdate'        => true,
            'approval_status'    => 'pending',
        ]);

        session()->flash('message', 'Backdate Request Submitted for Manager Approval.');
        $this->reset(['taskId', 'clockIn', 'clockOut', 'progressIncrement', 'notes']);
        $this->resetValidation();
    }

    // ──────────────────────────────────────────────
    // Render
    // ──────────────────────────────────────────────

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
            'todaysLogs'        => $todaysLogs,
            'myBackdateRequests' => $myBackdateRequests,
        ]);
    }

    private function validateTaskOwnership($taskId): bool
    {
        $task = Task::whereHas('users', fn($q) => $q->where('users.id', (int) Auth::id()))->find($taskId);
        return $task !== null;
    }
}

