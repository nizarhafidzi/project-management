<div>
    <div class="tw-max-w-6xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
        {{-- Header --}}
        <div class="tw-mb-6">
            <h2 class="tw-text-2xl tw-font-bold tw-text-gray-900">Backdate Approval Dashboard</h2>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Review and manage pending backdate log requests from team members.</p>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="tw-bg-green-50 tw-border-l-4 tw-border-green-400 tw-text-green-700 tw-px-4 tw-py-3 tw-rounded tw-mb-4">
                <div class="tw-flex tw-items-center">
                    <svg class="tw-w-5 tw-h-5 tw-mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        {{-- Pending Requests --}}
        <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-overflow-hidden tw-mb-8">
            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Pending Requests</h3>
                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-yellow-100 tw-text-yellow-800">
                    {{ $pendingLogs->count() }} pending
                </span>
            </div>
            <div class="tw-overflow-x-auto">
                <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Employee</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Log Date</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Task</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Time</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Progress</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                        @forelse($pendingLogs as $log)
                            <tr>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                    <div class="tw-flex tw-items-center">
                                        <div class="tw-h-8 tw-w-8 tw-rounded-full tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-text-indigo-700 tw-font-semibold tw-text-xs">
                                            {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                                        </div>
                                        <div class="tw-ml-3">
                                            <p class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $log->user->name }}</p>
                                            <p class="tw-text-xs tw-text-gray-400">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-font-medium">
                                    {{ $log->log_date ? \Carbon\Carbon::parse($log->log_date)->format('D, d M Y') : '—' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                    <p>{{ $log->task->name ?? 'Unknown' }}</p>
                                    <p class="tw-text-xs tw-text-gray-400">{{ $log->task->project->name ?? '' }}</p>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                    {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '' }} –
                                    {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-semibold tw-text-indigo-600">
                                    +{{ $log->progress_increment }}%
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-medium">
                                    <div class="tw-flex tw-flex-col tw-space-y-2">
                                        <button wire:click="approve({{ $log->id }})"
                                                wire:confirm="Are you sure you want to approve this backdate request? The progress will be applied to the task."
                                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-bg-green-50 tw-text-green-700 tw-rounded-md tw-text-xs tw-font-medium hover:tw-bg-green-100 tw-transition">
                                            <svg class="tw-w-3.5 tw-h-3.5 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                        <div class="tw-flex tw-items-center tw-space-x-1">
                                            <input type="text" wire:model="rejectionReason"
                                                   placeholder="Reason for rejection..."
                                                   class="tw-flex-1 tw-border tw-border-gray-300 tw-rounded tw-px-2 tw-py-1 tw-text-xs focus:tw-ring-red-500 focus:tw-border-red-500">
                                            <button wire:click="reject({{ $log->id }})"
                                                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-bg-red-50 tw-text-red-700 tw-rounded-md tw-text-xs tw-font-medium hover:tw-bg-red-100 tw-transition tw-whitespace-nowrap">
                                                <svg class="tw-w-3.5 tw-h-3.5 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Reject
                                            </button>
                                        </div>
                                        @error('rejectionReason') <span class="tw-text-red-500 tw-text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="tw-px-6 tw-py-8 tw-text-center tw-text-gray-400">
                                    <svg class="tw-mx-auto tw-h-8 tw-w-8 tw-text-gray-300 tw-mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    No pending backdate requests. All clear!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Decisions --}}
        @if($recentDecisions->isNotEmpty())
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Recent Decisions</h3>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                        <thead class="tw-bg-gray-50">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Employee</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Date</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Task</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Progress</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-gray-200">
                            @foreach($recentDecisions as $decision)
                                <tr>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-900">{{ $decision->user->name }}</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">{{ \Carbon\Carbon::parse($decision->log_date)->format('Y-m-d') }}</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">{{ $decision->task->name ?? '—' }}</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">+{{ $decision->progress_increment }}%</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm">
                                        @if($decision->approval_status === 'approved')
                                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800">Approved</span>
                                        @else
                                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-100 tw-text-red-800">Rejected</span>
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
</div>
