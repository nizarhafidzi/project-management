<?php

namespace Modules\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DailyLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'log_date',
        'clock_in',
        'clock_out',
        'progress_increment',
        'is_backdate',
        'approval_status',
        'rejection_reason',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'progress_increment' => 'decimal:2',
            'is_backdate' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────
    // Boot — Anti-Backdate Validation
    // ──────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (DailyLog $log) {
            $today = Carbon::now('Asia/Jakarta')->toDateString();

            // If the log_date is in the past, enforce backdate flag & pending status
            if ($log->log_date && Carbon::parse($log->log_date)->toDateString() < $today) {
                $log->is_backdate = true;
                $log->approval_status = 'pending';
            }
        });
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(\Modules\Project\Models\Task::class);
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function scopeMyLogs($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }
}
