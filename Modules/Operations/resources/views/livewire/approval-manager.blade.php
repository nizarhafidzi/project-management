<div>
    {{-- ===== Breadcrumbs & Header ===== --}}
    <div class="tw-mb-8">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Dashboard</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-700">Operations</span>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Approval Manager</span>
        </nav>
        <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">{{ __('Approval Manager') }}</h1>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Review and manage pending log requests from team members.</p>
            </div>
            <span class="tw-inline-flex tw-items-center tw-bg-yellow-100 tw-text-yellow-800 tw-px-3 tw-py-1.5 tw-rounded-full tw-text-sm tw-font-semibold">
                <svg class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $pendingLogs->count() }} Pending
            </span>
        </div>
    </div>

    {{-- ===== Flash Messages ===== --}}
    @if (session()->has('message'))
        <div class="tw-mb-6 tw-bg-green-50 tw-border tw-border-green-200 tw-text-green-700 tw-px-4 tw-py-3 tw-rounded-lg">
            <div class="tw-flex tw-items-center">
                <svg class="tw-w-5 tw-h-5 tw-mr-2 tw-text-green-500 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="tw-text-sm tw-font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    {{-- ===== Pending Requests Table Card ===== --}}
    <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden tw-mb-8">
        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Pending Requests</h3>
        </div>
        <div class="tw-overflow-x-auto">
            <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                <thead class="tw-bg-gray-50">
                    <tr>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Date</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">User</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Project & Task</th>
                        <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Link ACC File</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Labels</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Progress</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Notes</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                    @forelse($pendingLogs as $log)
                        <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                            {{-- Date --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-font-medium">
                                {{ $log->log_date ? \Carbon\Carbon::parse($log->log_date)->format('D, d M Y') : '—' }}
                            </td>
                            {{-- User --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <div class="tw-h-8 tw-w-8 tw-rounded-full tw-bg-[#174D9D]/10 tw-flex tw-items-center tw-justify-center tw-text-[#174D9D] tw-font-semibold tw-text-xs tw-flex-shrink-0">
                                        {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $log->user->name }}</p>
                                        <p class="tw-text-xs tw-text-gray-400">{{ $log->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            {{-- Project & Task --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <p class="tw-text-sm tw-text-gray-900">{{ $log->task->name ?? 'Unknown' }}</p>
                                <p class="tw-text-xs tw-text-gray-400">{{ $log->task->project->name ?? '' }}</p>
                            </td>

                            {{-- ACC File Link --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-center">
                                @if(isset($log->task) && $log->task->acc_file_urn)
                                    <a href="{{ route('project.task.workspace', ['project' => $log->task->project_id, 'task' => $log->task->id]) }}"
                                       target="_blank"
                                       title="{{ $log->task->acc_file_name }}"
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-p-1.5 tw-bg-blue-50 tw-text-[#174D9D] hover:tw-bg-blue-100 hover:tw-text-blue-800 tw-rounded-md tw-transition-colors">
                                        <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="tw-text-gray-400 tw-text-sm">—</span>
                                @endif
                            </td>

                            {{-- Labels --}}
                            @php
                                $isBackdateItem = \Carbon\Carbon::parse($log->log_date)->lt(\Carbon\Carbon::parse($log->created_at)->startOfDay()) || $log->is_backdate;
                                $isFinalReview = str_contains($log->notes ?? '', '[FINAL REVIEW]');
                            @endphp
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <div class="tw-flex tw-flex-col tw-gap-1 tw-items-start">
                                    @if($isBackdateItem)
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-100 tw-text-red-800">
                                            Backdate
                                        </span>
                                    @endif
                                    @if($isFinalReview)
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-blue-100 tw-text-blue-800">
                                            Final Review (100%)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Progress --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-semibold tw-text-[#174D9D]">
                                +{{ $log->progress_increment }}%
                            </td>

                            {{-- Notes --}}
                            <td class="tw-px-6 tw-py-4">
                                <div class="tw-text-sm tw-text-gray-600 tw-max-w-xs tw-truncate" title="{{ trim(Str::replace('[FINAL REVIEW]', '', $log->notes)) }}">
                                    {{ trim(Str::replace('[FINAL REVIEW]', '', $log->notes)) ?: '—' }}
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                @if($this->canApproveLog($log->id))
                                    <div class="tw-flex tw-flex-col tw-gap-2">
                                        <div class="tw-flex tw-items-center tw-gap-1">
                                            {{-- Approve Button --}}
                                            <button wire:click="approve({{ $log->id }})"
                                                    wire:confirm="Are you sure you want to approve this request? The progress will be applied to the task."
                                                    title="Approve"
                                                    class="tw-text-green-600 hover:tw-text-green-900 tw-bg-green-50 hover:tw-bg-green-100 tw-p-1.5 tw-rounded tw-transition">
                                                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                            {{-- Reject Button --}}
                                            <button wire:click="openRejectModal({{ $log->id }})"
                                                    title="Reject"
                                                    class="tw-text-red-600 hover:tw-text-red-900 tw-bg-red-50 hover:tw-bg-red-100 tw-p-1.5 tw-rounded tw-transition">
                                                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="tw-px-2 tw-py-1 tw-bg-gray-100 tw-text-gray-500 tw-text-xs tw-italic tw-rounded tw-inline-block tw-max-w-[150px] tw-whitespace-normal tw-text-center">Requires Manager/<br>Superadmin Approval</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="tw-px-6 tw-py-10 tw-text-center">
                                <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-gray-300 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="tw-text-sm tw-text-gray-500">No pending requests. All clear!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== Recent Decisions Table Card ===== --}}
    @if($recentDecisions->isNotEmpty())
        <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Recent Decisions</h3>
            </div>
            <div class="tw-overflow-x-auto">
                <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Date</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">User</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Task</th>
                            <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Link ACC File</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Progress</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-gray-200">
                        @foreach($recentDecisions as $decision)
                            <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-900 tw-font-medium">{{ \Carbon\Carbon::parse($decision->log_date)->format('D, d M Y') }}</td>
                                <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-600">{{ $decision->user->name }}</td>
                                <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-600">{{ $decision->task->name ?? '—' }}</td>
                                <td class="tw-px-6 tw-py-3 tw-text-center">
                                    @if(isset($decision->task) && $decision->task->acc_file_urn)
                                        <a href="{{ route('project.task.workspace', ['project' => $decision->task->project_id, 'task' => $decision->task->id]) }}"
                                           target="_blank"
                                           title="{{ $decision->task->acc_file_name }}"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-p-1.5 tw-bg-blue-50 tw-text-[#174D9D] hover:tw-bg-blue-100 hover:tw-text-blue-800 tw-rounded-md tw-transition-colors">
                                            <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="tw-text-gray-400 tw-text-sm">—</span>
                                    @endif
                                </td>
                                <td class="tw-px-6 tw-py-3 tw-text-sm tw-font-semibold tw-text-[#174D9D]">+{{ $decision->progress_increment }}%</td>
                                <td class="tw-px-6 tw-py-3 tw-text-sm">
                                    @if($decision->approval_status === 'approved')
                                        <span class="tw-bg-green-100 tw-text-green-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Approved</span>
                                    @else
                                        <span class="tw-bg-red-100 tw-text-red-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Rejected</span>
                                        @if($decision->rejection_reason)
                                            <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $decision->rejection_reason }}</p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ===== Reject & Revise Modal ===== --}}
    @if($isRejectModalOpen)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center tw-overflow-y-auto tw-overflow-x-hidden tw-bg-gray-900 tw-bg-opacity-50">
            <div class="tw-relative tw-w-full tw-max-w-md tw-p-4">
                <div class="tw-relative tw-bg-white tw-rounded-lg tw-shadow">
                    {{-- Modal Header --}}
                    <div class="tw-flex tw-items-center tw-justify-between tw-p-4 tw-border-b tw-rounded-t">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">
                            Reject & Revise Progress
                        </h3>
                        <button type="button" wire:click="$set('isRejectModalOpen', false)" class="tw-text-gray-400 tw-bg-transparent hover:tw-bg-gray-200 hover:tw-text-gray-900 tw-rounded-lg tw-text-sm tw-w-8 tw-h-8 tw-ms-auto tw-inline-flex tw-justify-center tw-items-center">
                            <svg class="tw-w-3 tw-h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="tw-sr-only">Close modal</span>
                        </button>
                    </div>
                    {{-- Modal Body --}}
                    <div class="tw-p-4 tw-space-y-4">
                        <div>
                            <label for="revisedProgress" class="tw-block tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-900">Revised Progress (%)</label>
                            <input type="number" id="revisedProgress" wire:model="revisedProgress" min="0" max="99" class="tw-bg-gray-50 tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] tw-block tw-w-full tw-p-2.5" placeholder="e.g. 85" required>
                            @error('revisedProgress') <span class="tw-text-red-500 tw-text-xs">{{ $message }}</span> @enderror
                            <p class="tw-mt-1 tw-text-xs tw-text-gray-500">Maximum allowed progress is 99% upon rejection.</p>
                        </div>
                        <div>
                            <label for="rejectReason" class="tw-block tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-900">Reason / Notes</label>
                            <textarea id="rejectReason" wire:model="rejectReason" rows="3" class="tw-block tw-p-2.5 tw-w-full tw-text-sm tw-text-gray-900 tw-bg-gray-50 tw-rounded-lg tw-border tw-border-gray-300 focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D]" placeholder="E.g., The notes are incomplete, I set it to 85% first..." required></textarea>
                            @error('rejectReason') <span class="tw-text-red-500 tw-text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    {{-- Modal Footer --}}
                    <div class="tw-flex tw-items-center tw-p-4 tw-border-t tw-border-gray-200 tw-rounded-b">
                        <button wire:click="confirmReject" type="button" class="tw-text-white tw-bg-red-600 hover:tw-bg-red-700 focus:tw-ring-4 focus:tw-outline-none focus:tw-ring-red-300 tw-font-medium tw-rounded-lg tw-text-sm tw-px-5 tw-py-2.5 tw-text-center">Confirm Reject & Revise</button>
                        <button wire:click="$set('isRejectModalOpen', false)" type="button" class="tw-py-2.5 tw-px-5 tw-ms-3 tw-text-sm tw-font-medium tw-text-gray-900 focus:tw-outline-none tw-bg-white tw-rounded-lg tw-border tw-border-gray-200 hover:tw-bg-gray-100 hover:tw-text-[#174D9D] focus:tw-z-10 focus:tw-ring-4 focus:tw-ring-gray-100">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
