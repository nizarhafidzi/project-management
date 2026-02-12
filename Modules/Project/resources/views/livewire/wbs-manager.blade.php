<div>
    {{-- Header moved inside component to enabled wire:click --}}
    <div class="tw-bg-white tw-shadow">
        <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-flex tw-justify-between tw-items-center">
                <div>
                    <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                        {{ __('WBS Planning') }}
                    </h2>
                    <p class="tw-mt-1 tw-text-sm tw-text-gray-500">
                        {{ $project->project_code }} — {{ $project->name }}
                    </p>
                </div>
                <div class="tw-flex tw-space-x-3">
                    @if ($canManage)
                        {{-- Import/Export Actions --}}
                        <div class="tw-relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Excel
                                <svg class="tw-w-4 tw-h-4 tw-ml-2 -tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak 
                                class="tw-absolute tw-right-0 tw-mt-2 tw-w-48 tw-bg-white tw-rounded-md tw-shadow-lg tw-origin-top-right tw-ring-1 tw-ring-black tw-ring-opacity-5 focus:tw-outline-none tw-z-50">
                                <div class="tw-py-1">
                                    <button wire:click="downloadTemplate" class="tw-block tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-100">
                                        Download Template
                                    </button>
                                    <button @click="$dispatch('open-import-modal'); open = false" class="tw-block tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-sm tw-text-gray-700 hover:tw-bg-gray-100">
                                        Import Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button wire:click="openCreateModal"
                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-text-sm tw-font-medium tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-transition-colors">
                            <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Root Task
                        </button>
                    @endif
                    <button wire:click="expandAll"
                            class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                        <svg class="tw-w-4 tw-h-4 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        Expand
                    </button>
                    <button wire:click="collapseAll"
                            class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                        <svg class="tw-w-4 tw-h-4 tw-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
                        </svg>
                        Collapse
                    </button>
                    @if ($canManage)
                        <button wire:click="recalculateSCurve" wire:loading.attr="disabled"
                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                            <svg wire:loading.remove wire:target="recalculateSCurve" class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg wire:loading wire:target="recalculateSCurve" class="tw-animate-spin tw-w-4 tw-h-4 tw-mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Recalculate S-Curve
                        </button>
                    @endif
                    <a href="{{ route('project.show', $project) }}" wire:navigate
                       class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                        <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-space-y-6">

            {{-- Flash Messages --}}
            @if (session()->has('message'))
                <div class="tw-rounded-md tw-bg-green-50 tw-p-4">
                    <div class="tw-flex">
                        <svg class="tw-h-5 tw-w-5 tw-text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-green-800">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            {{-- Access warning --}}
            @if (!$canManage)
                <div class="tw-rounded-md tw-bg-yellow-50 tw-p-4">
                    <div class="tw-flex">
                        <svg class="tw-h-5 tw-w-5 tw-text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-yellow-800">
                            You have read-only access. Only Team Leaders, Managers, and Superadmins can manage WBS tasks.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Root Weight Summary --}}
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-px-6 tw-py-4 tw-flex tw-items-center tw-justify-between tw-border-b tw-border-gray-200">
                    <div class="tw-flex tw-items-center tw-space-x-4">
                        <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900">Work Breakdown Structure</h3>
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
                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                        {{ $project->tasks()->count() }} total tasks
                    </span>
                </div>

                <div class="tw-p-6">
                    @if ($rootTasks->count() > 0)
                        {{-- Table Header --}}
                        <div class="tw-grid tw-grid-cols-12 tw-gap-2 tw-px-3 tw-py-2 tw-bg-gray-50 tw-rounded-t-md tw-border tw-border-gray-200 tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">
                            <div class="tw-col-span-4">Task</div>
                            <div class="tw-col-span-2 tw-text-center">Assigned To</div>
                            <div class="tw-col-span-1 tw-text-center">Weight</div>
                            <div class="tw-col-span-1 tw-text-center">Start Date</div>
                            <div class="tw-col-span-1 tw-text-center">End Date</div>
                            <div class="tw-col-span-1 tw-text-center">Progress</div>
                            <div class="tw-col-span-2 tw-text-right">Actions</div>
                        </div>

                        {{-- Task Tree --}}
                        <div class="tw-border tw-border-t-0 tw-border-gray-200 tw-rounded-b-md tw-divide-y tw-divide-gray-100">
                            @foreach ($rootTasks as $task)
                                @include('project::livewire.partials.wbs-task-row', ['task' => $task, 'depth' => 0])
                            @endforeach
                        </div>
                    @else
                        <div class="tw-text-center tw-py-12">
                            <svg class="tw-mx-auto tw-h-12 tw-w-12 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <h3 class="tw-mt-2 tw-text-sm tw-font-medium tw-text-gray-900">No tasks yet</h3>
                            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Get started by adding your first WBS task.</p>
                            @if ($canManage)
                                <div class="tw-mt-6">
                                    <button wire:click="openCreateModal"
                                            class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-transition-colors">
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
            </div>

        </div>
    </div>

    {{-- Create / Edit Task Modal --}}
    @if ($showTaskModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-end tw-justify-center tw-min-h-screen tw-pt-4 tw-px-4 tw-pb-20 tw-text-center sm:tw-block sm:tw-p-0">
                <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity" wire:click="closeModal"></div>
                <div class="tw-inline-block tw-align-bottom tw-bg-white tw-rounded-lg tw-text-left tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-my-8 sm:tw-align-middle sm:tw-max-w-lg sm:tw-w-full">
                    <div class="tw-bg-white tw-px-4 tw-pt-5 tw-pb-4 sm:tw-p-6">
                        <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900 tw-mb-1">
                            {{ $isEditing ? 'Edit Task' : 'Add New Task' }}
                        </h3>
                        <p class="tw-text-sm tw-text-gray-500 tw-mb-4">
                            Parent: <span class="tw-font-medium">{{ $parentLabel }}</span>
                            · WBS Code: <span class="tw-font-mono tw-font-medium tw-text-indigo-600">{{ $formWbsCode }}</span>
                        </p>

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
                                <x-text-input wire:model="formWeight" id="formWeight" type="number" step="0.01" min="0" max="100"
                                              class="tw-mt-1 tw-block tw-w-full" placeholder="e.g. 30" />
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

                            </div>

                            {{-- Assigned To --}}
                            @if($canManage)
                            <div>
                                <x-input-label for="formAssignedTo" :value="__('Assigned To')" />
                                <select wire:model="formAssignedTo" id="formAssignedTo"
                                        class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 sm:tw-text-sm">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($projectMembers as $member)
                                        <option value="{{ $member->id }}">
                                            {{ $member->name }} 
                                            @if($member->pivot && $member->pivot->role_in_project)
                                                ({{ $member->pivot->role_in_project }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('formAssignedTo')" class="tw-mt-1" />
                            </div>
                            @endif

                            {{-- ACC File Name (placeholder) --}}
                            <div>
                                <x-input-label for="formAccFileName" :value="__('ACC File Name (optional)')" />
                                <x-text-input wire:model="formAccFileName" id="formAccFileName" type="text"
                                              class="tw-mt-1 tw-block tw-w-full" placeholder="Manual input — ACC sync coming soon" />
                                <p class="tw-mt-1 tw-text-xs tw-text-gray-400">This will auto-sync with ACC in a future release.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tw-bg-gray-50 tw-px-4 tw-py-3 sm:tw-px-6 sm:tw-flex sm:tw-flex-row-reverse">
                        <button wire:click="saveTask" type="button"
                                class="tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-transparent tw-shadow-sm tw-px-4 tw-py-2 tw-bg-indigo-600 tw-text-base tw-font-medium tw-text-white hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            {{ $isEditing ? 'Update' : 'Create' }}
                        </button>
                        <button wire:click="closeModal" type="button"
                                class="tw-mt-3 tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-gray-300 tw-shadow-sm tw-px-4 tw-py-2 tw-bg-white tw-text-base tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 sm:tw-mt-0 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Import Modal --}}
    <div x-data="{ open: false }" 
         x-on:open-import-modal.window="open = true"
         style="display: none;" 
         x-show="open" 
         class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
        
        <div class="tw-flex tw-items-end tw-justify-center tw-min-h-screen tw-pt-4 tw-px-4 tw-pb-20 tw-text-center sm:tw-block sm:tw-p-0">
            <div x-show="open" x-transition:enter="tw-ease-out tw-duration-300" x-transition:enter-start="tw-opacity-0" x-transition:enter-end="tw-opacity-100" x-transition:leave="tw-ease-in tw-duration-200" x-transition:leave-start="tw-opacity-100" x-transition:leave-end="tw-opacity-0" class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity" @click="open = false"></div>

            <div x-show="open" x-transition:enter="tw-ease-out tw-duration-300" x-transition:enter-start="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95" x-transition:enter-end="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100" x-transition:leave="tw-ease-in tw-duration-200" x-transition:leave-start="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100" x-transition:leave-end="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95" class="tw-inline-block tw-align-bottom tw-bg-white tw-rounded-lg tw-px-4 tw-pt-5 tw-pb-4 tw-text-left tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-my-8 sm:tw-align-middle sm:tw-max-w-lg sm:tw-w-full sm:tw-p-6">
                
                <div>
                    <div class="tw-mx-auto tw-flex tw-items-center tw-justify-center tw-h-12 tw-w-12 tw-rounded-full tw-bg-green-100">
                        <svg class="tw-h-6 tw-w-6 tw-text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div class="tw-mt-3 tw-text-center sm:tw-mt-5">
                        <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900" id="modal-title">
                            Import WBS Implementation
                        </h3>
                        <div class="tw-mt-2">
                            <p class="tw-text-sm tw-text-gray-500">
                                Upload your Excel file to bulk create or update tasks.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="tw-mt-5 sm:tw-mt-6">
                    <div class="tw-space-y-4">
                        <div class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-md tw-p-6 tw-flex tw-flex-col tw-items-center tw-justify-center hover:tw-border-indigo-500 tw-transition-colors">
                            <input type="file" wire:model="inputTemplate" class="tw-block tw-w-full tw-text-sm tw-text-slate-500
                                file:tw-mr-4 file:tw-py-2 file:tw-px-4
                                file:tw-rounded-full file:tw-border-0
                                file:tw-text-sm file:tw-font-semibold
                                file:tw-bg-indigo-50 file:tw-text-indigo-700
                                hover:file:tw-bg-indigo-100
                            "/>
                            <div wire:loading wire:target="inputTemplate" class="tw-mt-2 tw-text-sm tw-text-gray-500 tw-italic">
                                Uploading...
                            </div>
                        </div>

                        <div class="tw-flex tw-justify-end tw-pt-4">
                            <button @click="open = false" type="button" class="tw-mr-3 tw-bg-white tw-py-2 tw-px-4 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50 focus:tw-outline-none">
                                Cancel
                            </button>
                            <button wire:click="importExcel" wire:loading.attr="disabled" type="button" class="tw-inline-flex tw-justify-center tw-py-2 tw-px-4 tw-border tw-border-transparent tw-shadow-sm tw-text-sm tw-font-medium tw-rounded-md tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 disabled:tw-opacity-50 disabled:tw-cursor-not-allowed">
                                <span wire:loading.remove wire:target="importExcel">Import</span>
                                <span wire:loading wire:target="importExcel">Processing...</span>
                            </button>
                        </div>
                         <x-input-error :messages="$errors->get('inputTemplate')" class="tw-mt-1" />
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
