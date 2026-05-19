<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Operations\Models\DailyLog;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'parent_id',
        'wbs_code',
        'name',
        'description',
        'categories',
        'discipline',
        'acc_file_name',
        'acc_file_urn',
        'acc_file_version',
        'acc_latest_version',
        'acc_last_synced_at',
        'weight',
        'coefficient',
        'start_date',
        'end_date',
        'actual_start_date',
        'actual_end_date',
        'status',
        'total_progress',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'coefficient' => 'decimal:4',
            'total_progress' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'actual_start_date' => 'date',
            'actual_end_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Recursive eager-loadable children for tree building.
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Daily logs associated with this task.
     */
    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class)->withTimestamps();
    }

    /**
     * Scope a query to only include tasks assigned to a specific user.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->whereHas('users', fn($q) => $q->where('users.id', $userId));
    }

    // ──────────────────────────────────────────────
    // WBS Code Generation
    // ──────────────────────────────────────────────

    /**
     * Generate the next sequential WBS code for a given parent context.
     *
     * Root-level examples: 1, 2, 3
     * Child-level examples: 1.1, 1.2 or 2.1, 2.2
     */
    public static function generateNextWbsCode(int $projectId, ?int $parentId = null): string
    {
        if ($parentId) {
            $parent = static::findOrFail($parentId);
            $lastChild = static::where('project_id', $projectId)
                ->where('parent_id', $parentId)
                ->orderByRaw("CAST(SUBSTRING_INDEX(wbs_code, '.', -1) AS UNSIGNED) DESC")
                ->first();

            if ($lastChild) {
                // Extract the last segment and increment
                $segments = explode('.', $lastChild->wbs_code);
                $lastSegment = (int) end($segments);
                return $parent->wbs_code . '.' . ($lastSegment + 1);
            }

            return $parent->wbs_code . '.1';
        }

        // Root level
        $lastRoot = static::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->orderByRaw("CAST(wbs_code AS UNSIGNED) DESC")
            ->first();

        return $lastRoot ? (string) ((int) $lastRoot->wbs_code + 1) : '1';
    }

    // ──────────────────────────────────────────────
    // Weight Validation
    // ──────────────────────────────────────────────

    /**
     * Get the sum of weights for all siblings (tasks sharing the same parent).
     */
    public static function getSiblingWeightSum(int $projectId, ?int $parentId = null): float
    {
        return (float) static::where('project_id', $projectId)
            ->where('parent_id', $parentId)
            ->sum('weight');
    }

    /**
     * Get the expected weight total for a sibling group.
     * Root tasks must sum to 100. Child tasks must sum to parent's weight.
     */
    public static function getExpectedWeight(int $projectId, ?int $parentId = null): float
    {
        if ($parentId) {
            $parent = static::find($parentId);
            return $parent ? (float) $parent->weight : 100.0;
        }

        return 100.0;
    }

    /**
     * Check if sibling weights are valid (sum equals expected weight).
     */
    public static function areSiblingWeightsValid(int $projectId, ?int $parentId = null): bool
    {
        $sum = static::getSiblingWeightSum($projectId, $parentId);
        $expected = static::getExpectedWeight($projectId, $parentId);

        return abs($sum - $expected) < 0.01; // floating point tolerance
    }

    // ──────────────────────────────────────────────
    // Progress Rollup
    // ──────────────────────────────────────────────

    /**
     * Calculate progress based on weighted children with coefficient adjustment.
     * Formula: P_parent = Σ(P_child × W_effective) / Σ(W_effective)
     * Where W_effective = weight × coefficient
     */
    public function calculateProgressRollup(): float
    {
        $children = $this->children;

        if ($children->isEmpty()) {
            return (float) $this->total_progress;
        }

        $weightedSum = 0;
        $totalEffectiveWeight = 0;

        foreach ($children as $child) {
            $childProgress = $child->calculateProgressRollup();
            $effectiveWeight = (float) $child->weight * (float) $child->coefficient;
            $weightedSum += $childProgress * $effectiveWeight;
            $totalEffectiveWeight += $effectiveWeight;
        }

        if ($totalEffectiveWeight <= 0) {
            return 0;
        }

        return round($weightedSum / $totalEffectiveWeight, 2);
    }

    /**
     * Recalculate and persist progress for this task and all ancestors.
     *
     * Leaf tasks: sum approved daily_log progress_increment values.
     * Parent tasks: weighted rollup from children.
     * Status is strictly synchronised — 'Completed' only when >= 100.
     */
    public function recalculateProgress(): void
    {
        if ($this->children()->exists()) {
            // ── Parent task: weighted rollup ──
            $this->total_progress = $this->calculateProgressRollup();
        } else {
            // ── Leaf task: authoritative sum from approved daily logs ──
            $sum = (float) $this->dailyLogs()
                ->where('approval_status', 'approved')
                ->sum('progress_increment');

            $this->total_progress = min($sum, 100);
        }

        // ── Status synchronisation ──
        if ($this->total_progress >= 100) {
            $this->total_progress = 100;

            // Only set Completed status if not already a Completed variant
            if (!str_starts_with($this->status ?? '', 'Completed')) {
                $plannedEnd = $this->end_date ? \Carbon\Carbon::parse($this->end_date)->startOfDay() : null;
                $actualEnd  = $this->actual_end_date
                    ? \Carbon\Carbon::parse($this->actual_end_date)->startOfDay()
                    : \Carbon\Carbon::today('Asia/Jakarta');

                if ($plannedEnd) {
                    if ($actualEnd->lt($plannedEnd)) {
                        $this->status = 'Completed (Ahead)';
                    } elseif ($actualEnd->eq($plannedEnd)) {
                        $this->status = 'Completed (On Time)';
                    } else {
                        $this->status = 'Completed (Late)';
                    }
                } else {
                    $this->status = 'Completed (On Time)';
                }
            }
        } else {
            // If still below 100, ensure the status is NOT 'Completed'
            if ($this->total_progress > 0) {
                $this->status = 'In Progress';
                $this->actual_end_date = null;
            }
        }

        $this->saveQuietly();

        // ── Propagate to parent ──
        if ($this->parent_id) {
            $this->parent->recalculateProgress();
        }
    }

    // ──────────────────────────────────────────────
    // Weight Rollup
    // ──────────────────────────────────────────────

    /**
     * Recalculate and persist weight for this task from its children's sum.
     * Recursively propagates upward to the root so the entire ancestor chain stays in sync.
     * Uses saveQuietly() to suppress model events and prevent infinite recursion.
     */
    public function recalculateWeight(): void
    {
        if ($this->children()->exists()) {
            $this->weight = $this->children()->sum('weight');
            $this->saveQuietly();
        }

        if ($this->parent_id) {
            $this->parent->recalculateWeight();
        }
    }
}
