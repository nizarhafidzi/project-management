<div class="tw-py-8 tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-max-w-7xl tw-mx-auto">
    {{-- ── Header ── --}}
    <div class="tw-mb-8">
        <div class="tw-flex tw-items-center tw-gap-3 tw-mb-2">
            <a href="{{ route('project.show', $project) }}" wire:navigate
               class="tw-text-gray-400 hover:tw-text-gray-600 tw-transition">
                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">📊 Project Analytics</h1>
        </div>
        <p class="tw-text-gray-500 tw-text-sm tw-ml-8">
            <span class="tw-font-mono tw-text-xs tw-bg-gray-100 tw-px-2 tw-py-0.5 tw-rounded">{{ $project->project_code }}</span>
            &mdash; {{ $project->name }}
        </p>
    </div>

    {{-- ── Summary Cards Row ── --}}
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6 tw-mb-8">
        {{-- Total Progress Card --}}
        <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-0 tw-right-0 tw-w-32 tw-h-32 tw-bg-gradient-to-bl tw-from-emerald-50 tw-to-transparent tw-rounded-bl-full"></div>
            <div class="tw-relative">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4">
                    <div class="tw-w-8 tw-h-8 tw-bg-emerald-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                        <svg class="tw-w-4 tw-h-4 tw-text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="tw-text-sm tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Total Progress</h3>
                </div>
                <div class="tw-flex tw-items-end tw-gap-2">
                    <span class="tw-text-4xl tw-font-bold tw-text-gray-900">{{ number_format($totalProgress, 1) }}</span>
                    <span class="tw-text-lg tw-text-gray-400 tw-mb-1">%</span>
                </div>
                <div class="tw-mt-4 tw-w-full tw-bg-gray-100 tw-rounded-full tw-h-2.5">
                    <div class="tw-h-2.5 tw-rounded-full tw-transition-all tw-duration-500
                        @if($totalProgress >= 75) tw-bg-emerald-500
                        @elseif($totalProgress >= 50) tw-bg-blue-500
                        @elseif($totalProgress >= 25) tw-bg-amber-500
                        @else tw-bg-red-400
                        @endif"
                         style="width: {{ min($totalProgress, 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Project Info Card --}}
        <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-0 tw-right-0 tw-w-32 tw-h-32 tw-bg-gradient-to-bl tw-from-blue-50 tw-to-transparent tw-rounded-bl-full"></div>
            <div class="tw-relative">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4">
                    <div class="tw-w-8 tw-h-8 tw-bg-blue-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                        <svg class="tw-w-4 tw-h-4 tw-text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="tw-text-sm tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Project Info</h3>
                </div>
                <dl class="tw-space-y-2 tw-text-sm">
                    <div class="tw-flex tw-justify-between">
                        <dt class="tw-text-gray-400">Status</dt>
                        <dd class="tw-font-medium
                            @if($project->status->value === 'Active') tw-text-emerald-600
                            @elseif($project->status->value === 'On-Hold') tw-text-amber-600
                            @else tw-text-gray-600
                            @endif">{{ $project->status->value }}</dd>
                    </div>
                    <div class="tw-flex tw-justify-between">
                        <dt class="tw-text-gray-400">Sector</dt>
                        <dd class="tw-font-medium tw-text-gray-700">{{ $project->sector->value }}</dd>
                    </div>
                    <div class="tw-flex tw-justify-between">
                        <dt class="tw-text-gray-400">Service</dt>
                        <dd class="tw-font-medium tw-text-gray-700">{{ $project->technical_service->value }}</dd>
                    </div>
                    <div class="tw-flex tw-justify-between">
                        <dt class="tw-text-gray-400">Type</dt>
                        <dd class="tw-font-medium tw-text-gray-700">{{ $project->project_type->value }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Team Card --}}
        <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6 tw-relative tw-overflow-hidden">
            <div class="tw-absolute tw-top-0 tw-right-0 tw-w-32 tw-h-32 tw-bg-gradient-to-bl tw-from-violet-50 tw-to-transparent tw-rounded-bl-full"></div>
            <div class="tw-relative">
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4">
                    <div class="tw-w-8 tw-h-8 tw-bg-violet-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                        <svg class="tw-w-4 tw-h-4 tw-text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="tw-text-sm tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Team</h3>
                </div>
                <div class="tw-text-3xl tw-font-bold tw-text-gray-900 tw-mb-1">
                    {{ $members->count() }}
                </div>
                <p class="tw-text-sm tw-text-gray-400">Members assigned</p>
            </div>
        </div>
    </div>

    {{-- ── S-Curve Chart ── --}}
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6 tw-mb-8">
        <livewire:reporting::s-curve-chart :projectId="$project->id" />
    </div>

    {{-- ── Member Project Widget ── --}}
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6">
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
            <div class="tw-w-8 tw-h-8 tw-bg-indigo-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                <svg class="tw-w-4 tw-h-4 tw-text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">Project Members</h2>
        </div>

        <div class="tw-overflow-x-auto">
            <table class="tw-w-full tw-text-sm">
                <thead>
                    <tr class="tw-border-b tw-border-gray-100">
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Name</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Email</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Project Role</th>
                    </tr>
                </thead>
                <tbody class="tw-divide-y tw-divide-gray-50">
                    @forelse($members as $member)
                        <tr class="hover:tw-bg-gray-50/50 tw-transition">
                            <td class="tw-py-3 tw-px-4">
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white
                                        @switch($member->pivot->role_in_project)
                                            @case('Manager') tw-bg-rose-500 @break
                                            @case('Team Leader') tw-bg-amber-500 @break
                                            @default tw-bg-blue-500
                                        @endswitch">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </div>
                                    <span class="tw-font-medium tw-text-gray-900">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="tw-py-3 tw-px-4 tw-text-gray-500">{{ $member->email }}</td>
                            <td class="tw-py-3 tw-px-4">
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium
                                    @switch($member->pivot->role_in_project)
                                        @case('Manager') tw-bg-rose-100 tw-text-rose-700 @break
                                        @case('Team Leader') tw-bg-amber-100 tw-text-amber-700 @break
                                        @default tw-bg-blue-100 tw-text-blue-700
                                    @endswitch">
                                    {{ $member->pivot->role_in_project }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="tw-py-8 tw-text-center tw-text-gray-400">
                                No members assigned to this project.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
