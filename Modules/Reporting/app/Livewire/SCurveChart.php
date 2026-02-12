<?php

namespace Modules\Reporting\Livewire;

use Livewire\Component;
use Modules\Reporting\Models\ProjectPlan;
use Carbon\Carbon;

class SCurveChart extends Component
{
    public int $projectId;
    public string $period = 'weekly';

    public function mount(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function switchPeriod(string $period): void
    {
        $this->period = $period;
        $this->dispatch('chart-data-updated', data: $this->getChartData());
    }

    #[\Livewire\Attributes\On('project-plan-updated')]
    public function handleProjectPlanUpdated(): void
    {
        $this->dispatch('chart-data-updated', data: $this->getChartData());
    }

    public function getChartData(): array
    {
        $query = ProjectPlan::where('project_id', $this->projectId)
            ->orderBy('period_date');

        if ($this->period === 'monthly') {
            // Group by month — take the last entry of each month
            $plans = $query->get()
                ->groupBy(fn ($plan) => Carbon::parse($plan->period_date)->format('Y-m'))
                ->map(fn ($group) => $group->last());
        } else {
            $plans = $query->get();
        }

        $labels = [];
        $planned = [];
        $actual = [];

        foreach ($plans as $plan) {
            $date = Carbon::parse($plan->period_date);
            $labels[] = $this->period === 'monthly'
                ? $date->format('M Y')
                : $date->format('d M');
            $planned[] = round((float) $plan->planned_progress, 2);
            $actual[] = $plan->actual_progress !== null
                ? round((float) $plan->actual_progress, 2)
                : null;
        }

        return [
            'labels' => $labels,
            'planned' => $planned,
            'actual' => $actual,
        ];
    }

    public function render()
    {
        return view('reporting::livewire.s-curve-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
