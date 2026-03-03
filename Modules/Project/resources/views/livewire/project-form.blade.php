<div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-py-8 tw-font-sans">
    {{-- Page Header --}}
    <div class="tw-mb-8 tw-flex tw-flex-col tw-gap-4 md:tw-flex-row md:tw-items-center md:tw-justify-between">
        <div>
            <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
                <span class="tw-mx-2">/</span>
                <a href="{{ route('project.index') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Projects</a>
                <span class="tw-mx-2">/</span>
                <span class="tw-text-gray-900">{{ $isEdit ? 'Edit Project' : 'New Project' }}</span>
            </nav>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-tracking-tight">
                {{ $isEdit ? __('Edit Project') : __('Create Project') }}
            </h1>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">{{ $isEdit ? 'Update the project details below.' : 'Fill in the details to create a new project.' }}</p>
        </div>
        <a href="{{ route('project.index') }}" wire:navigate
           class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-lg tw-bg-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-medium tw-text-gray-700 tw-border tw-border-gray-300 tw-shadow-sm hover:tw-bg-gray-50 tw-transition-all focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-gray-100">
            <svg class="tw-mr-2 tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('Back to List') }}
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
        <form wire:submit="save">
            {{-- Project Code Preview --}}
            @if ($project_code)
                <div class="tw-px-6 tw-pt-6">
                    <div class="tw-rounded-lg tw-p-4 tw-flex tw-items-center tw-gap-3" style="background-color: rgba(23, 77, 157, 0.06); border: 1px solid rgba(23, 77, 157, 0.15);">
                        <div class="tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-h-9 tw-w-9 tw-rounded-lg" style="background-color: rgba(23, 77, 157, 0.12);">
                            <svg class="tw-h-5 tw-w-5" style="color: #174D9D;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Generated Project Code</p>
                            <p class="tw-text-base tw-font-bold" style="color: #174D9D;">{{ $project_code }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Fields - 2 Column Grid --}}
            <div class="tw-p-6">
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-x-6 tw-gap-y-5">

                    {{-- Project Type --}}
                    <div>
                        <label for="project_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Project Type') }} <span class="tw-text-red-500">*</span></label>
                        <select wire:model.live="project_type" id="project_type"
                                class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                            <option value="">-- Select Type --</option>
                            @foreach ($projectTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('project_type')" class="tw-mt-1.5" />
                    </div>

                    {{-- Contract Number --}}
                    <div>
                        <label for="contract_number" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Contract Number') }} <span class="tw-text-red-500">*</span></label>
                        <input wire:model.live.debounce.500ms="contract_number" id="contract_number" type="text"
                               class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors"
                               placeholder="e.g., KTR/2026/001" />
                        <x-input-error :messages="$errors->get('contract_number')" class="tw-mt-1.5" />
                    </div>

                    {{-- Project Name (full width) --}}
                    <div class="sm:tw-col-span-2">
                        <label for="name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Project Name') }} <span class="tw-text-red-500">*</span></label>
                        <input wire:model.blur="name" id="name" type="text"
                               class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors"
                               placeholder="Enter project name" />
                        <x-input-error :messages="$errors->get('name')" class="tw-mt-1.5" />
                    </div>

                    {{-- Technical Service --}}
                    <div>
                        <label for="technical_service" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Technical Service') }} <span class="tw-text-red-500">*</span></label>
                        <select wire:model.blur="technical_service" id="technical_service"
                                class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                            <option value="">-- Select Service --</option>
                            @foreach ($technicalServices as $service)
                                <option value="{{ $service->value }}">{{ $service->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('technical_service')" class="tw-mt-1.5" />
                    </div>

                    {{-- Project Sector --}}
                    <div>
                        <label for="sector" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Project Sector') }} <span class="tw-text-red-500">*</span></label>
                        <select wire:model.blur="sector" id="sector"
                                class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                            <option value="">-- Select Sector --</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s->value }}">{{ $s->value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('sector')" class="tw-mt-1.5" />
                    </div>

                    {{-- Status (only visible in edit mode) --}}
                    @if ($isEdit)
                        <div class="sm:tw-col-span-2">
                            <label for="status" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Status') }}</label>
                            <select wire:model.blur="status" id="status"
                                    class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                                @foreach ($statuses as $st)
                                    <option value="{{ $st->value }}">{{ $st->value }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="tw-mt-1.5" />
                        </div>
                    @endif

                    {{-- Start Date --}}
                    <div>
                        <label for="start_date" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Start Date') }} <span class="tw-text-red-500">*</span></label>
                        <input wire:model.blur="start_date" id="start_date" type="date"
                               class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors" />
                        <x-input-error :messages="$errors->get('start_date')" class="tw-mt-1.5" />
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label for="end_date" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('End Date') }} <span class="tw-text-red-500">*</span></label>
                        <input wire:model.blur="end_date" id="end_date" type="date"
                               class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors" />
                        <x-input-error :messages="$errors->get('end_date')" class="tw-mt-1.5" />
                    </div>
                </div>
            </div>

            {{-- ACC Integration Section --}}
            <div class="tw-border-t tw-border-gray-200">
                <div class="tw-p-6">
                    <div class="tw-bg-gray-50 tw-rounded-md tw-border tw-border-gray-200 tw-p-5">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <div class="tw-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-lg" style="background-color: rgba(23, 77, 157, 0.1);">
                                    <svg class="tw-h-5 tw-w-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">Autodesk Construction Cloud Integration</h3>
                                    <p class="tw-text-xs tw-text-gray-500 tw-mt-0.5">Connect this project to ACC for document management.</p>
                                </div>
                            </div>

                            {{-- Modern Toggle Switch --}}
                            <label for="integrateWithAcc" class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer tw-group">
                                <input type="checkbox" id="integrateWithAcc" wire:model.live="integrateWithAcc" class="tw-sr-only tw-peer">
                                <div class="tw-w-11 tw-h-6 tw-bg-gray-300 tw-rounded-full tw-peer tw-peer-checked:tw-bg-[#174D9D] tw-transition-colors tw-duration-200 tw-after:tw-content-[''] tw-after:tw-absolute tw-after:tw-top-0.5 tw-after:tw-left-[2px] tw-after:tw-bg-white tw-after:tw-rounded-full tw-after:tw-h-5 tw-after:tw-w-5 tw-after:tw-transition-all tw-after:tw-shadow-sm peer-checked:after:tw-translate-x-full"></div>
                                <span class="tw-ml-3 tw-text-sm tw-font-medium {{ $integrateWithAcc ? 'tw-text-[#174D9D]' : 'tw-text-gray-500' }} tw-transition-colors">
                                    {{ $integrateWithAcc ? 'Enabled' : 'Disabled' }}
                                </span>
                            </label>
                        </div>

                        {{-- Integration UI --}}
                        @if ($integrateWithAcc)
                            <div class="tw-mt-5 tw-pt-5 tw-border-t tw-border-gray-200">
                                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-x-6 tw-gap-y-5">
                                    {{-- Mode Selection --}}
                                    <div class="sm:tw-col-span-2">
                                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-3">Integration Mode</label>
                                        <div class="tw-flex tw-flex-wrap tw-gap-4">
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-group">
                                                <input type="radio" wire:model.live="accCreationMode" value="existing"
                                                       class="tw-h-4 tw-w-4 tw-border-gray-300 focus:tw-ring-[#174D9D]"
                                                       style="color: #174D9D;">
                                                <span class="tw-text-sm tw-text-gray-700 group-hover:tw-text-gray-900 tw-transition-colors">Link Existing Project</span>
                                            </label>
                                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-group">
                                                <input type="radio" wire:model.live="accCreationMode" value="new"
                                                       class="tw-h-4 tw-w-4 tw-border-gray-300 focus:tw-ring-[#174D9D]"
                                                       style="color: #174D9D;">
                                                <span class="tw-text-sm tw-text-gray-700 group-hover:tw-text-gray-900 tw-transition-colors">Create New Project in ACC</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Hub Selection --}}
                                    <div class="sm:tw-col-span-2">
                                        <label for="selectedHub" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Select Hub / Account') }}</label>
                                        <select wire:model.live="selectedHub" id="selectedHub"
                                                class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                                            <option value="">-- Select Hub --</option>
                                            @foreach($hubs as $hub)
                                                <option value="{{ $hub['id'] }}">{{ $hub['attributes']['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('selectedHub')" class="tw-mt-1.5" />
                                    </div>

                                    {{-- Existing Project Selection --}}
                                    @if ($accCreationMode === 'existing')
                                        <div class="sm:tw-col-span-2">
                                            <label for="selectedAccProject" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Select ACC Project') }}</label>
                                            <select wire:model.live="selectedAccProject" id="selectedAccProject"
                                                    class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors disabled:tw-bg-gray-100 disabled:tw-cursor-not-allowed"
                                                    {{ empty($selectedHub) ? 'disabled' : '' }}>
                                                <option value="">-- Select Project --</option>
                                                @foreach($accProjects as $proj)
                                                    <option value="{{ $proj['id'] }}">{{ $proj['attributes']['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('selectedAccProject')" class="tw-mt-1.5" />
                                        </div>
                                    @else
                                        <div class="sm:tw-col-span-2 tw-rounded-lg tw-p-4" style="background-color: rgba(23, 77, 157, 0.05); border: 1px solid rgba(23, 77, 157, 0.12);">
                                            <div class="tw-flex tw-items-start tw-gap-3">
                                                <svg class="tw-h-5 tw-w-5 tw-flex-shrink-0 tw-mt-0.5" style="color: #174D9D;" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="tw-text-sm" style="color: #174D9D;">
                                                    <p>A new project will be created in Autodesk Construction Cloud using the <strong>Project Name</strong>, <strong>Start Date</strong>, and <strong>End Date</strong> entered above.</p>
                                                    <p class="tw-mt-1 tw-opacity-80">Assuming <strong>Construction Management</strong> template and <strong>Docs</strong> service activation.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Form Actions Footer --}}
            <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-border-t tw-border-gray-200 tw-bg-gray-50 tw-px-6 tw-py-4">
                <a href="{{ route('project.index') }}" wire:navigate
                   class="tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-medium tw-text-gray-700 tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-gray-300">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                        class="tw-inline-flex tw-items-center tw-rounded-lg tw-px-5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all tw-duration-200 focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-blue-100"
                        style="background-color: #174D9D;"
                        onmouseover="this.style.backgroundColor='#123d7e'"
                        onmouseout="this.style.backgroundColor='#174D9D'">
                    <svg class="tw-mr-2 tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $isEdit ? __('Update Project') : __('Create Project') }}
                </button>
            </div>
        </form>
    </div>
</div>
