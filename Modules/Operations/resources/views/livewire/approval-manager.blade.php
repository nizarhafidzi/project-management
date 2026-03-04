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
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Review and manage pending backdate log requests from team members.</p>
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
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Employee</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Project / Task</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Hours (In-Out)</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Progress (+%)</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Status</th>
                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                    @forelse($pendingLogs as $log)
                        <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                            {{-- Date --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-font-medium">
                                {{ $log->log_date ? \Carbon\Carbon::parse($log->log_date)->format('D, d M Y') : '—' }}
                            </td>
                            {{-- Employee --}}
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
                            {{-- Project / Task --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <p class="tw-text-sm tw-text-gray-900">{{ $log->task->name ?? 'Unknown' }}</p>
                                <p class="tw-text-xs tw-text-gray-400">{{ $log->task->project->name ?? '' }}</p>
                            </td>
                            {{-- Hours --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-600">
                                {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '' }} – {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '' }}
                            </td>
                            {{-- Progress --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-semibold tw-text-[#174D9D]">
                                +{{ $log->progress_increment }}%
                            </td>
                            {{-- Status Badge --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                <span class="tw-bg-yellow-100 tw-text-yellow-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Pending</span>
                            </td>
                            {{-- Actions --}}
                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                @hasanyrole('Superadmin|Manager')
                                <div class="tw-flex tw-flex-col tw-gap-2">
                                    <div class="tw-flex tw-items-center tw-gap-1">
                                        {{-- Approve Button --}}
                                        <button wire:click="approve({{ $log->id }})"
                                                wire:confirm="Are you sure you want to approve this backdate request? The progress will be applied to the task."
                                                title="Approve"
                                                class="tw-text-green-600 hover:tw-text-green-900 tw-bg-green-50 hover:tw-bg-green-100 tw-p-1.5 tw-rounded tw-transition">
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        {{-- Reject Button --}}
                                        <button wire:click="reject({{ $log->id }})"
                                                title="Reject"
                                                class="tw-text-red-600 hover:tw-text-red-900 tw-bg-red-50 hover:tw-bg-red-100 tw-p-1.5 tw-rounded tw-transition">
                                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    {{-- Rejection Reason Input --}}
                                    <input type="text" wire:model="rejectionReason"
                                           placeholder="Reason for rejection..."
                                           class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm tw-px-2 tw-py-1 tw-text-xs focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D]">
                                    @error('rejectionReason') <span class="tw-text-red-500 tw-text-xs">{{ $message }}</span> @enderror
                                </div>
                                @else
                                <span class="tw-text-gray-400 tw-text-sm">—</span>
                                @endhasanyrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tw-px-6 tw-py-10 tw-text-center">
                                <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-gray-300 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="tw-text-sm tw-text-gray-500">No pending backdate requests. All clear!</p>
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
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Employee</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-uppercase tw-text-gray-500 tw-font-medium tw-tracking-wider">Task</th>
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
</div>
