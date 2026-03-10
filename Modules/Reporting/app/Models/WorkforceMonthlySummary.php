<?php

namespace Modules\Reporting\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkforceMonthlySummary extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_working_days',
        'attended_days',
        'absent_days',
        'total_utilization',
        'project_details',
    ];

    protected function casts(): array
    {
        return [
            'project_details' => 'array',
            'total_utilization' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
