<div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-py-8 tw-font-sans">
    {{-- Page Header --}}
    <div class="tw-mb-8 tw-flex tw-flex-col tw-gap-4 md:tw-flex-row md:tw-items-center md:tw-justify-between">
        <div>
            <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
                <span class="tw-mx-2">/</span>
                <a href="{{ route('project.index') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Projects</a>
                <span class="tw-mx-2">/</span>
                <span class="tw-text-gray-900">Details</span>
            </nav>
            <div class="tw-flex tw-items-center tw-gap-3">
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-tracking-tight">{{ $project->name }}</h1>
                @php
                    $headerStatusClasses = match($project->status->value) {
                        'Active' => 'tw-bg-emerald-50 tw-text-emerald-700 tw-ring-emerald-600/20',
                        'Completed' => 'tw-bg-blue-50 tw-text-blue-700 tw-ring-blue-600/20',
                        'On-Hold' => 'tw-bg-amber-50 tw-text-amber-700 tw-ring-amber-600/20',
                        default => 'tw-bg-gray-50 tw-text-gray-700 tw-ring-gray-600/20',
                    };
                @endphp
                <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-2.5 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold tw-ring-1 tw-ring-inset {{ $headerStatusClasses }}">
                    <span class="tw-text-[0.5rem]">●</span>
                    {{ $project->status->value }}
                </span>
            </div>
        </div>
        <div class="tw-flex tw-flex-wrap tw-gap-2">
            <a href="{{ route('reporting.dashboard', $project) }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-4 tw-py-2.5 tw-border tw-border-transparent tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-shadow-sm tw-transition-colors">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                {{ __('Analytics') }}
            </a>
            <a href="{{ route('project.wbs', $project) }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-4 tw-py-2.5 tw-border tw-border-transparent tw-rounded-lg tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-colors"
               style="background-color: #174D9D;"
               onmouseover="this.style.backgroundColor='#123d7e'"
               onmouseout="this.style.backgroundColor='#174D9D'">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                {{ __('WBS Planning') }}
            </a>
            <a href="{{ route('project.edit', $project) }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-4 tw-py-2.5 tw-border tw-border-gray-300 tw-rounded-lg tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-shadow-sm tw-transition-colors">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('Edit') }}
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="tw-mb-6 tw-rounded-lg tw-bg-green-50 tw-p-4 tw-border tw-border-green-200">
            <div class="tw-flex tw-items-center">
                <svg class="tw-h-5 tw-w-5 tw-text-green-500 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="tw-mb-6 tw-rounded-lg tw-bg-red-50 tw-p-4 tw-border tw-border-red-200">
            <div class="tw-flex tw-items-center">
                <svg class="tw-h-5 tw-w-5 tw-text-red-500 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                </svg>
                <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Main Content Grid: Info (left, larger) + Team (right) --}}
    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6">

        {{-- Left Column: Project Information Card --}}
        <div class="lg:tw-col-span-2">
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-bg-gray-50">
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <svg class="tw-h-5 tw-w-5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">Project Information</h3>
                    </div>
                </div>
                <div class="tw-p-6">
                    <dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-x-8 tw-gap-y-5">
                        {{-- Project Code --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Project Code</dt>
                            <dd class="tw-mt-1.5">
                                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-md tw-text-sm tw-font-semibold tw-ring-1 tw-ring-inset"
                                      style="background-color: rgba(23, 77, 157, 0.08); color: #174D9D; --tw-ring-color: rgba(23, 77, 157, 0.2);">
                                    {{ $project->project_code }}
                                </span>
                            </dd>
                        </div>

                        {{-- Status --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Status</dt>
                            <dd class="tw-mt-1.5">
                                @php
                                    $statusColor = match($project->status->value) {
                                        'Active' => 'tw-bg-emerald-50 tw-text-emerald-700 tw-ring-emerald-600/20',
                                        'Completed' => 'tw-bg-blue-50 tw-text-blue-700 tw-ring-blue-600/20',
                                        'On-Hold' => 'tw-bg-amber-50 tw-text-amber-700 tw-ring-amber-600/20',
                                        default => 'tw-bg-gray-50 tw-text-gray-700 tw-ring-gray-600/20',
                                    };
                                @endphp
                                <span class="tw-inline-flex tw-items-center tw-gap-1 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-semibold tw-ring-1 tw-ring-inset {{ $statusColor }}">
                                    <span class="tw-text-[0.5rem]">●</span>
                                    {{ $project->status->value }}
                                </span>
                            </dd>
                        </div>

                        {{-- Autodesk Project ID --}}
                        @if($project->acc_project_id)
                        <div class="md:tw-col-span-2">
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Autodesk Project ID</dt>
                            <dd class="tw-mt-1.5">
                                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-1 tw-rounded-md tw-text-xs tw-font-mono tw-bg-gray-100 tw-text-gray-600 tw-ring-1 tw-ring-gray-200">
                                    <svg class="tw-h-3.5 tw-w-3.5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    {{ $project->acc_project_id }}
                                </span>
                            </dd>
                        </div>
                        @endif

                        {{-- Project Name --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Project Name</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-font-medium tw-text-gray-900">{{ $project->name }}</dd>
                        </div>

                        {{-- Contract Number --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Contract Number</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-text-gray-700">{{ $project->contract_number }}</dd>
                        </div>

                        {{-- Project Type --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Project Type</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-text-gray-700">{{ $project->project_type->value }}</dd>
                        </div>

                        {{-- Technical Service --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Technical Service</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-text-gray-700">{{ $project->technical_service->value }}</dd>
                        </div>

                        {{-- Sector --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Sector</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-text-gray-700">{{ $project->sector->value }}</dd>
                        </div>

                        {{-- Created --}}
                        <div>
                            <dt class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">Created</dt>
                            <dd class="tw-mt-1.5 tw-text-sm tw-text-gray-700">{{ $project->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Right Column: Team Members Card --}}
        <div class="lg:tw-col-span-1">
            <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-bg-gray-50">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <svg class="tw-h-5 tw-w-5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">Team Members</h3>
                            <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-gray-200 tw-text-gray-700">
                                {{ $project->users->count() }}
                            </span>
                        </div>
                        <button wire:click="openAssignModal"
                                class="tw-inline-flex tw-items-center tw-rounded-lg tw-p-2 tw-text-white tw-shadow-sm tw-transition-all tw-duration-200 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-300"
                                style="background-color: #174D9D;"
                                onmouseover="this.style.backgroundColor='#123d7e'"
                                onmouseout="this.style.backgroundColor='#174D9D'"
                                title="Assign Member">
                            <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="tw-p-4">
                    @if ($project->users->count() > 0)
                        <div class="tw-space-y-3">
                            @foreach ($project->users as $user)
                                <div class="tw-flex tw-items-center tw-justify-between tw-p-3 tw-rounded-lg tw-border tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors tw-group">
                                    <div class="tw-flex tw-items-center tw-gap-3 tw-min-w-0">
                                        {{-- Avatar --}}
                                        <div class="tw-flex-shrink-0 tw-h-9 tw-w-9 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-white tw-shadow-sm"
                                             style="background-color: #174D9D;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="tw-min-w-0">
                                            <p class="tw-text-sm tw-font-semibold tw-text-gray-900 tw-truncate">{{ $user->name }}</p>
                                            <p class="tw-text-xs tw-text-gray-500 tw-truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-flex-shrink-0">
                                        @php
                                            $roleColor = match($user->pivot->role_in_project) {
                                                'Manager' => 'tw-bg-purple-50 tw-text-purple-700 tw-ring-purple-600/20',
                                                'Team Leader' => 'tw-bg-blue-50 tw-text-blue-700 tw-ring-blue-600/20',
                                                default => 'tw-bg-gray-50 tw-text-gray-600 tw-ring-gray-500/20',
                                            };
                                        @endphp
                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-[0.65rem] tw-leading-tight tw-font-semibold tw-ring-1 tw-ring-inset {{ $roleColor }}">
                                            {{ $user->pivot->role_in_project }}
                                        </span>
                                        <button wire:click="removeMember({{ $user->id }})"
                                                wire:confirm="Are you sure you want to remove this member?"
                                                class="tw-opacity-0 group-hover:tw-opacity-100 tw-p-1 tw-rounded tw-text-gray-400 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-all tw-duration-200"
                                                title="Remove member">
                                            <svg class="tw-h-3.5 tw-w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="tw-text-center tw-py-10">
                            <div class="tw-rounded-full tw-bg-gray-100 tw-p-4 tw-inline-flex tw-mb-3">
                                <svg class="tw-h-8 tw-w-8 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">No members assigned</h3>
                            <p class="tw-mt-1 tw-text-xs tw-text-gray-500">Assign team members to this project.</p>
                            <button wire:click="openAssignModal"
                                    class="tw-mt-3 tw-inline-flex tw-items-center tw-rounded-lg tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-text-white tw-shadow-sm tw-transition-all"
                                    style="background-color: #174D9D;">
                                <svg class="tw-mr-1 tw-h-3.5 tw-w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Member
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Member Modal --}}
    @if ($showAssignModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto" x-data x-transition>
            <div class="tw-flex tw-min-h-screen tw-items-center tw-justify-center tw-p-4">
                {{-- Backdrop with blur --}}
                <div class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" wire:click="closeAssignModal"></div>

                {{-- Modal Card --}}
                <div class="tw-relative tw-w-full tw-max-w-md tw-transform tw-overflow-hidden tw-rounded-lg tw-bg-white tw-shadow-2xl tw-transition-all">
                    {{-- Modal Header --}}
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-bg-gray-50">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <div class="tw-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 tw-rounded-lg" style="background-color: rgba(23, 77, 157, 0.1);">
                                    <svg class="tw-h-4 tw-w-4" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">Assign Member</h3>
                            </div>
                            <button wire:click="closeAssignModal" class="tw-rounded-lg tw-p-1 tw-text-gray-400 hover:tw-text-gray-600 hover:tw-bg-gray-100 tw-transition-colors">
                                <svg class="tw-h-5 tw-w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="tw-p-6 tw-space-y-5">
                        {{-- Search User --}}
                        <div>
                            <label for="searchUser" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Search User') }}</label>
                            <div class="tw-relative">
                                <div class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-3">
                                    <svg class="tw-h-4 tw-w-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="searchUser" id="searchUser" type="text"
                                       class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-pl-10 tw-pr-4 tw-py-2.5 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors"
                                       placeholder="Type to search users..." />
                            </div>
                        </div>

                        {{-- User Selection --}}
                        <div>
                            <label for="selectedUserId" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Select User') }}</label>
                            <select wire:model="selectedUserId" id="selectedUserId"
                                    class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                                <option value="">-- Select User --</option>
                                @foreach ($this->availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('selectedUserId')" class="tw-mt-1.5" />
                        </div>

                        {{-- Role Selection --}}
                        <div>
                            <label for="selectedRole" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">{{ __('Role in Project') }}</label>
                            <select wire:model="selectedRole" id="selectedRole"
                                    class="tw-block tw-w-full tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-text-sm tw-py-2.5 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                                <option value="Manager">Manager</option>
                                <option value="Team Leader">Team Leader</option>
                                <option value="Member">Member</option>
                            </select>
                            <x-input-error :messages="$errors->get('selectedRole')" class="tw-mt-1.5" />
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-border-t tw-border-gray-100 tw-bg-gray-50 tw-px-6 tw-py-4">
                        <button wire:click="closeAssignModal" type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-gray-300">
                            Cancel
                        </button>
                        <button wire:click="assignMember" type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all tw-duration-200 focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-blue-100"
                                style="background-color: #174D9D;"
                                onmouseover="this.style.backgroundColor='#123d7e'"
                                onmouseout="this.style.backgroundColor='#174D9D'">
                            <svg class="tw-mr-2 tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Assign Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
