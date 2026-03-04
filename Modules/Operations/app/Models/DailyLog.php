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

    public function scopeDraft($query)
    {
        return $query->where('approval_status', 'draft');
    }

    // ──────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────

    /**
     * Calculate Man-Hours safely with cross-midnight handling.
     * Returns hours as a float, always >= 0.
     */
    public function getManHoursAttribute(): float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0.0;
        }

        $dateStr = $this->log_date
            ? Carbon::parse($this->log_date)->format('Y-m-d')
            : Carbon::today('Asia/Jakarta')->format('Y-m-d');

        $in = Carbon::parse($dateStr . ' ' . Carbon::parse($this->clock_in)->format('H:i:s'));
        $out = Carbon::parse($dateStr . ' ' . Carbon::parse($this->clock_out)->format('H:i:s'));

        // Cross-midnight handling: if clock_out is before clock_in, assume next day
        if ($out->lessThan($in)) {
            $out->addDay();
        }

        return max(0, $in->diffInMinutes($out) / 60);
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
