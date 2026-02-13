<div>
    <x-slot name="header">
        <div class="tw-flex tw-justify-between tw-items-center">
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                {{ $isEdit ? __('Edit Project') : __('Create Project') }}
            </h2>
            <a href="{{ route('project.index') }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Projects') }}
            </a>
        </div>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-3xl tw-mx-auto sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <form wire:submit="save" class="tw-p-6 tw-space-y-6">

                    {{-- Project Code Preview --}}
                    @if ($project_code)
                        <div class="tw-rounded-md tw-bg-indigo-50 tw-p-4">
                            <div class="tw-flex">
                                <div class="tw-flex-shrink-0">
                                    <svg class="tw-h-5 tw-w-5 tw-text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="tw-ml-3">
                                    <p class="tw-text-sm tw-text-indigo-700">
                                        Generated Project Code:
                                        <span class="tw-font-bold tw-text-indigo-900">{{ $project_code }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Project Type --}}
                    <div>
                        <x-input-label for="project_type" :value="__('Project Type')" />
                        <select wire:model.live="project_type" id="project_type"
                                class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                            <option value="">-- Select Type --</option>
                            @foreach ($projectTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('project_type')" class="tw-mt-2" />
                    </div>

                    {{-- Contract Number --}}
                    <div>
                        <x-input-label for="contract_number" :value="__('Contract Number')" />
                        <x-text-input wire:model.live.debounce.500ms="contract_number" id="contract_number" type="text"
                                      class="tw-mt-1 tw-block tw-w-full" placeholder="e.g., KTR/2026/001" />
                        <x-input-error :messages="$errors->get('contract_number')" class="tw-mt-2" />
                    </div>

                    {{-- Project Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Project Name')" />
                        <x-text-input wire:model.blur="name" id="name" type="text"
                                      class="tw-mt-1 tw-block tw-w-full" placeholder="Enter project name" />
                        <x-input-error :messages="$errors->get('name')" class="tw-mt-2" />
                    </div>

                    {{-- Technical Service --}}
                    <div>
                        <x-input-label for="technical_service" :value="__('Technical Service')" />
                        <select wire:model.blur="technical_service" id="technical_service"
                                class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                            <option value="">-- Select Service --</option>
                            @foreach ($technicalServices as $service)
                                <option value="{{ $service->value }}">{{ $service->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('technical_service')" class="tw-mt-2" />
                    </div>

                    {{-- Project Sector --}}
                    <div>
                        <x-input-label for="sector" :value="__('Project Sector')" />
                        <select wire:model.blur="sector" id="sector"
                                class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                            <option value="">-- Select Sector --</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s->value }}">{{ $s->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('sector')" class="tw-mt-2" />
                    </div>

                    {{-- Status (only visible in edit mode) --}}
                    @if ($isEdit)
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select wire:model.blur="status" id="status"
                                    class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                @foreach ($statuses as $st)
                                    <option value="{{ $st->value }}">{{ $st->value }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="tw-mt-2" />
                        </div>
                    @endif

                    {{-- Start & End Date --}}
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input wire:model.blur="start_date" id="start_date" type="date"
                                          class="tw-mt-1 tw-block tw-w-full" />
                            <x-input-error :messages="$errors->get('start_date')" class="tw-mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input wire:model.blur="end_date" id="end_date" type="date"
                                          class="tw-mt-1 tw-block tw-w-full" />
                            <x-input-error :messages="$errors->get('end_date')" class="tw-mt-2" />
                        </div>
                    </div>

                    {{-- ACC Integration Toggle --}}
                    <div class="tw-pt-6 tw-border-t tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <h3 class="tw-text-lg tw-font-medium tw-leading-6 tw-text-gray-900">Autodesk Construction Cloud Integration</h3>
                                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Connect this project to an ACC project for document management.</p>
                            </div>
                            <div class="tw-flex tw-items-center">
                                <label for="integrateWithAcc" class="tw-flex tw-items-center tw-cursor-pointer">
                                    <div class="tw-relative">
                                        <input type="checkbox" id="integrateWithAcc" wire:model.live="integrateWithAcc" class="tw-sr-only">
                                        <div class="tw-w-10 tw-h-4 tw-bg-gray-400 tw-rounded-full tw-shadow-inner toggler-bg"></div>
                                        <div class="tw-dot tw-absolute tw-w-6 tw-h-6 tw-bg-white tw-rounded-full tw-shadow tw-left-0 tw-top-[-4px] tw-transition"></div>
                                    </div>
                                    <div class="tw-ml-3 tw-text-gray-700 tw-font-medium">
                                        {{ $integrateWithAcc ? 'Enabled' : 'Disabled' }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Integration UI --}}
                        @if ($integrateWithAcc)
                            <div class="tw-mt-6 tw-p-4 tw-bg-blue-50 tw-rounded-md tw-border tw-border-blue-100">
                                {{-- Mode Selection --}}
                                <div class="tw-mb-4">
                                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">Integration Mode</label>
                                    <div class="tw-flex tw-space-x-4">
                                        <label class="tw-inline-flex tw-items-center">
                                            <input type="radio" wire:model.live="accCreationMode" value="existing" class="tw-form-radio tw-text-indigo-600">
                                            <span class="tw-ml-2 tw-text-gray-700">Link Existing Project</span>
                                        </label>
                                        <label class="tw-inline-flex tw-items-center">
                                            <input type="radio" wire:model.live="accCreationMode" value="new" class="tw-form-radio tw-text-indigo-600">
                                            <span class="tw-ml-2 tw-text-gray-700">Create New Project in ACC</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Hub Selection --}}
                                <div class="tw-mb-4">
                                    <x-input-label for="selectedHub" :value="__('Select Hub / Account')" />
                                    <select wire:model.live="selectedHub" id="selectedHub"
                                            class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                        <option value="">-- Select Hub --</option>
                                        @foreach($hubs as $hub)
                                            <option value="{{ $hub['id'] }}">{{ $hub['attributes']['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('selectedHub')" class="tw-mt-2" />
                                </div>

                                {{-- Existing Project Selection --}}
                                @if ($accCreationMode === 'existing')
                                    <div class="tw-mb-4">
                                        <x-input-label for="selectedAccProject" :value="__('Select ACC Project')" />
                                        <select wire:model.live="selectedAccProject" id="selectedAccProject"
                                                class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm"
                                                {{ empty($selectedHub) ? 'disabled' : '' }}>
                                            <option value="">-- Select Project --</option>
                                            @foreach($accProjects as $proj)
                                                <option value="{{ $proj['id'] }}">{{ $proj['attributes']['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('selectedAccProject')" class="tw-mt-2" />
                                    </div>
                                @else
                                    <div class="tw-mb-4 tw-text-sm tw-text-blue-700">
                                        <p>A new project will be created in Autodesk Construction Cloud using the <strong>Project Name</strong>, <strong>Start Date</strong>, and <strong>End Date</strong> entered above.</p>
                                        <p class="tw-mt-1">Assuming <strong>Construction Management</strong> template and <strong>Docs</strong> service activation.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <style>
                        /* Toggle Switch CSS */
                        input:checked ~ .tw-dot {
                            transform: translateX(100%);
                            background-color: #4f46e5; /* indigo-600 */
                        }
                        input:checked ~ .toggler-bg {
                            background-color: #e0e7ff; /* indigo-100 */
                        }
                    </style>

                    {{-- Submit --}}
                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-4 tw-pt-4 tw-border-t tw-border-gray-200">
                        <a href="{{ route('project.index') }}" wire:navigate
                           class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-gray-300 tw-rounded-md tw-font-semibold tw-text-xs tw-text-gray-700 tw-uppercase tw-tracking-widest tw-shadow-sm hover:tw-bg-gray-50 tw-transition tw-ease-in-out tw-duration-150">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ $isEdit ? __('Update Project') : __('Create Project') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
