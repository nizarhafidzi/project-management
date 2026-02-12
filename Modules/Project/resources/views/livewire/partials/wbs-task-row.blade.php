{{-- Recursive task row partial --}}
@php
    $hasChildren = $task->childrenRecursive && $task->childrenRecursive->count() > 0;
    $isExpanded = in_array($task->id, $expandedNodes);
    $indent = $depth * 1.5; // rem units for indentation
@endphp

<div class="tw-group" wire:key="task-{{ $task->id }}">
    <div class="tw-grid tw-grid-cols-12 tw-gap-2 tw-px-3 tw-py-2.5 tw-items-center hover:tw-bg-gray-50 tw-transition-colors">
        {{-- Task Name with indent & expand toggle --}}
        <div class="tw-col-span-4 tw-flex tw-items-center tw-min-w-0" style="padding-left: {{ $indent }}rem">
            @if ($hasChildren)
                <button wire:click="toggleNode({{ $task->id }})"
                        class="tw-flex-shrink-0 tw-w-5 tw-h-5 tw-mr-1.5 tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors tw-rounded focus:tw-outline-none">
                    <svg class="tw-w-5 tw-h-5 tw-transform tw-transition-transform {{ $isExpanded ? 'tw-rotate-90' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <span class="tw-flex-shrink-0 tw-w-5 tw-h-5 tw-mr-1.5 tw-inline-flex tw-items-center tw-justify-center">
                    <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-gray-300"></span>
                </span>
            @endif

            <div class="tw-min-w-0">
                <div class="tw-flex tw-items-center tw-space-x-2">
                    <span class="tw-inline-flex tw-items-center tw-px-1.5 tw-py-0.5 tw-rounded tw-text-xs tw-font-mono tw-font-medium tw-bg-indigo-50 tw-text-indigo-700">
                        {{ $task->wbs_code }}
                    </span>
                    <span class="tw-text-sm tw-text-gray-900 tw-truncate tw-font-medium">{{ $task->name }}</span>
                </div>
                @if ($task->acc_file_name)
                    <p class="tw-text-xs tw-text-gray-400 tw-truncate tw-mt-0.5">📎 {{ $task->acc_file_name }}</p>
                @endif
            </div>
        </div>

        {{-- Assigned To --}}
        <div class="tw-col-span-2 tw-text-center">
            @if($task->user)
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                    {{ $task->user->name }}
                </span>
            @else
                <span class="tw-text-xs tw-text-gray-400">-</span>
            @endif
        </div>

        {{-- Weight --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-sm tw-font-medium tw-text-gray-700">{{ number_format($task->weight, 1) }}%</span>
        </div>

        {{-- Start Date --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-sm tw-text-gray-600">{{ $task->start_date->format('d M Y') }}</span>
        </div>

        {{-- End Date --}}
        <div class="tw-col-span-1 tw-text-center">
            <span class="tw-text-sm tw-text-gray-600">{{ $task->end_date->format('d M Y') }}</span>
        </div>

        {{-- Progress --}}
        <div class="tw-col-span-1 tw-text-center">
            <div class="tw-flex tw-items-center tw-justify-center tw-space-x-1">
                <div class="tw-w-12 tw-bg-gray-200 tw-rounded-full tw-h-1.5">
                    <div class="tw-bg-indigo-500 tw-h-1.5 tw-rounded-full tw-transition-all" style="width: {{ min($task->total_progress, 100) }}%"></div>
                </div>
                <span class="tw-text-xs tw-text-gray-500">{{ number_format($task->total_progress, 0) }}%</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="tw-col-span-2 tw-text-right tw-space-x-1">
            @if ($canManage)
                {{-- Add Child --}}
                <button wire:click="openCreateModal({{ $task->id }})"
                        class="tw-inline-flex tw-items-center tw-p-1 tw-rounded tw-text-gray-400 hover:tw-text-indigo-600 hover:tw-bg-indigo-50 tw-transition-colors"
                        title="Add Sub-Task">
                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                {{-- Edit --}}
                <button wire:click="openEditModal({{ $task->id }})"
                        class="tw-inline-flex tw-items-center tw-p-1 tw-rounded tw-text-gray-400 hover:tw-text-yellow-600 hover:tw-bg-yellow-50 tw-transition-colors"
                        title="Edit Task">
                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                {{-- Delete --}}
                <button wire:click="deleteTask({{ $task->id }})"
                        wire:confirm="Are you sure you want to delete '{{ $task->name }}'? {{ $hasChildren ? 'All sub-tasks will also be removed.' : '' }}"
                        class="tw-inline-flex tw-items-center tw-p-1 tw-rounded tw-text-gray-400 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors"
                        title="Delete Task">
                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Weight validation for children group --}}
    @if ($hasChildren && $isExpanded)
        @php
            $childWeightInfo = $this->getWeightInfo($task->id);
        @endphp
        @if (!$childWeightInfo['is_valid'])
            <div class="tw-px-3 tw-py-1 tw-text-xs" style="padding-left: {{ ($depth + 1) * 1.5 + 1.75 }}rem">
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-bg-red-50 tw-text-red-700">
                    <svg class="tw-w-3 tw-h-3 tw-mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Sub-tasks: {{ $childWeightInfo['sum'] }}% / {{ $childWeightInfo['expected'] }}% expected
                </span>
            </div>
        @endif
    @endif

    {{-- Recursive children --}}
    @if ($hasChildren && $isExpanded)
        @foreach ($task->childrenRecursive as $child)
            @include('project::livewire.partials.wbs-task-row', ['task' => $child, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>
