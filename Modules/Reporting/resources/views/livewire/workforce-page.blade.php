<div class="tw-font-sans">
    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Workforce Analytics</span>
        </nav>
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Workforce Analytics</h1>
        <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Monitor actual staff utilization based on attendance data.</p>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="tw-bg-white tw-p-4 tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-mb-6">
        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-end tw-flex-wrap tw-gap-4">
            {{-- Month --}}
            <div class="tw-flex-1 tw-min-w-0">
                <label for="selectedMonth" class="tw-block tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-1.5">Month</label>
                <select wire:model.live="selectedMonth" id="selectedMonth"
                    class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D]">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Year --}}
            <div class="tw-flex-1 tw-min-w-0">
                <label for="selectedYear" class="tw-block tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-1.5">Year</label>
                <select wire:model.live="selectedYear" id="selectedYear"
                    class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D]">
                    @foreach(range(now()->year - 2, now()->year + 2) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Multi-Select User Dropdown --}}
            <div class="tw-flex-shrink-0" x-data="{ open: false }" @click.away="open = false">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-1.5">Filter Users</label>
                <div class="tw-relative">
                    <button @click="open = !open" type="button"
                            class="tw-bg-white tw-border tw-border-gray-300 tw-text-gray-700 tw-px-4 tw-py-2 tw-rounded-md tw-shadow-sm tw-flex tw-items-center tw-gap-2 tw-text-sm tw-min-w-[200px] tw-justify-between hover:tw-border-gray-400 tw-transition-colors">
                        <span class="tw-flex tw-items-center tw-gap-2">
                            <svg class="tw-w-4 tw-h-4 tw-text-gray-400 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ count($selectedUsers) }} of {{ $availableUsers->count() }} selected</span>
                        </span>
                        <svg class="tw-w-4 tw-h-4 tw-text-gray-400 tw-transition-transform tw-flex-shrink-0" :class="{ 'tw-rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open" x-cloak
                         x-transition:enter="tw-transition tw-ease-out tw-duration-100"
                         x-transition:enter-start="tw-opacity-0 tw-scale-95"
                         x-transition:enter-end="tw-opacity-100 tw-scale-100"
                         x-transition:leave="tw-transition tw-ease-in tw-duration-75"
                         x-transition:leave-start="tw-opacity-100 tw-scale-100"
                         x-transition:leave-end="tw-opacity-0 tw-scale-95"
                         class="tw-absolute tw-right-0 tw-mt-2 tw-w-72 tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-shadow-xl tw-z-50 tw-overflow-hidden">

                        {{-- Select All / Deselect All --}}
                        <div class="tw-flex tw-items-center tw-justify-between tw-px-3 tw-py-2 tw-bg-gray-50 tw-border-b tw-border-gray-200">
                            <span class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Select Users</span>
                            <div class="tw-flex tw-gap-2">
                                <button type="button"
                                        wire:click="$set('selectedUsers', {{ $availableUsers->pluck('id')->map(fn($id) => (string) $id)->toJson() }})"
                                        class="tw-text-xs tw-font-medium hover:tw-underline" style="color: #174D9D;">All</button>
                                <span class="tw-text-gray-300">|</span>
                                <button type="button"
                                        wire:click="$set('selectedUsers', [])"
                                        class="tw-text-xs tw-font-medium tw-text-gray-500 hover:tw-text-gray-700 hover:tw-underline">None</button>
                            </div>
                        </div>

                        {{-- User List --}}
                        <div class="tw-max-h-60 tw-overflow-y-auto tw-p-1">
                            @foreach($availableUsers as $u)
                                <label class="tw-flex tw-items-center tw-gap-2.5 tw-px-3 tw-py-2 hover:tw-bg-gray-50 tw-cursor-pointer tw-rounded tw-text-sm tw-transition-colors">
                                    <input type="checkbox"
                                           wire:model.live="selectedUsers"
                                           value="{{ $u->id }}"
                                           class="tw-w-4 tw-h-4 tw-rounded tw-border-gray-300 tw-shadow-sm focus:tw-ring-[#174D9D]"
                                           style="color: #174D9D;">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-min-w-0">
                                        <div class="tw-w-6 tw-h-6 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-[10px] tw-font-bold tw-text-white tw-flex-shrink-0" style="background-color: #174D9D;">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <span class="tw-truncate tw-text-gray-700">{{ $u->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sync Button --}}
            <div class="tw-flex-shrink-0">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-transparent tw-mb-1.5">&nbsp;</label>
                <button wire:click="syncData"
                        wire:loading.attr="disabled"
                        wire:target="syncData"
                        class="tw-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-md tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                        style="background-color: #174D9D;"
                        onmouseover="this.style.backgroundColor='#123d7e'"
                        onmouseout="this.style.backgroundColor='#174D9D'">
                    {{-- Default Icon --}}
                    <span wire:loading.remove wire:target="syncData">
                        <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </span>
                    {{-- Loading Spinner --}}
                    <span wire:loading wire:target="syncData">
                        <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2 tw-flex-shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="syncData">Sync Data</span>
                    <span wire:loading wire:target="syncData">Syncing...</span>
                </button>
            </div>

            {{-- Export Button --}}
            <div class="tw-flex-shrink-0">
                <label class="tw-block tw-text-xs tw-font-semibold tw-text-transparent tw-mb-1.5">&nbsp;</label>
                <button wire:click="exportExcel"
                        wire:loading.attr="disabled"
                        class="tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-md tw-text-sm tw-font-medium tw-shadow-sm tw-transition-colors disabled:tw-opacity-50 disabled:tw-cursor-not-allowed">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span wire:loading.remove wire:target="exportExcel">Export to Excel</span>
                    <span wire:loading wire:target="exportExcel">Generating...</span>
                </button>
            </div>
        </div>

        {{-- Sync Flash Message --}}
        @if($syncMessage)
            <div class="tw-mt-3 tw-px-4 tw-py-2.5 tw-rounded-md tw-text-sm tw-font-medium tw-flex tw-items-center tw-gap-2
                {{ $syncMessageType === 'success' ? 'tw-bg-emerald-50 tw-text-emerald-700 tw-border tw-border-emerald-200' : 'tw-bg-red-50 tw-text-red-700 tw-border tw-border-red-200' }}">
                @if($syncMessageType === 'success')
                    <svg class="tw-w-4 tw-h-4 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="tw-w-4 tw-h-4 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                {{ $syncMessage }}
            </div>
        @endif
    </div>

    {{-- ── Data Table Card ── --}}
    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
        {{-- Loading Overlay --}}
        <div wire:loading.flex wire:target="selectedMonth, selectedYear, syncData, selectedUsers"
             class="tw-absolute tw-inset-0 tw-bg-white/60 tw-z-10 tw-items-center tw-justify-center tw-backdrop-blur-[1px]">
            <div class="tw-flex tw-items-center tw-gap-2.5 tw-bg-white tw-px-4 tw-py-2.5 tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-100">
                <svg class="tw-animate-spin tw-h-4 tw-w-4" style="color: #174D9D;" fill="none" viewBox="0 0 24 24">
                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="tw-text-sm tw-text-gray-600 tw-font-medium">Loading data...</span>
            </div>
        </div>

        <div class="tw-overflow-x-auto">
            <table class="tw-w-full tw-text-sm">
                <thead class="tw-bg-gray-50">
                    <tr>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Name</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Role</th>
                        <th class="tw-text-center tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Working Days</th>
                        <th class="tw-text-center tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Attended</th>
                        <th class="tw-text-center tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Absent</th>
                        <th class="tw-text-center tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Utilization (%)</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Project Breakdown</th>
                    </tr>
                </thead>
                <tbody class="tw-divide-y tw-divide-gray-200">
                    @forelse($summaries as $summary)
                        <tr class="hover:tw-bg-gray-50/60 tw-transition">
                            {{-- Name --}}
                            <td class="tw-px-6 tw-py-4">
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white tw-flex-shrink-0" style="background-color: #174D9D;">
                                        {{ strtoupper(substr($summary->user->name, 0, 2)) }}
                                    </div>
                                    <div class="tw-min-w-0">
                                        <div class="tw-font-medium tw-text-gray-900">{{ $summary->user->name }}</div>
                                        <div class="tw-text-xs tw-text-gray-400">{{ $summary->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="tw-px-6 tw-py-4">
                                @php $roles = $summary->user->getRoleNames(); @endphp
                                @if($roles->isNotEmpty())
                                    @foreach($roles as $role)
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-blue-50 tw-text-blue-700">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="tw-text-xs tw-text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Working Days --}}
                            <td class="tw-px-6 tw-py-4 tw-text-center">
                                <span class="tw-font-semibold tw-text-gray-700">{{ $summary->total_working_days }}</span>
                            </td>

                            {{-- Attended --}}
                            <td class="tw-px-6 tw-py-4 tw-text-center">
                                <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-full tw-bg-emerald-50 tw-text-sm tw-font-semibold tw-text-emerald-700">
                                    {{ $summary->attended_days }}
                                </span>
                            </td>

                            {{-- Absent --}}
                            <td class="tw-px-6 tw-py-4 tw-text-center">
                                <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-full tw-text-sm tw-font-semibold {{ $summary->absent_days > 0 ? 'tw-bg-red-50 tw-text-red-700' : 'tw-bg-gray-50 tw-text-gray-500' }}">
                                    {{ $summary->absent_days }}
                                </span>
                            </td>

                            {{-- Utilization (%) --}}
                            <td class="tw-px-6 tw-py-4 tw-text-center">
                                @php
                                    $util = (float) $summary->total_utilization;
                                    $utilClass = match(true) {
                                        $util >= 90 => 'tw-bg-emerald-50 tw-text-emerald-700',
                                        $util >= 70 => 'tw-bg-amber-50 tw-text-amber-700',
                                        default => 'tw-bg-red-50 tw-text-red-700',
                                    };
                                @endphp
                                <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold {{ $utilClass }}">
                                    {{ number_format($util, 1) }}%
                                </span>
                            </td>

                            {{-- Project Breakdown --}}
                            <td class="tw-px-6 tw-py-4">
                                @if(!empty($summary->project_details))
                                    <div class="tw-flex tw-flex-wrap tw-gap-1.5">
                                        @foreach($summary->project_details as $project)
                                            <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-1 tw-rounded-md tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-700 tw-border tw-border-gray-200"
                                                  title="{{ $project['project_name'] }}: {{ $project['days'] }} day(s), coefficient {{ $project['coefficient'] }}">
                                                <span class="tw-font-semibold" style="color: #174D9D;">{{ $project['project_name'] }}</span>
                                                <span class="tw-mx-1 tw-text-gray-300">|</span>
                                                {{ $project['days'] }}d ({{ $project['coefficient'] }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="tw-text-xs tw-text-gray-400 tw-italic">No project logs</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tw-px-6 tw-py-12 tw-text-center">
                                <div class="tw-flex tw-flex-col tw-items-center">
                                    <div class="tw-w-12 tw-h-12 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mb-3" style="background-color: rgba(23, 77, 157, 0.07);">
                                        <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="tw-text-sm tw-font-medium tw-text-gray-500">No summary data found for this period.</p>
                                    <p class="tw-text-xs tw-text-gray-400 tw-mt-1">Click <strong>"Sync Data"</strong> to generate utilization data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('update-analytics-chart', (event) => {
                console.log('Chart update event received:', event);
            });
        });
    </script>
</div>
