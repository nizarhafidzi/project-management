<div class="tw-font-sans">

    {{-- ═══════════════════════════════════════════════════════════════
         BREADCRUMBS & PAGE HEADER
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="tw-mb-6">
        {{-- Breadcrumbs --}}
        <nav class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-text-gray-500 tw-mb-2">
            <a href="{{ route('dashboard') }}" class="hover:tw-text-[#174D9D] tw-transition-colors">Dashboard</a>
            <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('project.index') }}" class="hover:tw-text-[#174D9D] tw-transition-colors">Projects</a>
            <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('project.show', $project) }}" class="hover:tw-text-[#174D9D] tw-transition-colors">{{ $project->name }}</a>
            <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="tw-text-gray-800 tw-font-medium">WBS</span>
        </nav>

        {{-- Title & Description --}}
        <div class="tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-2">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Work Breakdown Structure</h1>
                <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5">{{ $project->project_code }} — {{ $project->name }}</p>
            </div>
            <a href="{{ route('project.show', $project) }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Project
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         TOOLBAR / ACTION ROW
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="tw-flex tw-flex-wrap tw-gap-3 tw-mb-4">
        @if ($canManage)
            {{-- Primary: Add Root Task --}}
            <button wire:click="openCreateModal"
                    class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors tw-border tw-border-transparent"
                    style="background-color: #174D9D;"
                    onmouseover="this.style.backgroundColor='#123f82'" onmouseout="this.style.backgroundColor='#174D9D'">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Root Task
            </button>

            {{-- Bulk Assign --}}
            @if (count($selectedTasks) > 0)
                <button type="button" 
                        wire:click="openBulkAssignModal"
                        wire:key="bulk-assign-btn-{{ count($selectedTasks) }}"
                        class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors tw-border tw-border-transparent"
                        style="background-color: #3b82f6;"
                        onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Bulk Assign ({{ count($selectedTasks) }})
                </button>

                {{-- Bulk Delete --}}
                <button type="button"
                        wire:click="deleteSelectedTasks"
                        wire:confirm="Are you sure you want to delete the selected tasks? This action cannot be undone and may delete child tasks!"
                        wire:key="bulk-delete-btn-{{ count($selectedTasks) }}"
                        class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-bg-red-600 hover:tw-bg-red-700 tw-shadow-sm tw-transition-colors tw-border tw-border-transparent">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Bulk Delete ({{ count($selectedTasks) }})
                </button>
            @endif
        @endif

        {{-- Secondary: Expand All --}}
        <button wire:click="expandAll"
                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
            <svg class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
            </svg>
            Expand All
        </button>

        {{-- Secondary: Collapse All --}}
        <button wire:click="collapseAll"
                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
            <svg class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
            </svg>
            Collapse All
        </button>

        @hasanyrole('Superadmin|Manager|Team Leader')
            {{-- Secondary: Import/Export Dropdown --}}
            <div class="tw-relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                        class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                    <svg class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Import / Export
                    <svg class="tw-w-4 tw-h-4 tw-ml-1.5 -tw-mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition
                     class="tw-absolute tw-right-0 tw-mt-2 tw-w-52 tw-bg-white tw-rounded-lg tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-z-50">
                    <div class="tw-py-1">
                        <button @click="$dispatch('open-import-modal'); open = false"
                                class="tw-flex tw-items-center tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-50 tw-transition-colors">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import Excel (TIDP/MIDP)
                        </button>
                        <div class="tw-border-t tw-border-gray-100 tw-my-1"></div>
                        <button wire:click="downloadTemplate" @click="open = false"
                                class="tw-flex tw-items-center tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-blue-700 hover:tw-bg-blue-50 tw-transition-colors tw-font-medium">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download TIDP/MIDP Template
                        </button>
                        <div class="tw-border-t tw-border-gray-100 tw-my-1"></div>
                        <button wire:click="exportWbs" @click="open = false"
                                class="tw-flex tw-items-center tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-green-700 hover:tw-bg-green-50 tw-transition-colors tw-font-medium">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export WBS (.xlsx)
                        </button>
                    </div>
                </div>
            </div>
        @endhasanyrole
        @if ($canManage)
            {{-- Secondary: Check BIM Updates --}}
            <button wire:click="checkForUpdates" wire:loading.attr="disabled"
                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                <svg wire:loading.remove wire:target="checkForUpdates" class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <svg wire:loading wire:target="checkForUpdates" class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-1.5" fill="none" viewBox="0 0 24 24">
                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="checkForUpdates">Check BIM Updates</span>
                <span wire:loading wire:target="checkForUpdates">Checking...</span>
            </button>

            {{-- Secondary: Recalculate S-Curve --}}
            <button wire:click="recalculateSCurve" wire:loading.attr="disabled"
                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                <svg wire:loading.remove wire:target="recalculateSCurve" class="tw-w-4 tw-h-4 tw-mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <svg wire:loading wire:target="recalculateSCurve" class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-1.5" fill="none" viewBox="0 0 24 24">
                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Recalculate S-Curve
            </button>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         FLASH MESSAGES & WARNINGS
    ═══════════════════════════════════════════════════════════════ --}}
    @if (session()->has('message'))
        <div class="tw-rounded-lg tw-bg-green-50 tw-p-4 tw-mb-4 tw-border tw-border-green-200">
            <div class="tw-flex tw-items-center">
                <svg class="tw-h-5 tw-w-5 tw-text-green-500 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (!$canManage)
        <div class="tw-rounded-lg tw-bg-amber-50 tw-p-4 tw-mb-4 tw-border tw-border-amber-200">
            <div class="tw-flex tw-items-center">
                <svg class="tw-h-5 tw-w-5 tw-text-amber-500 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-amber-800">
                    You have read-only access. Only Team Leaders, Managers, and Superadmins can manage WBS tasks.
                </p>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         TREE GRID CARD
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-shadow-sm tw-overflow-hidden">

        {{-- Card Header: Title + Weight Badge + Task Count --}}
        <div class="tw-px-5 tw-py-4 tw-flex tw-items-center tw-justify-between tw-border-b tw-border-gray-200 tw-bg-gray-50/50">
            <div class="tw-flex tw-items-center tw-space-x-3">
                <div class="tw-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg" style="background-color: rgba(23, 77, 157, 0.1);">
                    <svg class="tw-w-4 tw-h-4" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">Task Tree</h3>
                @if ($rootTasks->count() > 0)
                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium
                        {{ $rootWeightInfo['is_valid'] ? 'tw-bg-green-100 tw-text-green-800' : 'tw-bg-red-100 tw-text-red-800' }}">
                        Weight: {{ $rootWeightInfo['sum'] }}% / {{ $rootWeightInfo['expected'] }}%
                        @if ($rootWeightInfo['is_valid'])
                            <svg class="tw-ml-1 tw-w-3.5 tw-h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="tw-ml-1 tw-w-3.5 tw-h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </span>
                @endif
            </div>
            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-700">
                {{ $project->tasks()->count() }} total tasks
            </span>
        </div>

        @if ($rootTasks->count() > 0)
            {{-- ═══════════ HORIZONTAL SCROLL WRAPPER ═══════════ --}}
            <div class="tw-w-full tw-overflow-x-auto tw-pb-4">
            <div class="tw-min-w-[1400px] tw-w-full">

            {{-- ═══════════ TREE GRID HEADER ═══════════ --}}
            <div class="tw-hidden md:tw-grid tw-bg-gray-50 tw-border-b tw-border-gray-200 tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider"
                 style="grid-template-columns: minmax(300px, auto) 90px 70px 100px 100px 100px 100px 100px 70px 90px 110px;">

                {{-- Col 0: Task Name --}}
                <div class="tw-flex tw-items-center tw-px-5 tw-py-3">
                    @if ($canManage)
                        <div class="tw-w-4 tw-mr-3 tw-flex-shrink-0"></div>
                    @endif
                    <span class="tw-truncate">Task Name</span>
                </div>

                {{-- Col 1: WBS Code --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">WBS Code</span>
                </div>

                {{-- Col 2: Weight --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Weight</span>
                </div>

                {{-- Col 3: Plan Start --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Plan Start</span>
                </div>

                {{-- Col 4: Plan End --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Plan End</span>
                </div>

                {{-- Col 5: Actual Start --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Actual Start</span>
                </div>

                {{-- Col 6: Actual End --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Actual End</span>
                </div>

                {{-- Col 7: Status --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Status</span>
                </div>

                {{-- Col 8: Progress --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Progress</span>
                </div>

                {{-- Col 9: Assigned --}}
                <div class="tw-flex tw-items-center tw-justify-center tw-px-2 tw-py-3">
                    <span class="tw-truncate">Assigned</span>
                </div>

                {{-- Col 10: Actions --}}
                <div class="tw-flex tw-items-center tw-justify-end tw-px-4 tw-py-3">
                    <span class="tw-truncate">Actions</span>
                </div>
            </div>

            {{-- ═══════════ TASK ROWS ═══════════ --}}
            <div class="tw-divide-y tw-divide-gray-100">
                @foreach ($rootTasks as $task)
                    @include('project::livewire.partials.wbs-task-row', ['task' => $task, 'depth' => 0])
                @endforeach
            </div>

            </div>{{-- end tw-min-w-[1400px] --}}
            </div>{{-- end tw-overflow-x-auto --}}
        @else
            {{-- ═══════════ EMPTY STATE ═══════════ --}}
            <div class="tw-text-center tw-py-16 tw-px-6">
                <div class="tw-mx-auto tw-flex tw-items-center tw-justify-center tw-w-16 tw-h-16 tw-rounded-full tw-bg-gray-100 tw-mb-4">
                    <svg class="tw-h-8 tw-w-8 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">No tasks yet</h3>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Get started by adding your first WBS task.</p>
                @if ($canManage)
                    <div class="tw-mt-6">
                        <button wire:click="openCreateModal"
                                class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-lg tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-transition-colors tw-border tw-border-transparent"
                                style="background-color: #174D9D;"
                                onmouseover="this.style.backgroundColor='#123f82'" onmouseout="this.style.backgroundColor='#174D9D'">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add First Task
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         CREATE / EDIT TASK MODAL
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($showTaskModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-center tw-justify-center tw-min-h-screen tw-px-4 tw-py-6">

                {{-- Backdrop --}}
                <div class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" wire:click="closeModal"></div>

                {{-- Modal Panel --}}
                <div class="tw-relative tw-bg-white tw-rounded-xl tw-shadow-2xl tw-w-full tw-max-w-lg tw-transform tw-transition-all">

                    {{-- Header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">
                                    {{ $isEditing ? 'Edit Task' : 'Add New Task' }}
                                </h3>
                                <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5">
                                    Parent: <span class="tw-font-medium">{{ $parentLabel }}</span>
                                    · WBS Code: <span class="tw-font-mono tw-font-semibold" style="color: #174D9D;">{{ $formWbsCode }}</span>
                                </p>
                            </div>
                            <button wire:click="closeModal" class="tw-p-1 tw-rounded-lg tw-text-gray-400 hover:tw-text-gray-600 hover:tw-bg-gray-100 tw-transition-colors">
                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="tw-px-6 tw-py-5">
                        @if ($errors->any())
                            <div class="tw-mb-5 tw-rounded-lg tw-bg-red-50 tw-border tw-border-red-200 tw-p-4">
                                <div class="tw-flex">
                                    <svg class="tw-h-5 tw-w-5 tw-text-red-400 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="tw-ml-3">
                                        <h3 class="tw-text-sm tw-font-medium tw-text-red-800">There were errors with your submission</h3>
                                        <ul class="tw-mt-2 tw-text-sm tw-text-red-700 tw-list-disc tw-pl-5 tw-space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="tw-space-y-4">
                            {{-- Task Name --}}
                            <div>
                                <x-input-label for="formName" :value="__('Task Name')" />
                                <x-text-input wire:model="formName" id="formName" type="text"
                                              class="tw-mt-1 tw-block tw-w-full" placeholder="e.g. Pekerjaan Persiapan" />
                                <x-input-error :messages="$errors->get('formName')" class="tw-mt-1" />
                            </div>

                            {{-- Weight --}}
                            <div>
                                <x-input-label for="formWeight" :value="__('Weight (%)')" />
                                @php
                                    $isEditingParent = $isEditing && $editingTaskId
                                        ? \Modules\Project\Models\Task::find($editingTaskId)?->children()->exists()
                                        : false;
                                @endphp
                                @if ($isEditingParent)
                                    {{-- Parent task: weight is auto-calculated from children --}}
                                    <div class="tw-mt-1 tw-flex tw-items-center tw-gap-2">
                                        <x-text-input id="formWeight" type="number" step="0.01"
                                                      :value="$formWeight"
                                                      class="tw-block tw-w-full tw-bg-gray-100 tw-text-gray-500 tw-cursor-not-allowed tw-border-gray-200"
                                                      disabled />
                                        <span class="tw-inline-flex tw-items-center tw-gap-1 tw-text-xs tw-text-amber-600 tw-whitespace-nowrap tw-font-medium">
                                            <svg class="tw-w-3.5 tw-h-3.5 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                            </svg>
                                            Auto-sum from sub-tasks
                                        </span>
                                    </div>
                                @else
                                    <x-text-input wire:model="formWeight" id="formWeight" type="number" step="0.01" min="0" max="100"
                                                  class="tw-mt-1 tw-block tw-w-full" placeholder="e.g. 30" />
                                @endif
                                <x-input-error :messages="$errors->get('formWeight')" class="tw-mt-1" />
                            </div>

                            {{-- Date Range --}}
                            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <x-input-label for="formStartDate" :value="__('Start Date')" />
                                    <x-text-input wire:model="formStartDate" id="formStartDate" type="date"
                                                  class="tw-mt-1 tw-block tw-w-full" />
                                    <x-input-error :messages="$errors->get('formStartDate')" class="tw-mt-1" />
                                </div>
                                <div>
                                    <x-input-label for="formEndDate" :value="__('End Date')" />
                                    <x-text-input wire:model="formEndDate" id="formEndDate" type="date"
                                                  class="tw-mt-1 tw-block tw-w-full" />
                                    <x-input-error :messages="$errors->get('formEndDate')" class="tw-mt-1" />
                                </div>
                            </div>

                            {{-- Assigned To (Multi-Select Checkboxes) --}}
                            @if($canManage)
                            <div>
                                <x-input-label :value="__('Assign To')" />
                                <div class="tw-mt-1 tw-max-h-48 tw-overflow-y-auto tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-space-y-2 tw-bg-gray-50/50">
                                    @forelse($projectMembers as $member)
                                        <label for="assignUser{{ $member->id }}" class="tw-flex tw-items-center tw-gap-2.5 tw-cursor-pointer tw-p-1.5 tw-rounded-md hover:tw-bg-blue-50 tw-transition-colors">
                                            <input type="checkbox" wire:model="formAssignedUsers"
                                                   id="assignUser{{ $member->id }}"
                                                   value="{{ $member->id }}"
                                                   class="tw-rounded tw-border-gray-300 tw-text-[#174D9D] tw-shadow-sm focus:tw-ring-[#174D9D]" />
                                            <span class="tw-text-sm tw-text-gray-800 tw-font-medium">{{ $member->name }}</span>
                                            @if($member->pivot && $member->pivot->role_in_project)
                                                <span class="tw-text-xs tw-text-gray-400">({{ $member->pivot->role_in_project }})</span>
                                            @endif
                                        </label>
                                    @empty
                                        <p class="tw-text-xs tw-text-gray-400 tw-text-center tw-py-2">No project members available.</p>
                                    @endforelse
                                </div>
                                <x-input-error :messages="$errors->get('formAssignedUsers')" class="tw-mt-1" />
                            </div>
                            @endif

                            {{-- ACC File Link --}}
                            <div x-data="{ openPicker: @entangle('showFilePicker') }">
                                <x-input-label for="formAccFileName" :value="__('Linked ACC File')" />
                                <div class="tw-mt-1 tw-flex tw-rounded-lg tw-shadow-sm">
                                    <div class="tw-relative tw-flex-grow focus-within:tw-z-10 tw-min-w-0">
                                        <x-text-input wire:model="formAccFileName" id="formAccFileName" type="text" readonly
                                                      class="tw-block tw-w-full tw-rounded-none tw-rounded-l-lg tw-bg-gray-50 tw-text-gray-500 tw-truncate"
                                                      placeholder="No file selected" />
                                    </div>
                                    <button type="button" @click="openPicker = !openPicker"
                                            class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-text-sm tw-font-medium tw-rounded-r-lg tw-text-gray-700 tw-bg-gray-50 hover:tw-bg-gray-100 tw-transition-colors">
                                        <svg class="-tw-ml-1 tw-mr-2 tw-h-4 tw-w-4 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        <span x-text="openPicker ? 'Close Picker' : 'Select File'"></span>
                                    </button>
                                </div>
                                @if($formAccFileUrn)
                                    <p class="tw-mt-1 tw-text-xs tw-text-green-600">
                                        ✓ Linked. Version: {{ $formAccFileVersion ?? 'Unknown' }}
                                    </p>
                                @endif
                                <x-input-error :messages="$errors->get('formAccFileName')" class="tw-mt-1" />

                                {{-- Inline Picker --}}
                                <div x-show="openPicker" x-cloak
                                     class="tw-mt-2 tw-border tw-border-gray-200 tw-rounded-lg tw-bg-gray-50 tw-p-3"
                                     x-transition>
                                    @if($project->acc_project_id)
                                        <livewire:project::acc-file-picker
                                            :projectId="$project->acc_project_id"
                                            :hubId="$project->acc_account_id"
                                            wire:key="acc-picker-{{ $project->id }}"
                                        />
                                    @else
                                        <div class="tw-p-4 tw-text-center tw-text-sm tw-text-red-500">
                                            Project is not linked to ACC.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-gray-50/50 tw-flex tw-justify-end tw-gap-3 tw-rounded-b-xl">
                        <button wire:click="closeModal" type="button"
                                class="tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                            Cancel
                        </button>
                        <button wire:click="saveTask" type="button"
                                class="tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors tw-border tw-border-transparent"
                                style="background-color: #174D9D;"
                                onmouseover="this.style.backgroundColor='#123f82'" onmouseout="this.style.backgroundColor='#174D9D'">
                            {{ $isEditing ? 'Update Task' : 'Create Task' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         IMPORT MODAL
    ═══════════════════════════════════════════════════════════════ --}}
    <div x-data="{ open: false }"
         x-on:open-import-modal.window="open = true"
         style="display: none;"
         x-show="open"
         class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">

        <div class="tw-flex tw-items-center tw-justify-center tw-min-h-screen tw-px-4 tw-py-6">

            {{-- Backdrop --}}
            <div x-show="open" x-transition:enter="tw-ease-out tw-duration-300" x-transition:enter-start="tw-opacity-0" x-transition:enter-end="tw-opacity-100"
                 x-transition:leave="tw-ease-in tw-duration-200" x-transition:leave-start="tw-opacity-100" x-transition:leave-end="tw-opacity-0"
                 class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" @click="open = false"></div>

            {{-- Modal Panel --}}
            <div x-show="open" x-transition:enter="tw-ease-out tw-duration-300" x-transition:enter-start="tw-opacity-0 tw-scale-95" x-transition:enter-end="tw-opacity-100 tw-scale-100"
                 x-transition:leave="tw-ease-in tw-duration-200" x-transition:leave-start="tw-opacity-100 tw-scale-100" x-transition:leave-end="tw-opacity-0 tw-scale-95"
                 class="tw-relative tw-bg-white tw-rounded-xl tw-shadow-2xl tw-w-full tw-max-w-lg tw-transform tw-transition-all">

                {{-- Header --}}
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                    <div class="tw-flex tw-items-center tw-space-x-3">
                        <div class="tw-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-full tw-bg-green-100">
                            <svg class="tw-h-5 tw-w-5 tw-text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Import WBS (TIDP/MIDP)</h3>
                            <p class="tw-text-sm tw-text-gray-500">Upload your TIDP/MIDP Excel file. Only sheets named "TIDP" or "MIDP" will be processed.</p>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="tw-px-6 tw-py-5">
                    <div class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-8 tw-flex tw-flex-col tw-items-center tw-justify-center hover:tw-border-[#174D9D] tw-transition-colors tw-bg-gray-50/50">
                        <svg class="tw-w-10 tw-h-10 tw-text-gray-400 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <input type="file" wire:model="inputTemplate" class="tw-block tw-w-full tw-text-sm tw-text-gray-500
                            file:tw-mr-4 file:tw-py-2 file:tw-px-4
                            file:tw-rounded-lg file:tw-border-0
                            file:tw-text-sm file:tw-font-medium
                            file:tw-bg-blue-50 file:tw-text-[#174D9D]
                            hover:file:tw-bg-blue-100 tw-transition-colors
                        "/>
                        <div wire:loading wire:target="inputTemplate" class="tw-mt-3 tw-text-sm tw-text-gray-500 tw-italic tw-flex tw-items-center">
                            <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('inputTemplate')" class="tw-mt-2" />
                </div>

                {{-- Footer --}}
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-gray-50/50 tw-flex tw-justify-end tw-gap-3 tw-rounded-b-xl">
                    <button @click="open = false" type="button"
                            class="tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                        Cancel
                    </button>
                    <button wire:click="importExcel" wire:loading.attr="disabled" type="button"
                            class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors disabled:tw-opacity-50 disabled:tw-cursor-not-allowed tw-border tw-border-transparent"
                            style="background-color: #174D9D;"
                            onmouseover="this.style.backgroundColor='#123f82'" onmouseout="this.style.backgroundColor='#174D9D'">
                        <span wire:loading.remove wire:target="importExcel">Import</span>
                        <span wire:loading wire:target="importExcel" class="tw-flex tw-items-center">
                            <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         IMPORT SUB TASK MODAL
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($showImportSubTaskModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-center tw-justify-center tw-min-h-screen tw-px-4 tw-py-6">
                {{-- Backdrop --}}
                <div class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" wire:click="closeImportSubTaskModal"></div>

                {{-- Modal Panel --}}
                <div class="tw-relative tw-bg-white tw-rounded-xl tw-shadow-2xl tw-w-full tw-max-w-lg tw-transform tw-transition-all">

                    {{-- Header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-space-x-3">
                            <div class="tw-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-full tw-bg-green-100">
                                <svg class="tw-h-5 tw-w-5 tw-text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Import Sub Tasks</h3>
                                @php
                                    $parentNameForImport = \Modules\Project\Models\Task::find($importParentId)?->name ?? 'Unknown Parent';
                                @endphp
                                <p class="tw-text-sm tw-text-gray-500">Import Sub Tasks for: <span class="tw-font-medium tw-text-gray-800">{{ $parentNameForImport }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="tw-px-6 tw-py-5">
                        <div class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-8 tw-flex tw-flex-col tw-items-center tw-justify-center hover:tw-border-[#174D9D] tw-transition-colors tw-bg-gray-50/50">
                            <svg class="tw-w-10 tw-h-10 tw-text-gray-400 tw-mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <input type="file" wire:model="inputTemplate" class="tw-block tw-w-full tw-text-sm tw-text-gray-500
                                file:tw-mr-4 file:tw-py-2 file:tw-px-4
                                file:tw-rounded-lg file:tw-border-0
                                file:tw-text-sm file:tw-font-medium
                                file:tw-bg-blue-50 file:tw-text-[#174D9D]
                                hover:file:tw-bg-blue-100 tw-transition-colors
                            "/>
                            <div wire:loading wire:target="inputTemplate" class="tw-mt-3 tw-text-sm tw-text-gray-500 tw-italic tw-flex tw-items-center">
                                <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('inputTemplate')" class="tw-mt-2" />
                    </div>

                    {{-- Footer --}}
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-gray-50/50 tw-flex tw-justify-end tw-gap-3 tw-rounded-b-xl">
                        <button wire:click="closeImportSubTaskModal" type="button"
                                class="tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                            Cancel
                        </button>
                        <button wire:click="importSubTaskExcel" wire:loading.attr="disabled" type="button"
                                class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors disabled:tw-opacity-50 disabled:tw-cursor-not-allowed tw-border tw-border-transparent"
                                style="background-color: #174D9D;"
                                onmouseover="this.style.backgroundColor='#123f82'" onmouseout="this.style.backgroundColor='#174D9D'">
                            <span wire:loading.remove wire:target="importSubTaskExcel">Import Sub Tasks</span>
                            <span wire:loading wire:target="importSubTaskExcel" class="tw-flex tw-items-center">
                                <svg class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         BULK ASSIGN MODAL
    ═══════════════════════════════════════════════════════════════ --}}
    @if ($showBulkAssignModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-center tw-justify-center tw-min-h-screen tw-px-4 tw-py-6">
                <div class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" wire:click="closeBulkAssignModal"></div>

                <div class="tw-relative tw-bg-white tw-rounded-xl tw-shadow-2xl tw-w-full tw-max-w-lg tw-transform tw-transition-all">
                    {{-- Header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Bulk Assign Users</h3>
                                <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5">
                                    Assign users to {{ count($selectedTasks) }} selected tasks. <br>
                                    <span class="tw-text-xs tw-text-gray-400">This will not remove existing assignments.</span>
                                </p>
                            </div>
                            <button wire:click="closeBulkAssignModal" class="tw-p-1 tw-rounded-lg tw-text-gray-400 hover:tw-text-gray-600 hover:tw-bg-gray-100 tw-transition-colors">
                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="tw-px-6 tw-py-5">
                        <div class="tw-max-h-60 tw-overflow-y-auto tw-border tw-border-gray-300 tw-rounded-lg tw-p-3 tw-space-y-2 tw-bg-gray-50/50">
                            @forelse($projectMembers as $member)
                                <label for="bulkAssignUser{{ $member->id }}" class="tw-flex tw-items-center tw-gap-2.5 tw-cursor-pointer tw-p-1.5 tw-rounded-md hover:tw-bg-blue-50 tw-transition-colors">
                                    <input type="checkbox" wire:model="bulkAssignUsers"
                                           id="bulkAssignUser{{ $member->id }}"
                                           value="{{ $member->id }}"
                                           class="tw-rounded tw-border-gray-300 tw-text-blue-600 tw-shadow-sm focus:tw-ring-blue-500" />
                                    <span class="tw-text-sm tw-text-gray-800 tw-font-medium">{{ $member->name }}</span>
                                    @if($member->pivot && $member->pivot->role_in_project)
                                        <span class="tw-text-xs tw-text-gray-400">({{ $member->pivot->role_in_project }})</span>
                                    @endif
                                </label>
                            @empty
                                <p class="tw-text-xs tw-text-gray-400 tw-text-center tw-py-2">No project members available.</p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('bulkAssignUsers')" class="tw-mt-1" />
                    </div>

                    {{-- Footer --}}
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-gray-50/50 tw-flex tw-justify-end tw-gap-3 tw-rounded-b-xl">
                        <button wire:click="closeBulkAssignModal" type="button"
                                class="tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                            Cancel
                        </button>
                        <button wire:click="applyBulkAssign" type="button"
                                class="tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors tw-border tw-border-transparent"
                                style="background-color: #3b82f6;"
                                onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                            Apply Assignment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

