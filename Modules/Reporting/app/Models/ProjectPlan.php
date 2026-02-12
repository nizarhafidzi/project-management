<?php

namespace Modules\Reporting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Project\Models\Project;

class ProjectPlan extends Model
{
    protected $fillable = [
        'project_id',
        'period_date',
        'planned_progress',
        'actual_progress',
    ];

    protected function casts(): array
    {
        return [
            'period_date' => 'date',
            'planned_progress' => 'decimal:2',
            'actual_progress' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
