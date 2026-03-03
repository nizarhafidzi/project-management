<div class="tw-min-h-screen tw-bg-gray-50/50 tw-font-sans tw-py-8">
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">

        {{-- Header Section --}}
        <div class="tw-mb-8">
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Executive Dashboard</h1>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Welcome back, <span class="tw-font-medium tw-text-gray-700">{{ Auth::user()->name }}</span></p>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             KPI Cards — 4-Column Grid
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-6 tw-mb-6">

            {{-- Active Projects --}}
            <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
                <div class="tw-relative tw-z-10">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500 tw-mb-1">Active Projects</p>
                    <p class="tw-text-3xl tw-font-bold tw-text-gray-900">{{ $activeProjects }}</p>
                    <p class="tw-text-xs tw-text-gray-400 tw-mt-1">of {{ $totalProjects }} total</p>
                </div>
                {{-- Large Faded Icon --}}
                <div class="tw-absolute tw-top-3 tw-right-3 tw-opacity-10">
                    <svg class="tw-w-20 tw-h-20" fill="#174D9D" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>

            {{-- Average Progress --}}
            <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
                <div class="tw-relative tw-z-10">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500 tw-mb-1">Avg. Progress</p>
                    <p class="tw-text-3xl tw-font-bold tw-text-gray-900">{{ $averageProgress }}<span class="tw-text-lg tw-text-gray-400">%</span></p>
                    <div class="tw-w-full tw-bg-gray-100 tw-rounded-full tw-h-1.5 tw-mt-2">
                        <div class="tw-h-1.5 tw-rounded-full tw-transition-all tw-duration-500" style="width: {{ $averageProgress }}%; background-color: #174D9D;"></div>
                    </div>
                </div>
                <div class="tw-absolute tw-top-3 tw-right-3 tw-opacity-10">
                    <svg class="tw-w-20 tw-h-20" fill="#174D9D" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>

            {{-- Total Man-Hours --}}
            <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
                <div class="tw-relative tw-z-10">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500 tw-mb-1">Total Man-Hours</p>
                    <p class="tw-text-3xl tw-font-bold tw-text-gray-900">{{ number_format($totalManHours) }}</p>
                    <p class="tw-text-xs tw-text-gray-400 tw-mt-1">across all projects</p>
                </div>
                <div class="tw-absolute tw-top-3 tw-right-3 tw-opacity-10">
                    <svg class="tw-w-20 tw-h-20" fill="#174D9D" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            {{-- Sync Alerts --}}
            <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
                <div class="tw-relative tw-z-10">
                    <p class="tw-text-sm tw-font-medium tw-text-gray-500 tw-mb-1">Sync Alerts</p>
                    <p class="tw-text-3xl tw-font-bold {{ $syncAlerts > 0 ? 'tw-text-red-600' : 'tw-text-gray-900' }}">{{ $syncAlerts }}</p>
                    <p class="tw-text-xs {{ $syncAlerts > 0 ? 'tw-text-red-400' : 'tw-text-gray-400' }} tw-mt-1">
                        {{ $syncAlerts > 0 ? 'files out of sync' : 'all files synced' }}
                    </p>
                </div>
                <div class="tw-absolute tw-top-3 tw-right-3 tw-opacity-10">
                    <svg class="tw-w-20 tw-h-20" fill="#174D9D" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════════════════════
             Main Content — 2:1 Grid (Charts Left, Activity Right)
        ══════════════════════════════════════════════════════════════════ --}}
        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">

            {{-- LEFT COLUMN (Span 2) — Charts --}}
            <div class="lg:tw-col-span-2 tw-space-y-6">

                {{-- Weekly Productivity Trend --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                        <div>
                            <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">Weekly Productivity Trend</h3>
                            <p class="tw-text-xs tw-text-gray-400 tw-mt-0.5">Daily man-hours over the last 7 days</p>
                        </div>
                        <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1 tw-rounded-md tw-text-xs tw-font-medium tw-border" style="color: #174D9D; border-color: #174D9D20; background-color: #174D9D08;">
                            <svg class="tw-w-3 tw-h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                            Last 7 Days
                        </span>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-relative tw-h-72 tw-w-full">
                            <canvas id="manHoursChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Project Status Distribution --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                        <div>
                            <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">Project Status Distribution</h3>
                            <p class="tw-text-xs tw-text-gray-400 tw-mt-0.5">Breakdown of all project statuses</p>
                        </div>
                        <span class="tw-text-xs tw-font-medium tw-text-gray-500">{{ $totalProjects }} Total</span>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-relative tw-h-72 tw-w-full tw-flex tw-items-center tw-justify-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN (Span 1) — Activity Feed + My Projects --}}
            <div class="tw-space-y-6">

                {{-- Activity Feed (Vertical Timeline) --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                        <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">Activity Feed</h3>
                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-50 tw-text-green-700 tw-ring-1 tw-ring-green-600/10">
                            <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-green-500 tw-mr-1.5 tw-animate-pulse"></span>
                            Live
                        </span>
                    </div>
                    <div class="tw-overflow-y-auto tw-max-h-[420px]">
                        <div class="tw-px-6 tw-py-4">
                            @forelse($recentActivities as $log)
                                <div class="tw-relative tw-pl-6 tw-pb-6 {{ !$loop->last ? 'tw-border-l-2 tw-border-gray-100' : '' }} tw-ml-2">
                                    {{-- Timeline Dot --}}
                                    <div class="tw-absolute -tw-left-[7px] tw-top-0.5">
                                        <div class="tw-w-3 tw-h-3 tw-rounded-full tw-ring-4 tw-ring-white" style="background-color: #174D9D;"></div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="tw-ml-2">
                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-0.5">
                                            <span class="tw-text-xs tw-font-semibold tw-text-gray-900">{{ $log->user->name ?? 'Unknown' }}</span>
                                            <span class="tw-text-[10px] tw-text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="tw-text-xs tw-text-gray-500 tw-leading-relaxed">
                                            Updated
                                            <a href="{{ route('project.task.workspace', ['project' => $log->task->project_id, 'task' => $log->task_id]) }}" class="tw-font-medium hover:tw-underline" style="color: #174D9D;">
                                                {{ Str::limit($log->task->name ?? 'Unknown', 35) }}
                                            </a>
                                        </p>
                                        @if($log->progress_increment > 0)
                                            <span class="tw-inline-flex tw-items-center tw-mt-1 tw-px-1.5 tw-py-0.5 tw-rounded tw-text-[10px] tw-font-medium tw-bg-green-50 tw-text-green-700">
                                                +{{ $log->progress_increment }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="tw-text-center tw-py-10">
                                    <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="tw-mt-2 tw-text-xs tw-text-gray-400">No recent activity.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- My Projects --}}
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                        <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">My Projects</h3>
                        <a href="{{ route('project.index') }}" class="tw-text-xs tw-font-medium hover:tw-underline" style="color: #174D9D;">View All →</a>
                    </div>
                    <div class="tw-overflow-y-auto tw-max-h-[360px]">
                        <ul role="list" class="tw-divide-y tw-divide-gray-100">
                            @forelse($projectsList as $project)
                                <li class="tw-px-6 tw-py-4 hover:tw-bg-gray-50 tw-transition-colors tw-group">
                                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                        <a href="{{ route('project.show', $project) }}" class="tw-text-sm tw-font-semibold tw-text-gray-900 group-hover:tw-text-blue-700 tw-transition-colors tw-truncate tw-max-w-[180px]">
                                            {{ $project->name }}
                                        </a>
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[10px] tw-font-medium tw-border
                                            {{ ($project->status->name ?? $project->status) === 'Active' ? 'tw-bg-green-50 tw-text-green-700 tw-border-green-200' : 'tw-bg-gray-50 tw-text-gray-600 tw-border-gray-200' }}">
                                            {{ $project->status->name ?? $project->status }}
                                        </span>
                                    </div>
                                    {{-- Progress Bar --}}
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <div class="tw-flex-1 tw-bg-gray-100 tw-rounded-full tw-h-1.5 tw-overflow-hidden">
                                            <div class="tw-h-1.5 tw-rounded-full tw-transition-all tw-duration-500" style="width: {{ $project->total_progress }}%; background-color: #174D9D;"></div>
                                        </div>
                                        <span class="tw-text-xs tw-font-bold tw-text-gray-700 tw-w-10 tw-text-right">{{ $project->total_progress }}%</span>
                                    </div>
                                </li>
                            @empty
                                <li class="tw-py-10 tw-text-center">
                                    <p class="tw-text-xs tw-text-gray-400">No projects found.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Charts Script --}}
    @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
            // Optional: Re-init charts if needed on updates
        })

        const trendData = @json($manHourTrend);
        const statusDataMap = @json($projectStatusData);

        const statusLabels = Object.keys(statusDataMap);
        const statusValues = Object.values(statusDataMap);

        const statusColors = {
            'Active': '#10b981',
            'Completed': '#174D9D',
            'On-Hold': '#f59e0b',
            'Archived': '#6b7280',
            'Cancelled': '#ef4444'
        };

        const backgroundColors = statusLabels.map(label => statusColors[label] || '#94a3b8');

        // Global Chart Defaults — Poppins
        Chart.defaults.font.family = "'Poppins', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#6b7280';

        // 1. Productivity Trend Line Chart (Brand Color)
        const ctxLine = document.getElementById('manHoursChart');
        if (ctxLine) {
            const ctx = ctxLine.getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(23, 77, 157, 0.12)');
            gradient.addColorStop(1, 'rgba(23, 77, 157, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendData.labels || [],
                    datasets: [{
                        label: 'Man-Hours',
                        data: trendData.data || [],
                        borderColor: '#174D9D',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#174D9D',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#174D9D',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { weight: '600', family: "'Poppins', sans-serif" },
                            bodyFont: { family: "'Poppins', sans-serif" },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' hrs';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#f3f4f6', drawBorder: false },
                            ticks: { padding: 10, font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { padding: 10, font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // 2. Project Status Doughnut Chart
        const ctxPie = document.getElementById('statusChart');
        if (ctxPie) {
            new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: backgroundColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 16,
                                font: { size: 12, family: "'Poppins', sans-serif" }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { family: "'Poppins', sans-serif" },
                            bodyFont: { family: "'Poppins', sans-serif" }
                        }
                    }
                }
            });
        }
    </script>
    @endscript
</div>
