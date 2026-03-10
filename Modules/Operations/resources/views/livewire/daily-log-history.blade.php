<div>
    <!-- Header & Title -->
    <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
        <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800">Daily Log History</h2>
    </div>

    <!-- Filter Card -->
    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6 tw-mb-6 tw-border tw-border-gray-100">
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-4">
            <!-- Start Date -->
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate" class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm">
            </div>
            
            <!-- End Date -->
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">End Date</label>
                <input type="date" wire:model.live="endDate" class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm">
            </div>

            <!-- Project Filter -->
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Project</label>
                <select wire:model.live="filterProjectId" class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm">
                    <option value="">All Projects</option>
                    @foreach($projectOptions as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Employee Filter -->
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Employee</label>
                <select wire:model.live="filterUserId" class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm">
                    <option value="">All Employees</option>
                    @foreach($userOptions as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Status</label>
                <select wire:model.live="filterStatus" class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-100 tw-overflow-hidden">
        <div class="tw-overflow-x-auto">
            <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                <thead class="tw-bg-gray-50">
                    <tr>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Date</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Employee Name</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Project & Task</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock In</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock Out</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Progress</th>
                        <th scope="col" class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900">
                                {{ $log->log_date ? \Carbon\Carbon::parse($log->log_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <div class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $log->user->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="tw-px-6 tw-py-4">
                                <div class="tw-text-sm tw-font-bold tw-text-gray-900">{{ $log->task->project->name ?? 'N/A' }}</div>
                                <div class="tw-text-xs tw-text-gray-500 tw-lowercase">{{ $log->task->name ?? 'N/A' }}</div>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-text-center">
                                {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '-' }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-text-center">
                                {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '-' }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-center">
                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-blue-100 tw-text-blue-800">
                                    {{ rtrim(rtrim($log->progress_increment, '0'), '.') }}%
                                </span>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-center">
                                @if($log->approval_status === 'draft')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                                        Draft
                                    </span>
                                @elseif($log->approval_status === 'pending')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-yellow-100 tw-text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($log->approval_status === 'approved')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800">
                                        Approved
                                    </span>
                                @elseif($log->approval_status === 'rejected')
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-100 tw-text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                                        {{ ucfirst($log->approval_status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tw-px-6 tw-py-10 tw-text-center tw-text-sm tw-text-gray-500">
                                <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                    <svg class="tw-h-10 tw-w-10 tw-text-gray-400 tw-mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="tw-text-base tw-font-medium tw-text-gray-900">No daily logs found</span>
                                    <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Try adjusting your filters to find what you're looking for.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-white">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
