<div>
    <div class="tw-max-w-5xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
        {{-- Header --}}
        <div class="tw-mb-6">
            <h2 class="tw-text-2xl tw-font-bold tw-text-gray-900">Daily Operations Log</h2>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Record your daily work progress. All times use server clock (WIB).</p>
        </div>

        {{-- Date & Time Display --}}
        <div class="tw-mb-6 tw-flex tw-flex-wrap tw-justify-between tw-items-center tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-4">
            <div class="tw-flex tw-items-center tw-space-x-4">
                <div>
                    <span class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Today</span>
                    <p class="tw-text-lg tw-font-semibold tw-text-gray-900">{{ $todayDate }}</p>
                </div>
                <div>
                    <span class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Server Time (WIB)</span>
                    <p class="tw-text-lg tw-font-semibold tw-text-indigo-600">{{ $currentTime }}</p>
                </div>
            </div>
            <div>
                <button wire:click="$toggle('isBackdateMode')"
                        class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-rounded-md tw-text-sm tw-font-medium
                               {{ $isBackdateMode ? 'tw-bg-yellow-100 tw-border-yellow-300 tw-text-yellow-800' : 'tw-bg-gray-100 tw-border-gray-300 tw-text-gray-700' }}
                               hover:tw-bg-gray-200 tw-transition">
                    @if($isBackdateMode)
                        <svg class="tw-w-4 tw-h-4 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Switch to Today Mode
                    @else
                        <svg class="tw-w-4 tw-h-4 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Request Backdate
                    @endif
                </button>
            </div>
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

        @if(!$isBackdateMode)
            {{-- ===================== TODAY MODE ===================== --}}
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6 tw-mb-6">
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-4">New Log Entry</h3>
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4 tw-items-end">
                    <div class="md:tw-col-span-2">
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Select Task</label>
                        <select wire:model="taskId"
                                class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                            <option value="">-- Choose a Task --</option>
                            @foreach($myTasks as $task)
                                <option value="{{ $task->id }}">{{ $task->wbs_code }} — {{ $task->name }} ({{ $task->project->name }})</option>
                            @endforeach
                        </select>
                        @error('taskId') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <button wire:click="clockIn"
                                class="tw-w-full tw-inline-flex tw-justify-center tw-items-center tw-px-4 tw-py-2 tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-semibold tw-rounded-md tw-shadow-sm tw-transition tw-text-sm">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Clock In (Now)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Today's Log Table --}}
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Today's Logs</h3>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                        <thead class="tw-bg-gray-50">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Task</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock In</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock Out</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Progress (+%)</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                            @forelse($todaysLogs as $log)
                                <tr>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-medium tw-text-gray-900">
                                        {{ $log->task->wbs_code ?? '' }} — {{ $log->task->name ?? 'Unknown' }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '—' }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '—' }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        @if($log->clock_out)
                                            <input type="number" wire:model="progressIncrement"
                                                   min="0" max="100" step="0.5"
                                                   class="tw-w-20 tw-border tw-border-gray-300 tw-rounded tw-px-2 tw-py-1 tw-text-sm focus:tw-ring-indigo-500 focus:tw-border-indigo-500">
                                        @else
                                            <span class="tw-text-gray-400 tw-italic">Clock out first</span>
                                        @endif
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-medium tw-space-x-2">
                                        @if(!$log->clock_out)
                                            <button wire:click="clockOut({{ $log->id }})"
                                                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-bg-red-50 tw-text-red-700 tw-rounded-md tw-text-xs tw-font-medium hover:tw-bg-red-100 tw-transition">
                                                <svg class="tw-w-3.5 tw-h-3.5 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                                Clock Out
                                            </button>
                                        @else
                                            <button wire:click="saveProgress({{ $log->id }})"
                                                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-bg-indigo-50 tw-text-indigo-700 tw-rounded-md tw-text-xs tw-font-medium hover:tw-bg-indigo-100 tw-transition">
                                                <svg class="tw-w-3.5 tw-h-3.5 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Save Progress
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="tw-px-6 tw-py-8 tw-text-center tw-text-gray-400">
                                        <svg class="tw-mx-auto tw-h-8 tw-w-8 tw-text-gray-300 tw-mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        No logs for today. Select a task and clock in to start.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            {{-- ===================== BACKDATE MODE ===================== --}}
            <div class="tw-bg-yellow-50 tw-border-l-4 tw-border-yellow-400 tw-p-4 tw-rounded-r-lg tw-mb-6">
                <div class="tw-flex">
                    <svg class="tw-h-5 tw-w-5 tw-text-yellow-400 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="tw-ml-3 tw-text-sm tw-text-yellow-700">
                        <strong>Backdate Mode</strong> — Entries for past dates require <strong>Manager Approval</strong> before counting toward project progress.
                    </p>
                </div>
            </div>

            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6 tw-mb-6">
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-4">Submit Backdate Request</h3>
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Date</label>
                        <input type="date" wire:model="backdateDate"
                               max="{{ \Carbon\Carbon::yesterday('Asia/Jakarta')->format('Y-m-d') }}"
                               class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                        @error('backdateDate') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Task</label>
                        <select wire:model="taskId"
                                class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                            <option value="">-- Choose Task --</option>
                            @foreach($myTasks as $task)
                                <option value="{{ $task->id }}">{{ $task->wbs_code }} — {{ $task->name }}</option>
                            @endforeach
                        </select>
                        @error('taskId') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Clock In Time</label>
                        <input type="time" wire:model="clockIn"
                               class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                        @error('clockIn') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Clock Out Time</label>
                        <input type="time" wire:model="clockOut"
                               class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                        @error('clockOut') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Progress Increment (%)</label>
                        <input type="number" wire:model="progressIncrement"
                               min="0" max="100" step="0.5"
                               class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                        @error('progressIncrement') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="tw-mt-6">
                    <button wire:click="submitBackdate"
                            class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-yellow-600 hover:tw-bg-yellow-700 tw-text-white tw-font-semibold tw-rounded-md tw-shadow-sm tw-transition tw-text-sm">
                        <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit Backdate Request
                    </button>
                </div>
            </div>

            {{-- My Backdate Requests --}}
            @if($myBackdateRequests->isNotEmpty())
                <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">My Backdate Requests</h3>
                    </div>
                    <div class="tw-overflow-x-auto">
                        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                            <thead class="tw-bg-gray-50">
                                <tr>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Date</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Task</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Time</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Progress</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-gray-200">
                                @foreach($myBackdateRequests as $req)
                                    <tr>
                                        <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-900">{{ \Carbon\Carbon::parse($req->log_date)->format('Y-m-d') }}</td>
                                        <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">{{ $req->task->name ?? '—' }}</td>
                                        <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">
                                            {{ $req->clock_in ? \Carbon\Carbon::parse($req->clock_in)->format('H:i') : '' }} –
                                            {{ $req->clock_out ? \Carbon\Carbon::parse($req->clock_out)->format('H:i') : '' }}
                                        </td>
                                        <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-500">+{{ $req->progress_increment }}%</td>
                                        <td class="tw-px-6 tw-py-3 tw-text-sm">
                                            @if($req->approval_status === 'pending')
                                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-yellow-100 tw-text-yellow-800">Pending</span>
                                            @elseif($req->approval_status === 'approved')
                                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800">Approved</span>
                                            @else
                                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-100 tw-text-red-800">Rejected</span>
                                                @if($req->rejection_reason)
                                                    <p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $req->rejection_reason }}</p>
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
        @endif
    </div>
</div>
