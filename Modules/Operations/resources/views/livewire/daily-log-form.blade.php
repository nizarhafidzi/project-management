<div>
    {{-- ===== Breadcrumbs & Header ===== --}}
    <div class="tw-mb-8">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Dashboard</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-700">Operations</span>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Daily Log Entry</span>
        </nav>
        <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">{{ __('Daily Log Entry') }}</h1>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Record your daily work progress. All times use server clock (WIB).</p>
            </div>
            {{-- Only show backdate toggle when NOT in an active clock-in session --}}
            @if(!$activeLogId)
            <button type="button" wire:click="$toggle('isBackdateMode')"
                    class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-rounded-lg tw-text-sm tw-font-medium tw-transition
                           {{ $isBackdateMode ? 'tw-bg-yellow-50 tw-border-yellow-300 tw-text-yellow-800 hover:tw-bg-yellow-100' : 'tw-bg-white tw-border-gray-300 tw-text-gray-700 hover:tw-bg-gray-50' }}">
                @if($isBackdateMode)
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Switch to Today Mode
                @else
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Request Backdate
                @endif
            </button>
            @endif
        </div>
    </div>

    {{-- ===== Date & Time Info Bar ===== --}}
    <div class="tw-mb-6 tw-flex tw-flex-wrap tw-items-center tw-gap-6 tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-px-6 tw-py-4">
        <div class="tw-flex tw-items-center tw-gap-3">
            <div class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-lg tw-bg-[#174D9D]/10">
                <svg class="tw-w-5 tw-h-5 tw-text-[#174D9D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <span class="tw-block tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Today's Date</span>
                <p class="tw-text-sm tw-font-semibold tw-text-gray-900">{{ $todayDate }}</p>
            </div>
        </div>
        <div class="tw-w-px tw-h-10 tw-bg-gray-200 tw-hidden sm:tw-block"></div>
        <div class="tw-flex tw-items-center tw-gap-3">
            <div class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-lg tw-bg-green-50">
                <svg class="tw-w-5 tw-h-5 tw-text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="tw-block tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Server Time (WIB)</span>
                <p class="tw-text-sm tw-font-semibold tw-text-[#174D9D]">{{ $currentTime }}</p>
            </div>
        </div>

        @if($activeLogId && !$isBackdateMode)
        <div class="tw-w-px tw-h-10 tw-bg-gray-200 tw-hidden sm:tw-block"></div>
        <div class="tw-flex tw-items-center tw-gap-3">
            <div class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-lg tw-bg-blue-50">
                <svg class="tw-w-5 tw-h-5 tw-text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <span class="tw-block tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Active Task</span>
                <p class="tw-text-sm tw-font-semibold tw-text-[#174D9D] tw-truncate tw-max-w-[150px]" title="{{ $activeTaskName }}">{{ $activeTaskName }}</p>
            </div>
        </div>
        <div class="tw-w-px tw-h-10 tw-bg-gray-200 tw-hidden sm:tw-block"></div>
        <div class="tw-flex tw-items-center tw-gap-3">
            <div class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-lg tw-bg-orange-50">
                <svg class="tw-w-5 tw-h-5 tw-text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="tw-block tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock In Time</span>
                <p class="tw-text-sm tw-font-semibold tw-text-orange-600">{{ $activeClockInTime }} WIB</p>
            </div>
        </div>
        <div class="tw-w-px tw-h-10 tw-bg-gray-200 tw-hidden sm:tw-block"></div>
        <div class="tw-flex tw-items-center tw-gap-3">
            <div class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 tw-rounded-lg tw-bg-gray-50">
                <svg class="tw-w-5 tw-h-5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="tw-block tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Clock Out Time</span>
                <p class="tw-text-sm tw-font-semibold tw-text-gray-400">--:-- WIB</p>
            </div>
        </div>
        @endif
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

    @if (session()->has('error'))
        <div class="tw-mb-6 tw-bg-red-50 tw-border tw-border-red-200 tw-text-red-700 tw-px-4 tw-py-3 tw-rounded-lg">
            <div class="tw-flex tw-items-center">
                <svg class="tw-w-5 tw-h-5 tw-mr-2 tw-text-red-500 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="tw-text-sm tw-font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if(!$isBackdateMode)
        {{-- ===================== TODAY MODE ===================== --}}

        {{-- New Log Entry Card --}}
        <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-p-6 tw-mb-6">

            {{-- ────────────────────────────────────────── --}}
            {{-- STATE 1: No active log → Show Clock In UI --}}
            {{-- ────────────────────────────────────────── --}}
            @if(!$activeLogId)
                <div wire:key="state-1-ui">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-5">New Log Entry</h3>

                    <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                        {{-- Task/Project Dropdown (ONLY visible input in State 1) --}}
                        <div class="tw-col-span-full">
                            <label for="state1-taskId" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Select Task / Project</label>
                            @if(count($myTasks) === 0)
                                <div class="tw-flex tw-items-start tw-gap-3 tw-bg-amber-50 tw-border tw-border-amber-200 tw-rounded-lg tw-p-4 tw-mt-1">
                                    <svg class="tw-h-5 tw-w-5 tw-text-amber-500 tw-flex-shrink-0 tw-mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <p class="tw-text-sm tw-text-amber-700"><strong>No tasks assigned.</strong> You must be assigned to a task before you can clock in. Please contact your Manager.</p>
                                </div>
                            @else
                            <select id="state1-taskId" wire:model="taskId"
                                    class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                                <option value="">-- Choose a Task --</option>
                                @foreach($myTasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->wbs_code }} &mdash; {{ $task->name }} ({{ $task->project->name }})</option>
                                @endforeach
                            </select>
                            @error('taskId') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        {{-- Clock In Button (Blue) --}}
                        <div class="tw-flex tw-justify-end tw-pt-2">
                            <button type="button" wire:click="startClockIn"
                                    wire:loading.attr="disabled"
                                    wire:target="startClockIn"
                                    class="tw-inline-flex tw-items-center tw-px-5 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition
                                           tw-bg-[#174D9D] hover:tw-bg-[#123d7e] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] disabled:tw-opacity-70 disabled:tw-cursor-not-allowed">
                                <svg wire:loading.remove wire:target="startClockIn" class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <svg wire:loading wire:target="startClockIn" class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24"><circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span wire:loading.remove wire:target="startClockIn">Clock In</span>
                                <span wire:loading wire:target="startClockIn">Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>

            {{-- ────────────────────────────────────────── --}}
            {{-- STATE 2: Active log exists → Show Clock Out UI --}}
            {{-- ────────────────────────────────────────── --}}
            @else
                <div wire:key="state-2-ui">
                    {{-- Active Session Indicator --}}
                    <div class="tw-mb-5 tw-flex tw-items-center tw-gap-3 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-lg tw-p-4">
                        <div class="tw-relative tw-flex tw-h-3 tw-w-3">
                            <span class="tw-animate-ping tw-absolute tw-inline-flex tw-h-full tw-w-full tw-rounded-full tw-bg-green-400 tw-opacity-75"></span>
                            <span class="tw-relative tw-inline-flex tw-rounded-full tw-h-3 tw-w-3 tw-bg-green-500"></span>
                        </div>
                        <p class="tw-text-sm tw-font-semibold tw-text-green-800">
                            You clocked in at: <span class="tw-font-bold">{{ $activeClockInTime }} WIB</span> — Session is active
                        </p>
                    </div>

                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-5">Complete Your Log</h3>

                    <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                        {{-- Task/Project Dropdown (Disabled/Readonly) --}}
                        <div class="tw-col-span-full">
                            <label for="state2-taskId" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Selected Task / Project</label>
                            <select id="state2-taskId" wire:model="taskId"
                                    disabled
                                    class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm sm:tw-text-sm tw-bg-gray-100 tw-text-gray-600 tw-cursor-not-allowed tw-opacity-80">
                                @foreach($myTasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->wbs_code }} &mdash; {{ $task->name }} ({{ $task->project->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Progress Increment (APPEARS in State 2) --}}
                        <div class="tw-col-span-full">
                            <label for="progressIncrement" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Progress Increment (%) <span class="tw-text-red-500">*</span></label>
                            <input id="progressIncrement" type="number" wire:model="progressIncrement"
                                   min="0" max="100" step="0.5"
                                   placeholder="e.g. 10.5"
                                   class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                            @error('progressIncrement') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Notes (APPEARS in State 2) --}}
                        <div class="tw-col-span-full">
                            <label for="notes" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Notes / Description <span class="tw-text-red-500">*</span></label>
                            <textarea id="notes" rows="3" wire:model="notes"
                                      placeholder="Describe exactly what you completed..."
                                      class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm"
                                      style="min-height: 5rem;"></textarea>
                            @error('notes') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Clock Out Button (Red/Orange) --}}
                        <div class="tw-flex tw-justify-end tw-pt-2">
                            <button type="button" wire:click="startClockOut"
                                    wire:loading.attr="disabled"
                                    wire:target="startClockOut"
                                    class="tw-inline-flex tw-items-center tw-px-5 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition
                                           tw-bg-red-600 hover:tw-bg-red-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-red-500 disabled:tw-opacity-70 disabled:tw-cursor-not-allowed">
                                <svg wire:loading.remove wire:target="startClockOut" class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                <svg wire:loading wire:target="startClockOut" class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24"><circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span wire:loading.remove wire:target="startClockOut">Clock Out</span>
                                <span wire:loading wire:target="startClockOut">Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Today's Log Table Card --}}
        <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
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
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Man Hours</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Progress</th>
                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                        @forelse($todaysLogs as $log)
                            <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-medium tw-text-gray-900">
                                    {{ $log->task->wbs_code ?? '' }} — {{ $log->task->name ?? 'Unknown' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-600">
                                    {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '—' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-600">
                                    {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '—' }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-semibold tw-text-[#174D9D]">
                                    @if($log->clock_in && $log->clock_out)
                                        {{ number_format($log->man_hours, 2) }} hrs
                                    @else
                                        <span class="tw-text-gray-400 tw-italic">—</span>
                                    @endif
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-semibold tw-text-[#174D9D]">
                                    +{{ $log->progress_increment }}%
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-600 tw-max-w-xs tw-truncate" title="{{ $log->notes }}">
                                    {{ $log->notes ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="tw-px-6 tw-py-10 tw-text-center">
                                    <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-gray-300 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="tw-text-sm tw-text-gray-500">No logs for today. Select a task and clock in to start.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- ===================== BACKDATE MODE ===================== --}}

        {{-- Backdate Warning Banner --}}
        <div class="tw-mb-6 tw-bg-yellow-50 tw-border tw-border-yellow-200 tw-rounded-lg tw-p-4">
            <div class="tw-flex tw-items-start tw-gap-3">
                <svg class="tw-h-5 tw-w-5 tw-text-yellow-500 tw-flex-shrink-0 tw-mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="tw-text-sm tw-text-yellow-700">
                    <strong>Backdate Mode</strong> — Entries for past dates require <strong>Manager Approval</strong> before counting toward project progress.
                </p>
            </div>
        </div>

        {{-- Backdate Form Card --}}
        <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-p-6 tw-mb-6">
            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-mb-5">Submit Backdate Request</h3>
            <div class="tw-grid tw-grid-cols-1 tw-gap-5">

                {{-- Task - Full Width --}}
                <div class="tw-col-span-full">
                    <label for="backdateTaskId" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Select Task / Project</label>
                    <select id="backdateTaskId" wire:model="taskId"
                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                        <option value="">-- Choose Task --</option>
                        @foreach($myTasks as $task)
                            <option value="{{ $task->id }}">{{ $task->wbs_code }} — {{ $task->name }}</option>
                        @endforeach
                    </select>
                    @error('taskId') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Log Date, Clock In, Clock Out - Split Columns --}}
                <div class="tw-grid sm:tw-grid-cols-3 tw-gap-4">
                    <div>
                        <label for="backdateDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Log Date</label>
                        <input id="backdateDate" type="date" wire:model="backdateDate"
                               max="{{ \Carbon\Carbon::yesterday('Asia/Jakarta')->format('Y-m-d') }}"
                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                        @error('backdateDate') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="clockIn" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Clock In</label>
                        <input id="clockIn" type="time" wire:model="clockIn"
                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                        @error('clockIn') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="clockOut" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Clock Out</label>
                        <input id="clockOut" type="time" wire:model="clockOut"
                               class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                        @error('clockOut') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Progress Increment - Full Width --}}
                <div class="tw-col-span-full">
                    <label for="progressIncrement" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Progress Increment (%)</label>
                    <input id="progressIncrement" type="number" wire:model="progressIncrement"
                           min="0" max="100" step="0.5"
                           class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm">
                    @error('progressIncrement') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Notes / Description - Full Width Textarea --}}
                <div class="tw-col-span-full">
                    <label for="backdateNotes" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Notes / Description <span class="tw-text-red-500">*</span></label>
                    <textarea id="backdateNotes" rows="3" wire:model="notes"
                              placeholder="Describe what you worked on..."
                              class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] sm:tw-text-sm"
                              style="min-height: 5rem;"></textarea>
                    @error('notes') <span class="tw-text-red-500 tw-text-xs tw-mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Action Footer --}}
            <div class="tw-flex tw-justify-end tw-pt-5 tw-mt-5 tw-border-t tw-border-gray-100">
                <button type="button" wire:click="submitBackdate"
                        class="tw-inline-flex tw-items-center tw-px-5 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition
                               tw-bg-[#174D9D] hover:tw-bg-[#123d7e] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D]">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Submit Log
                </button>
            </div>
        </div>

        {{-- My Backdate Requests Table --}}
        @if($myBackdateRequests->isNotEmpty())
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">My Backdate Requests</h3>
                </div>
                <div class="tw-overflow-x-auto">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                        <thead class="tw-bg-gray-50">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Date</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Task</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Time</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Progress</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-gray-200">
                            @foreach($myBackdateRequests as $req)
                                <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-900 tw-font-medium">{{ \Carbon\Carbon::parse($req->log_date)->format('D, d M Y') }}</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-600">{{ $req->task->name ?? '—' }}</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-text-gray-600">
                                        {{ $req->clock_in ? \Carbon\Carbon::parse($req->clock_in)->format('H:i') : '' }} –
                                        {{ $req->clock_out ? \Carbon\Carbon::parse($req->clock_out)->format('H:i') : '' }}
                                    </td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm tw-font-semibold tw-text-[#174D9D]">+{{ $req->progress_increment }}%</td>
                                    <td class="tw-px-6 tw-py-3 tw-text-sm">
                                        @if($req->approval_status === 'pending')
                                            <span class="tw-inline-flex tw-items-center tw-bg-yellow-100 tw-text-yellow-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Pending</span>
                                        @elseif($req->approval_status === 'approved')
                                            <span class="tw-inline-flex tw-items-center tw-bg-green-100 tw-text-green-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Approved</span>
                                        @else
                                            <span class="tw-inline-flex tw-items-center tw-bg-red-100 tw-text-red-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Rejected</span>
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
