<div class="tw-font-sans">
    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Workforce Analytics</span>
        </nav>
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Workforce Analytics</h1>
        <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Monitor staff utilization and project allocation across the organization.</p>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="tw-bg-white tw-p-4 tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-mb-6">
        <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-end tw-gap-4">
            {{-- Month --}}
            <div class="tw-flex-1 tw-min-w-0">
                <label for="selectedMonth" class="tw-block tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-1.5">Month</label>
                <select wire:model.live="selectedMonth" id="selectedMonth"
                    class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D]">
                    <option value="">All Months</option>
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
                    <option value="">All Years</option>
                    @foreach(range(now()->year - 2, now()->year + 2) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sector --}}
            <div class="tw-flex-1 tw-min-w-0">
                <label for="sector" class="tw-block tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-1.5">Sector</label>
                <select wire:model.live="sector" id="sector"
                    class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D]">
                    <option value="All">All Sectors</option>
                    @foreach($sectors as $sec)
                        <option value="{{ $sec->value }}">{{ $sec->value }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Export Button --}}
            <div class="tw-flex-shrink-0">
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
    </div>

    {{-- ── Data Table Card ── --}}
    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200 tw-relative tw-overflow-hidden">
        {{-- Loading Overlay --}}
        <div wire:loading.flex wire:target="selectedMonth, selectedYear, sector"
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
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Staff Name</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Active Projects</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Utilization Coeff.</th>
                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="tw-divide-y tw-divide-gray-200">
                    @forelse($staff as $user)
                        <tr class="hover:tw-bg-gray-50/60 tw-cursor-pointer tw-transition" wire:click="openProjectModal({{ $user->id }}, '{{ $user->name }}')">
                            <td class="tw-px-6 tw-py-4">
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <div class="tw-w-8 tw-h-8 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white tw-flex-shrink-0" style="background-color: #174D9D;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="tw-min-w-0">
                                        <div class="tw-font-medium tw-text-gray-900">{{ $user->name }}</div>
                                        <div class="tw-text-xs tw-text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="tw-px-6 tw-py-4">
                                <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-7 tw-h-7 tw-rounded-full tw-bg-blue-50 tw-text-sm tw-font-semibold" style="color: #174D9D;">
                                    {{ $user->active_projects_count }}
                                </span>
                            </td>
                            <td class="tw-px-6 tw-py-4">
                                <span class="tw-font-semibold tw-text-gray-900">{{ number_format($user->utilization_coefficient, 2) }}</span>
                            </td>
                            <td class="tw-px-6 tw-py-4">
                                @php
                                    $statusConfig = match($user->utilization_status) {
                                        'Ideal' => ['tw-bg-emerald-50 tw-text-emerald-700', '●'],
                                        'Moderate' => ['tw-bg-amber-50 tw-text-amber-700', '●'],
                                        'Overload' => ['tw-bg-red-50 tw-text-red-700 tw-animate-pulse', '●'],
                                        default => ['tw-bg-gray-50 tw-text-gray-600', '●'],
                                    };
                                @endphp
                                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold {{ $statusConfig[0] }}">
                                    <span class="tw-text-[8px]">{{ $statusConfig[1] }}</span>
                                    {{ $user->utilization_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="tw-px-6 tw-py-12 tw-text-center">
                                <div class="tw-flex tw-flex-col tw-items-center">
                                    <div class="tw-w-12 tw-h-12 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mb-3" style="background-color: rgba(23, 77, 157, 0.07);">
                                        <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="tw-text-sm tw-font-medium tw-text-gray-500">No staff found matching criteria.</p>
                                    <p class="tw-text-xs tw-text-gray-400 tw-mt-1">Try adjusting filters above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Project Breakdown Modal ── --}}
    @if($showModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4 tw-bg-gray-900/50 tw-backdrop-blur-sm"
             aria-modal="true" role="dialog">
            <div class="tw-relative tw-w-full tw-max-w-3xl tw-bg-white tw-rounded-xl tw-shadow-xl tw-border tw-border-gray-200 tw-overflow-hidden"
                 @click.outside="$wire.closeModal()">
                {{-- Modal Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-bg-gray-50/50">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                            <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-gray-900">Project Breakdown</h3>
                            <p class="tw-text-sm tw-text-gray-500">{{ $selectedStaffName }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" type="button"
                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-text-gray-400 hover:tw-bg-gray-100 hover:tw-text-gray-600 tw-transition-colors">
                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="tw-p-6">
                    @if(count($projectDetails) > 0)
                        <div class="tw-overflow-x-auto tw-rounded-lg tw-border tw-border-gray-200">
                            <table class="tw-w-full tw-text-sm">
                                <thead class="tw-bg-gray-50">
                                    <tr>
                                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Code</th>
                                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Project Name</th>
                                        <th class="tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Sector</th>
                                        <th class="tw-text-right tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-py-3 tw-px-4">Coefficient</th>
                                    </tr>
                                </thead>
                                <tbody class="tw-divide-y tw-divide-gray-200">
                                    @php $totalCoeff = 0; @endphp
                                    @foreach($projectDetails as $project)
                                        <tr class="hover:tw-bg-gray-50/60 tw-transition">
                                            <td class="tw-py-3 tw-px-4">
                                                <span class="tw-font-mono tw-text-xs tw-bg-gray-100 tw-px-2 tw-py-0.5 tw-rounded tw-text-gray-600">{{ $project->project_code }}</span>
                                            </td>
                                            <td class="tw-py-3 tw-px-4 tw-font-medium tw-text-gray-900">{{ $project->name }}</td>
                                            <td class="tw-py-3 tw-px-4 tw-text-gray-500">{{ $project->sector->value }}</td>
                                            <td class="tw-py-3 tw-px-4 tw-text-right tw-font-semibold tw-text-gray-900">
                                                {{ number_format($currentStaffCoefficient, 2) }}
                                                @php $totalCoeff += $currentStaffCoefficient; @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tw-bg-gray-50 tw-border-t-2 tw-border-gray-200">
                                    <tr>
                                        <td colspan="3" class="tw-py-3 tw-px-4 tw-text-right tw-text-sm tw-font-bold tw-text-gray-700">Total Sum:</td>
                                        <td class="tw-py-3 tw-px-4 tw-text-right tw-text-sm tw-font-bold" style="color: #174D9D;">
                                            {{ number_format($totalCoeff, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="tw-text-center tw-py-8">
                             <p class="tw-text-sm tw-text-gray-500">No active projects assigned.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('update-analytics-chart', (event) => {
            console.log('Chart update event received:', event);
        });
    });
</script>
