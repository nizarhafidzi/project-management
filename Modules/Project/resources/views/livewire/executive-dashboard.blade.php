<div class="tw-min-h-screen tw-bg-gray-50/50 tw-font-sans tw-py-8">
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
        
        <!-- Header Section -->
        <div class="tw-mb-8">
            <h1 class="tw-text-3xl tw-font-bold tw-tracking-tight tw-text-gray-900">Executive Overview</h1>
            <p class="tw-mt-2 tw-text-base tw-text-gray-500">Global BIM performance summary and key project insights.</p>
        </div>

        <!-- Metric Cards Grid -->
        <div class="tw-grid tw-grid-cols-1 tw-gap-6 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-mb-8">
            <!-- Active Projects -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-6 tw-shadow-sm tw-border tw-border-gray-100 tw-transition-all hover:tw-shadow-md hover:tw-border-blue-100">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">Active Projects</p>
                        <p class="tw-mt-2 tw-text-3xl tw-font-bold tw-text-gray-900 tw-tracking-tight">{{ $activeProjects }}</p>
                    </div>
                    <div class="tw-p-3 tw-rounded-xl tw-bg-blue-50 tw-text-blue-600">
                        <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Average Progress -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-6 tw-shadow-sm tw-border tw-border-gray-100 tw-transition-all hover:tw-shadow-md hover:tw-border-green-100">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">Avg. Progress</p>
                        <p class="tw-mt-2 tw-text-3xl tw-font-bold tw-text-gray-900 tw-tracking-tight">{{ $averageProgress }}%</p>
                    </div>
                    <div class="tw-p-3 tw-rounded-xl tw-bg-green-50 tw-text-green-600">
                        <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Total Man-Hours -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-6 tw-shadow-sm tw-border tw-border-gray-100 tw-transition-all hover:tw-shadow-md hover:tw-border-indigo-100">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">Total Man-Hours</p>
                        <p class="tw-mt-2 tw-text-3xl tw-font-bold tw-text-gray-900 tw-tracking-tight">{{ number_format($totalManHours) }}</p>
                    </div>
                    <div class="tw-p-3 tw-rounded-xl tw-bg-indigo-50 tw-text-indigo-600">
                        <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Sync Alerts -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-6 tw-shadow-sm tw-border tw-border-gray-100 tw-transition-all hover:tw-shadow-md hover:tw-border-red-100">
                <div class="tw-flex tw-items-center tw-justify-between">
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">Sync Alerts</p>
                        <p class="tw-mt-2 tw-text-3xl tw-font-bold tw-text-gray-900 tw-tracking-tight">{{ $syncAlerts }}</p>
                    </div>
                    <div class="tw-p-3 tw-rounded-xl tw-bg-red-50 tw-text-red-600">
                        <svg class="tw-w-6 tw-h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6 tw-mb-8">
            <!-- Weekly Workforce Productivity Trend -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-8 tw-shadow-sm tw-border tw-border-gray-100">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Weekly Workforce Productivity Trends</h3>
                </div>
                <div class="tw-relative tw-h-80 tw-w-full">
                     <canvas id="manHoursChart"></canvas>
                </div>
            </div>

            <!-- Project Distribution Doughnut -->
            <div class="tw-bg-white tw-rounded-2xl tw-p-8 tw-shadow-sm tw-border tw-border-gray-100">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Project Distribution</h3>
                </div>
                <div class="tw-relative tw-h-80 tw-w-full tw-flex tw-items-center tw-justify-center">
                     <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activity & Links Grid -->
        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6">
            
            <!-- Recent Activity Feed -->
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-overflow-hidden tw-flex tw-flex-col">
                <div class="tw-p-6 tw-border-b tw-border-gray-100 tw-flex tw-justify-between tw-items-center tw-bg-gray-50/30">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Recent Global Activity</h3>
                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800 tw-ring-1 tw-ring-green-600/10">
                        Live Updates
                    </span>
                </div>
                <div class="tw-flex-1 tw-overflow-y-auto tw-max-h-[500px]">
                    <ul role="list" class="tw-divide-y tw-divide-gray-100">
                        @forelse($recentActivities as $log)
                        <li class="tw-p-6 hover:tw-bg-gray-50 tw-transition-colors">
                            <div class="tw-flex tw-space-x-4">
                                <!-- User Avatar -->
                                <div class="tw-flex-shrink-0">
                                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-full tw-bg-blue-100 tw-text-blue-600 tw-ring-4 tw-ring-white tw-shadow-sm">
                                        <span class="tw-font-bold tw-text-sm">{{ substr($log->user->name ?? 'U', 0, 1) }}</span>
                                    </span>
                                </div>
                                <!-- Activity Content -->
                                <div class="tw-min-w-0 tw-flex-1">
                                    <div class="tw-flex tw-justify-between tw-items-start">
                                        <p class="tw-text-sm tw-font-medium tw-text-gray-900">
                                            {{ $log->user->name ?? 'Unknown' }}
                                        </p>
                                        <p class="tw-text-xs tw-text-gray-500 tw-whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                                        updated task 
                                        <a href="{{ route('project.task.workspace', ['project' => $log->task->project_id, 'task' => $log->task_id]) }}" class="tw-font-medium tw-text-blue-600 hover:tw-text-blue-700 hover:tw-underline">
                                            {{ Str::limit($log->task->name ?? 'Unknown', 40) }}
                                        </a>
                                    </p>
                                    @if($log->notes)
                                        <div class="tw-mt-3 tw-p-3 tw-bg-gray-50 tw-rounded-lg tw-text-sm tw-text-gray-500 tw-italic tw-border tw-border-gray-100">
                                            "{{ Str::limit($log->notes, 80) }}"
                                        </div>
                                    @endif
                                    @if($log->progress_increment > 0)
                                        <div class="tw-mt-2">
                                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-50 tw-text-green-700 tw-ring-1 tw-ring-green-600/10">
                                                +{{ $log->progress_increment }}% Progress
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="tw-py-12 tw-text-center">
                            <svg class="tw-mx-auto tw-h-12 tw-w-12 tw-text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="tw-mt-2 tw-text-sm tw-text-gray-500">No recent activity found.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Quick Links / My Projects -->
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-overflow-hidden tw-flex tw-flex-col">
                <div class="tw-p-6 tw-border-b tw-border-gray-100 tw-flex tw-justify-between tw-items-center tw-bg-gray-50/30">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">My Projects</h3>
                    <a href="{{ route('project.index') }}" class="tw-text-sm tw-font-medium tw-text-blue-600 hover:tw-text-blue-700 hover:tw-underline">View All</a>
                </div>
                <div class="tw-flex-1 tw-overflow-y-auto tw-max-h-[500px]">
                    <ul role="list" class="tw-divide-y tw-divide-gray-100">
                        @forelse($projectsList as $project)
                        <li class="tw-relative tw-p-6 hover:tw-bg-gray-50 tw-transition-colors group">
                            <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                                <div class="tw-flex tw-items-center tw-space-x-3">
                                    <h4 class="tw-text-sm tw-font-semibold tw-text-gray-900 group-hover:tw-text-blue-600 tw-transition-colors">
                                        <a href="{{ route('project.show', $project) }}" class="focus:tw-outline-none">
                                            <span class="tw-absolute tw-inset-0" aria-hidden="true"></span>
                                            {{ $project->name }}
                                        </a>
                                    </h4>
                                    <!-- Status Badge -->
                                    <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded text-xs tw-font-medium fs-10px tw-border
                                        {{ $project->status->name === 'Active' ? 'tw-bg-green-50 tw-text-green-700 tw-border-green-200' : 'tw-bg-gray-50 tw-text-gray-600 tw-border-gray-200' }}">
                                        {{ $project->status->name ?? $project->status }}
                                    </span>
                                </div>
                                <span class="tw-text-xs tw-text-gray-500 tw-font-mono">{{ $project->project_code }}</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="tw-mt-4">
                                <div class="tw-flex tw-justify-between tw-text-xs tw-mb-2">
                                    <span class="tw-font-medium tw-text-gray-500">Progress</span>
                                    <span class="tw-font-bold tw-text-gray-900">{{ $project->total_progress }}%</span>
                                </div>
                                <div class="tw-w-full tw-bg-gray-100 tw-rounded-full tw-h-2 tw-overflow-hidden">
                                    <div class="tw-bg-blue-600 tw-h-2 tw-rounded-full tw-transition-all tw-duration-500 tw-shadow-sm" style="width: {{ $project->total_progress }}%"></div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="tw-py-12 tw-text-center">
                            <p class="tw-text-sm tw-text-gray-500">No projects found.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
                <!-- Optional Footer -->
                @if($projectsList->count() > 0)
                <div class="tw-bg-gray-50 tw-px-6 tw-py-4 hover:tw-bg-gray-100 tw-transition-colors tw-border-t tw-border-gray-100">
                    <a href="{{ route('project.index') }}" class="tw-flex tw-items-center tw-justify-center tw-text-sm tw-font-medium tw-text-gray-600 hover:tw-text-gray-900">
                        View Detailed Project List
                        <svg class="tw-ml-2 tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                @endif
            </div>

        </div> 
    </div>

    <!-- Charts Script -->
    @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets

    @script
    <script>
        // Init logic for Chart.js
        Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
            // Optional: Re-init charts if needed on updates
        })
        
        // Render Charts once component is mounted
        const trendData = @json($manHourTrend);
        const statusDataMap = @json($projectStatusData);

        const statusLabels = Object.keys(statusDataMap);
        const statusValues = Object.values(statusDataMap);

        // Palette
        const statusColors = {
            'Active': '#10b981',    // Emerald-500
            'Completed': '#3b82f6', // Blue-500
            'On-Hold': '#f59e0b',   // Amber-500
            'Archived': '#6b7280',  // Gray-500
            'Cancelled': '#ef4444'  // Red-500
        };
              
        const backgroundColors = statusLabels.map(label => statusColors[label] || '#94a3b8');

        // Global Chart Defaults
        Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#6b7280';

        // 1. Man-Hours Line Chart
        const ctxLine = document.getElementById('manHoursChart');
        if (ctxLine) {
            const ctx = ctxLine.getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.1)'); 
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendData.labels || [],
                    datasets: [{
                        label: 'Man-Hours',
                        data: trendData.data || [],
                        borderColor: '#6366f1', // Indigo-500
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { weight: '600' },
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
        if(ctxPie) {
             new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: backgroundColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                     responsive: true,
                     maintainAspectRatio: false,
                     cutout: '75%',
                     plugins: {
                         legend: { 
                             position: 'right',
                             labels: { 
                                 usePointStyle: true, 
                                 boxWidth: 8,
                                 padding: 15,
                                 font: { size: 12 }
                             }
                         },
                         tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8
                        }
                     }
                }
            });
        }
    </script>
    @endscript
</div>
