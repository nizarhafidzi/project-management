<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Modules\Project\Enums\ProjectType;
use Modules\Project\Enums\TechnicalService;
use Modules\Project\Enums\ProjectSector;
use Modules\Project\Enums\ProjectStatus;
use Modules\Reporting\Models\ProjectPlan;

class Project extends Model
{
    protected $fillable = [
        'project_type',
        'contract_number',
        'project_code',
        'name',
        'technical_service',
        'sector',
        'acc_project_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'project_type' => ProjectType::class,
            'technical_service' => TechnicalService::class,
            'sector' => ProjectSector::class,
            'status' => ProjectStatus::class,
        ];
    }

    /**
     * All users assigned to this project.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role_in_project')
            ->withTimestamps();
    }

    /**
     * Users with 'Manager' role in this project.
     */
    public function managers(): BelongsToMany
    {
        return $this->users()->wherePivot('role_in_project', 'Manager');
    }

    /**
     * Users with 'Team Leader' role in this project.
     */
    public function teamLeaders(): BelongsToMany
    {
        return $this->users()->wherePivot('role_in_project', 'Team Leader');
    }

    /**
     * Users with 'Member' role in this project.
     */
    public function members(): BelongsToMany
    {
        return $this->users()->wherePivot('role_in_project', 'Member');
    }

    /**
     * Generate a project code based on type and contract number.
     * Format: YYYY-TYPE-SEQ (e.g., 2026-INT-001)
     */
    public static function generateProjectCode(ProjectType $type, string $contractNumber): string
    {
        $year = date('Y');
        $typeCode = $type->code();

        // Count existing projects of this type in the current year
        $count = static::where('project_type', $type->value)
            ->where('project_code', 'LIKE', "{$year}-{$typeCode}-%")
            ->count();

        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "{$year}-{$typeCode}-{$sequence}";
    }

    /**
     * All tasks belonging to this project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Root-level tasks (no parent) for this project.
     */
    public function rootTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * Planned progress records for this project.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(ProjectPlan::class);
    }

    /**
     * Calculate total cumulative progress for the entire project.
     * Uses coefficient-adjusted weighted average of root-level tasks.
     *
     * Formula: P_total = Σ(P_root × W_effective) / Σ(W_effective)
     * Where W_effective = weight × coefficient
     */
    public function calculateTotalProgress(): float
    {
        $rootTasks = $this->rootTasks()->with('children')->get();

        if ($rootTasks->isEmpty()) {
            return 0;
        }

        $weightedSum = 0;
        $totalEffectiveWeight = 0;

        foreach ($rootTasks as $task) {
            $progress = $task->calculateProgressRollup();
            $effectiveWeight = (float) $task->weight * (float) $task->coefficient;
            $weightedSum += $progress * $effectiveWeight;
            $totalEffectiveWeight += $effectiveWeight;
        }

        if ($totalEffectiveWeight <= 0) {
            return 0;
        }

        return round($weightedSum / $totalEffectiveWeight, 2);
    }
}
