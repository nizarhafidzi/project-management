<div class="tw-py-8 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-max-w-7xl tw-mx-auto tw-font-sans">
    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <a href="{{ route('project.show', $project) }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">{{ $project->project_code }}</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Analytics</span>
        </nav>
        <div class="tw-flex tw-items-center tw-gap-3">
            <a href="{{ route('project.show', $project) }}" wire:navigate
               class="tw-w-9 tw-h-9 tw-rounded-lg tw-bg-gray-100 hover:tw-bg-gray-200 tw-flex tw-items-center tw-justify-center tw-text-gray-500 hover:tw-text-gray-700 tw-transition-all">
                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Project Overview: {{ $project->name }}</h1>
                <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5">
                    <span class="tw-font-mono tw-text-xs tw-bg-gray-100 tw-px-2 tw-py-0.5 tw-rounded tw-text-gray-600">{{ $project->project_code }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ── KPI Summary Cards (4-column) ── --}}
    <div class="tw-grid sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">
        {{-- Total Progress Card --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-p-5 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-3 tw-right-3 tw-w-10 tw-h-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-opacity-10" style="background-color: #174D9D;">
                <svg class="tw-w-6 tw-h-6 tw-text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-2">Total Progress</p>
            <div class="tw-flex tw-items-end tw-gap-1">
                <span class="tw-text-3xl tw-font-bold tw-text-gray-900">{{ number_format($totalProgress, 1) }}</span>
                <span class="tw-text-sm tw-text-gray-400 tw-mb-1">%</span>
            </div>
            <div class="tw-mt-3 tw-w-full tw-bg-gray-100 tw-rounded-full tw-h-2">
                <div class="tw-h-2 tw-rounded-full tw-transition-all tw-duration-500
                    @if($totalProgress >= 75) tw-bg-emerald-500
                    @elseif($totalProgress >= 50) tw-bg-[#174D9D]
                    @elseif($totalProgress >= 25) tw-bg-amber-500
                    @else tw-bg-red-400
                    @endif"
                     style="width: {{ min($totalProgress, 100) }}%"></div>
            </div>
        </div>

        {{-- Project Status Card --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-p-5 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-3 tw-right-3 tw-w-10 tw-h-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-opacity-10" style="background-color: #174D9D;">
                <svg class="tw-w-6 tw-h-6 tw-text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-2">Status</p>
            <span class="tw-text-lg tw-font-bold
                @if($project->status->value === 'Active') tw-text-emerald-600
                @elseif($project->status->value === 'On-Hold') tw-text-amber-600
                @else tw-text-gray-600
                @endif">{{ $project->status->value }}</span>
            <dl class="tw-mt-2 tw-space-y-1 tw-text-xs">
                <div class="tw-flex tw-justify-between">
                    <dt class="tw-text-gray-400">Sector</dt>
                    <dd class="tw-font-medium tw-text-gray-600">{{ $project->sector->value }}</dd>
                </div>
                <div class="tw-flex tw-justify-between">
                    <dt class="tw-text-gray-400">Service</dt>
                    <dd class="tw-font-medium tw-text-gray-600">{{ $project->technical_service->value }}</dd>
                </div>
            </dl>
        </div>

        {{-- Project Type Card --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-p-5 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-3 tw-right-3 tw-w-10 tw-h-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-opacity-10" style="background-color: #174D9D;">
                <svg class="tw-w-6 tw-h-6 tw-text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-2">Project Type</p>
            <span class="tw-text-lg tw-font-bold tw-text-gray-900">{{ $project->project_type->value }}</span>
        </div>

        {{-- Team Card --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-p-5 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-3 tw-right-3 tw-w-10 tw-h-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-opacity-10" style="background-color: #174D9D;">
                <svg class="tw-w-6 tw-h-6 tw-text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-2">Team Members</p>
            <div class="tw-text-3xl tw-font-bold tw-text-gray-900">{{ $members->count() }}</div>
            <p class="tw-text-sm tw-text-gray-400 tw-mt-1">Members assigned</p>
        </div>
    </div>

    {{-- ── Stacked Full-Width Content ── --}}
    <div class="tw-flex tw-flex-col tw-gap-6">

        {{-- Row 1: S-Curve Chart (Full Width) --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-border tw-border-gray-200 tw-w-full">
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-5">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                        <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">S-Curve (Planned vs Actual)</h2>
                </div>
            </div>
            <div class="tw-w-full" style="min-height: 24rem;">
                <livewire:reporting::s-curve-chart :projectId="$project->id" />
            </div>
        </div>

        {{-- Row 2: Activity History (Full Width) --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-p-6 tw-w-full">
            <div class="tw-flex tw-items-center tw-gap-3 tw-mb-5">
                <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                    <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">Activity History</h2>
            </div>

            @if($activities->count())
                <div class="tw-relative tw-space-y-0">
                    @foreach($activities as $activity)
                        <div class="tw-relative tw-pl-8 tw-pb-6 last:tw-pb-0 tw-group">
                            {{-- Vertical line --}}
                            @if(!$loop->last)
                                <div class="tw-absolute tw-left-[9px] tw-top-4 tw-bottom-0 tw-border-l-2 tw-border-gray-200"></div>
                            @endif

                            {{-- Timeline dot --}}
                            <div class="tw-absolute tw-left-0 tw-top-1.5 tw-w-5 tw-h-5 tw-rounded-full tw-border-2 tw-border-white tw-shadow-sm tw-bg-[#174D9D] group-hover:tw-scale-110 tw-transition-transform tw-z-10"></div>

                            {{-- Content Card --}}
                            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-border tw-border-gray-100 hover:tw-border-gray-200 tw-transition-all hover:tw-shadow-sm">
                                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                                    {{-- Left: User + Activity Info --}}
                                    <div class="tw-flex tw-items-start tw-gap-3 tw-min-w-0">
                                        {{-- User Avatar --}}
                                        <div class="tw-flex-shrink-0 tw-w-9 tw-h-9 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white" style="background-color: #174D9D;">
                                            {{ strtoupper(substr($activity->user->name ?? '?', 0, 2)) }}
                                        </div>
                                        <div class="tw-min-w-0">
                                            <p class="tw-font-semibold tw-text-gray-900 tw-text-sm">
                                                {{ $activity->user->name ?? 'Unknown User' }}
                                            </p>
                                            <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5 tw-truncate">
                                                <span class="tw-text-gray-400">worked on</span>
                                                <span class="tw-font-medium tw-text-gray-700">{{ $activity->task->name ?? 'Unknown Task' }}</span>
                                            </p>
                                            @if($activity->notes)
                                                <p class="tw-text-xs tw-text-gray-400 tw-mt-1.5 tw-italic">
                                                    "{{ Str::limit($activity->notes, 80) }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right: Date + Progress Badge --}}
                                    <div class="tw-flex tw-flex-col tw-items-end tw-gap-1.5 tw-flex-shrink-0">
                                        <span class="tw-text-xs tw-text-gray-400 tw-whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($activity->log_date)->format('d M Y') }}
                                        </span>
                                        @if($activity->progress_increment > 0)
                                            <span class="tw-inline-flex tw-items-center tw-gap-0.5 tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-text-white" style="background-color: #174D9D;">
                                                <svg class="tw-w-3 tw-h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                                </svg>
                                                +{{ number_format($activity->progress_increment, 0) }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="tw-text-center tw-py-12">
                    <div class="tw-w-14 tw-h-14 tw-mx-auto tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mb-3" style="background-color: rgba(23, 77, 157, 0.07);">
                        <svg class="tw-w-6 tw-h-6" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="tw-text-gray-500 tw-font-medium tw-text-sm">No recent activity found for this project.</p>
                    <p class="tw-text-gray-400 tw-text-xs tw-mt-1">Activity will appear here when team members log their daily work.</p>
                </div>
            @endif
        </div>

        {{-- Row 3: Project Members Table (Full Width) --}}
        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200">
            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                        <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">Project Members</h2>
                </div>
            </div>
            <div class="tw-overflow-x-auto">
                <table class="tw-w-full tw-text-sm">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Name</th>
                            <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Email</th>
                            <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Project Role</th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-gray-200">
                        @forelse($members as $member)
                            <tr class="hover:tw-bg-gray-50/60 tw-transition">
                                <td class="tw-py-3.5 tw-px-6">
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <div class="tw-w-8 tw-h-8 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white
                                            @switch($member->pivot->role_in_project)
                                                @case('Manager') tw-bg-rose-500 @break
                                                @case('Team Leader') tw-bg-amber-500 @break
                                                @default tw-bg-[#174D9D]
                                            @endswitch">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <span class="tw-font-medium tw-text-gray-900">{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td class="tw-py-3.5 tw-px-6 tw-text-gray-500">{{ $member->email }}</td>
                                <td class="tw-py-3.5 tw-px-6">
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium
                                        @switch($member->pivot->role_in_project)
                                            @case('Manager') tw-bg-rose-50 tw-text-rose-700 @break
                                            @case('Team Leader') tw-bg-amber-50 tw-text-amber-700 @break
                                            @default tw-bg-blue-50 tw-text-[#174D9D]
                                        @endswitch">
                                        {{ $member->pivot->role_in_project }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="tw-py-8 tw-text-center tw-text-gray-400 tw-text-sm">
                                    No members assigned to this project.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
