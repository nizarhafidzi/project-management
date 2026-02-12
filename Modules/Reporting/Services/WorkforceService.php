<?php

namespace Modules\Reporting\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Project\Models\Project;

class WorkforceService
{
    // Threshold constants for utilization status (Based on C = 1/N)
    const STATUS_IDEAL = 1.0;
    const STATUS_MODERATE_MIN = 0.5;
    // Overload is anything less than MODERATE_MIN (0.5), meaning N > 2.

    /**
     * Get staff utilization data, optionally filtered by sector.
     *
     * @param string|null $sector
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStaffUtilization(?string $sector = null): Collection
    {
        // 1. Query Users
        $query = User::query()
            ->with(['projects' => function ($query) {
                $query->select('projects.id', 'projects.name', 'projects.sector', 'projects.project_code', 'projects.technical_service', 'projects.status');
            }]);

        // 2. Filter by Sector if provided
        if ($sector && $sector !== 'All') {
            $query->whereHas('projects', function (Builder $q) use ($sector) {
                $q->where('sector', $sector);
            });
        } else {
             $query->whereHas('projects');
        }

        $users = $query->get();

        // 3. Process each user to add utilization metrics
        return $users->map(function ($user) {
            // Count active projects
            $activeProjectsCount = $user->projects->where('status', '!=', 'Completed')->count();
            
            $user->active_projects_count = $activeProjectsCount;

            // Calculate Coefficient C = 1 / N
            if ($activeProjectsCount > 0) {
                $user->utilization_coefficient = round(1 / $activeProjectsCount, 2);
            } else {
                $user->utilization_coefficient = 0.00;
            }

            $user->utilization_status = $this->determineStatus($user->utilization_coefficient);
            
            return $user;
        });
    }

    /**
     * Determine the utilization status based on coefficient.
     *
     * @param float $coefficient
     * @return string 'Ideal', 'Moderate', 'Overload'
     */
    private function determineStatus(float $coefficient): string
    {
        // Ideal: Exactly 1.0 (N=1)
        if ($coefficient >= self::STATUS_IDEAL) {
            return 'Ideal';
        }
        
        // Moderate: 0.5 to < 1.0 (N=2 implies 0.5)
        if ($coefficient >= self::STATUS_MODERATE_MIN) {
            return 'Moderate';
        }

        // Overload: < 0.5 (N >= 3)
        return 'Overload';
    }

    /**
     * Get active projects for a specific user, for the modal breakdown.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProjectBreakdown(int $userId): Collection
    {
        $user = User::with(['projects' => function ($query) {
            $query->where('status', '!=', 'Completed'); // Show active only
        }])->find($userId);

        return $user ? $user->projects : collect([]);
    }
}
