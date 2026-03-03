{{-- ═══════════════════════════════════════════════════════════════
     Recursive WBS Task Row
     Aligned with tree grid header: Task(4) | WBS(1) | Weight(1) | Start(1) | End(1) | Progress(1) | Assigned(1) | Actions(2)
═══════════════════════════════════════════════════════════════ --}}
@php
    $hasChildren = $task->childrenRecursive && $task->childrenRecursive->count() > 0;
    $isExpanded = in_array($task->id, $expandedNodes);
    $indent = $depth * 1.5; // rem units for indentation
@endphp

<div wire:key="task-{{ $task->id }}">

    {{-- Row Container --}}
    <div class="tw-grid tw-grid-cols-12 tw-gap-2 tw-bg-white hover:tw-bg-blue-50 tw-transition-colors tw-border-b tw-border-gray-100 tw-px-4 tw-py-3 tw-group tw-items-center">

        {{-- ═══════ TASK NAME COLUMN (col-span-4) ═══════ --}}
        <div class="tw-col-span-4 tw-min-w-0">
            <div class="tw-flex tw-items-center" style="padding-left: {{ $indent }}rem">

                {{-- Expand/Collapse Chevron --}}
                @if ($hasChildren)
                    <button wire:click="toggleNode({{ $task->id }})"
                            class="tw-flex-shrink-0 tw-w-6 tw-h-6 tw-mr-2 tw-flex tw-items-center tw-justify-center tw-text-gray-400 hover:tw-text-[#174D9D] tw-transition-all tw-rounded-md hover:tw-bg-blue-50">
                        <svg class="tw-w-4 tw-h-4 tw-transform tw-transition-transform tw-duration-200 {{ $isExpanded ? 'tw-rotate-90' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @else
                    <span class="tw-flex-shrink-0 tw-w-6 tw-h-6 tw-mr-2 tw-inline-flex tw-items-center tw-justify-center">
                        <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-gray-300"></span>
                    </span>
                @endif

                {{-- Folder / Document Icon --}}
                @if ($hasChildren)
                    <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-flex-shrink-0 tw-text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                @else
                    <svg class="tw-w-4 tw-h-4 tw-mr-2 tw-flex-shrink-0 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                @endif

                {{-- Task Name Text --}}
                <div class="tw-min-w-0 tw-flex-1">
                    <span class="tw-font-medium tw-text-gray-800 tw-text-sm tw-truncate tw-block" title="{{ $task->name }}">
                        {{ $task->name }}
                    </span>
                    {{-- Linked File (shown under task name for compact view) --}}
                    @if ($task->acc_file_name)
                        <div class="tw-flex tw-items-center tw-gap-1.5 tw-mt-0.5">
                            @if($task->acc_file_urn)
                                <a href="{{ route('project.task.workspace', ['project' => $task->project_id, 'task' => $task->id]) }}"
                                   target="_blank"
                                   class="tw-text-xs tw-text-[#174D9D] hover:tw-underline tw-truncate tw-transition-colors"
                                   title="Open 3D Workspace">
                                    📎 {{ \Illuminate\Support\Str::limit($task->acc_file_name, 20) }}
                                </a>
                            @else
                                <span class="tw-text-xs tw-text-gray-400 tw-truncate" title="{{ $task->acc_file_name }}">
                                    📎 {{ \Illuminate\Support\Str::limit($task->acc_file_name, 20) }}
                                </span>
                            @endif
                            <span class="tw-inline-flex tw-items-center tw-px-1 tw-py-0 tw-rounded tw-text-[10px] tw-font-bold tw-bg-blue-100 tw-text-blue-800">
                                V{{ $task->acc_file_version ?? '?' }}
                            </span>
                            @if($task->acc_latest_version > $task->acc_file_version)
                                <span class="tw-inline-flex tw-items-center tw-px-1 tw-py-0 tw-rounded tw-text-[10px] tw-font-bold tw-bg-red-100 tw-text-red-700" title="Update Available">
                                    V{{ $task->acc_latest_version }}!
                                </span>
                                @if($canManage)
                                    <button wire:click="refreshVersion({{ $task->id }})"
                                            class="tw-text-[#174D9D] hover:tw-text-blue-800 tw-transition-colors" title="Update to latest version">
                                        <svg class="tw-w-3 tw-h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════ WBS CODE COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center tw-flex tw-items-center tw-justify-center">
            <span class="tw-bg-gray-100 tw-text-gray-600 tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-mono tw-font-medium">
                {{ $task->wbs_code }}
            </span>
        </div>

        {{-- ═══════ WEIGHT COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-sm tw-font-medium tw-text-gray-700">{{ number_format($task->weight, 1) }}%</span>
            {{-- Mini progress bar representing weight --}}
            <div class="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-1 tw-mt-1 tw-mx-auto" style="max-width: 3rem;">
                <div class="tw-h-1 tw-rounded-full tw-transition-all" style="width: {{ min($task->weight, 100) }}%; background-color: #174D9D;"></div>
            </div>
        </div>

        {{-- ═══════ START DATE COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-gray-500 tw-text-sm">{{ $task->start_date->format('d M Y') }}</span>
        </div>

        {{-- ═══════ END DATE COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-gray-500 tw-text-sm">{{ $task->end_date->format('d M Y') }}</span>
        </div>

        {{-- ═══════ PROGRESS COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center">
            <div class="tw-flex tw-flex-col tw-items-center tw-gap-0.5">
                <span class="tw-text-xs tw-font-medium tw-text-gray-600">{{ number_format($task->total_progress, 0) }}%</span>
                <div class="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-1.5 tw-mx-auto" style="max-width: 3rem;">
                    <div class="tw-bg-emerald-500 tw-h-1.5 tw-rounded-full tw-transition-all" style="width: {{ min($task->total_progress, 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- ═══════ ASSIGNED COLUMN (col-span-1) ═══════ --}}
        <div class="tw-col-span-1 tw-text-center tw-flex tw-items-center tw-justify-center">
            @if($task->user)
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-blue-50 tw-text-blue-700 tw-truncate tw-max-w-full" title="{{ $task->user->name }}">
                    {{ \Illuminate\Support\Str::limit($task->user->name, 8) }}
                </span>
            @else
                <span class="tw-text-xs tw-text-gray-400">—</span>
            @endif
        </div>

        {{-- ═══════ ACTIONS COLUMN (col-span-2) ═══════ --}}
        <div class="tw-col-span-2 tw-text-right">
            <div class="tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity tw-duration-150 tw-flex tw-items-center tw-justify-end tw-gap-0.5">
                @if ($canManage)
                    {{-- Add Child --}}
                    <button wire:click="openCreateModal({{ $task->id }})"
                            class="tw-inline-flex tw-items-center tw-p-1.5 tw-rounded-md tw-text-gray-400 hover:tw-text-[#174D9D] hover:tw-bg-blue-50 tw-transition-colors"
                            title="Add Sub-Task">
                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    {{-- Edit --}}
                    <button wire:click="openEditModal({{ $task->id }})"
                            class="tw-inline-flex tw-items-center tw-p-1.5 tw-rounded-md tw-text-gray-400 hover:tw-text-amber-600 hover:tw-bg-amber-50 tw-transition-colors"
                            title="Edit Task">
                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    {{-- Delete --}}
                    <button wire:click="deleteTask({{ $task->id }})"
                            wire:confirm="Are you sure you want to delete '{{ $task->name }}'? {{ $hasChildren ? 'All sub-tasks will also be removed.' : '' }}"
                            class="tw-inline-flex tw-items-center tw-p-1.5 tw-rounded-md tw-text-gray-400 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors"
                            title="Delete Task">
                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════ WEIGHT VALIDATION WARNING ═══════ --}}
    @if ($hasChildren && $isExpanded)
        @php
            $childWeightInfo = $this->getWeightInfo($task->id);
        @endphp
        @if (!$childWeightInfo['is_valid'])
            <div class="tw-px-4 tw-py-1.5 tw-bg-red-50/50 tw-border-b tw-border-red-100" style="padding-left: {{ ($depth + 1) * 1.5 + 2.5 }}rem">
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-red-100 tw-text-red-700">
                    <svg class="tw-w-3 tw-h-3 tw-mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Sub-tasks: {{ $childWeightInfo['sum'] }}% / {{ $childWeightInfo['expected'] }}% expected
                </span>
            </div>
        @endif
    @endif

    {{-- ═══════ RECURSIVE CHILDREN ═══════ --}}
    @if ($hasChildren && $isExpanded)
        @foreach ($task->childrenRecursive as $child)
            @include('project::livewire.partials.wbs-task-row', ['task' => $child, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>
