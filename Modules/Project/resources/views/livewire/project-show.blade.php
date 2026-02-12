<div>
    <x-slot name="header">
        <div class="tw-flex tw-justify-between tw-items-center">
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                {{ __('Project Details') }}
            </h2>
            <div class="tw-flex tw-space-x-3">
                <a href="{{ route('reporting.dashboard', $project) }}" wire:navigate
                   class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-text-sm tw-font-medium tw-text-white tw-bg-emerald-600 hover:tw-bg-emerald-700 tw-transition-colors">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('📊 Analytics') }}
                </a>
                <a href="{{ route('project.wbs', $project) }}" wire:navigate
                   class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-text-sm tw-font-medium tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-transition-colors">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    {{ __('WBS Planning') }}
                </a>
                <a href="{{ route('project.edit', $project) }}" wire:navigate
                   class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-yellow-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-yellow-700 tw-bg-yellow-50 hover:tw-bg-yellow-100 tw-transition-colors">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('project.index') }}" wire:navigate
                   class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white hover:tw-bg-gray-50 tw-transition-colors">
                    <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

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
            @if (session()->has('error'))
                <div class="tw-rounded-md tw-bg-red-50 tw-p-4">
                    <div class="tw-flex">
                        <svg class="tw-h-5 tw-w-5 tw-text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                        </svg>
                        <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Project Info Card --}}
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-px-6 tw-py-5 tw-border-b tw-border-gray-200">
                    <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900">Project Information</h3>
                </div>
                <div class="tw-p-6">
                    <dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-x-6 tw-gap-y-4">
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Project Code</dt>
                            <dd class="tw-mt-1">
                                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-indigo-100 tw-text-indigo-800">
                                    {{ $project->project_code }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Status</dt>
                            <dd class="tw-mt-1">
                                @php
                                    $statusColor = match($project->status->value) {
                                        'Active' => 'tw-bg-green-100 tw-text-green-800',
                                        'Completed' => 'tw-bg-blue-100 tw-text-blue-800',
                                        'On-Hold' => 'tw-bg-yellow-100 tw-text-yellow-800',
                                        default => 'tw-bg-gray-100 tw-text-gray-800',
                                    };
                                @endphp
                                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium {{ $statusColor }}">
                                    {{ $project->status->value }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Project Name</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900 tw-font-medium">{{ $project->name }}</dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Contract Number</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ $project->contract_number }}</dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Project Type</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ $project->project_type->value }}</dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Technical Service</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ $project->technical_service->value }}</dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Sector</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ $project->sector->value }}</dd>
                        </div>
                        <div>
                            <dt class="tw-text-sm tw-font-medium tw-text-gray-500">Created</dt>
                            <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ $project->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Team Members Card --}}
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-px-6 tw-py-5 tw-border-b tw-border-gray-200 tw-flex tw-justify-between tw-items-center">
                    <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900">
                        Team Members
                        <span class="tw-ml-2 tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                            {{ $project->users->count() }}
                        </span>
                    </h3>
                    <button wire:click="openAssignModal"
                            class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-text-sm tw-font-medium tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 tw-transition-colors">
                        <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Assign Member
                    </button>
                </div>
                <div class="tw-p-6">
                    @if ($project->users->count() > 0)
                        <div class="tw-overflow-x-auto">
                            <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                                <thead class="tw-bg-gray-50">
                                    <tr>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Name</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Email</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Role in Project</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                                    @foreach ($project->users as $user)
                                        <tr class="hover:tw-bg-gray-50">
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                                <div class="tw-flex tw-items-center">
                                                    <div class="tw-flex-shrink-0 tw-h-8 tw-w-8 tw-rounded-full tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center">
                                                        <span class="tw-text-xs tw-font-medium tw-text-indigo-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div class="tw-ml-3">
                                                        <p class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $user->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                                {{ $user->email }}
                                            </td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                                @php
                                                    $roleColor = match($user->pivot->role_in_project) {
                                                        'Manager' => 'tw-bg-purple-100 tw-text-purple-800',
                                                        'Team Leader' => 'tw-bg-blue-100 tw-text-blue-800',
                                                        default => 'tw-bg-gray-100 tw-text-gray-800',
                                                    };
                                                @endphp
                                                <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium {{ $roleColor }}">
                                                    {{ $user->pivot->role_in_project }}
                                                </span>
                                            </td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-right">
                                                <button wire:click="removeMember({{ $user->id }})"
                                                        wire:confirm="Are you sure you want to remove this member?"
                                                        class="tw-text-red-600 hover:tw-text-red-900 tw-text-sm tw-font-medium tw-transition-colors">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="tw-text-center tw-py-8">
                            <svg class="tw-mx-auto tw-h-12 tw-w-12 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="tw-mt-2 tw-text-sm tw-font-medium tw-text-gray-900">No members assigned</h3>
                            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Assign team members to this project.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Member Modal --}}
    @if ($showAssignModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-end tw-justify-center tw-min-h-screen tw-pt-4 tw-px-4 tw-pb-20 tw-text-center sm:tw-block sm:tw-p-0">
                <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity" wire:click="closeAssignModal"></div>
                <div class="tw-inline-block tw-align-bottom tw-bg-white tw-rounded-lg tw-text-left tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-my-8 sm:tw-align-middle sm:tw-max-w-lg sm:tw-w-full">
                    <div class="tw-bg-white tw-px-4 tw-pt-5 tw-pb-4 sm:tw-p-6">
                        <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900 tw-mb-4">Assign Member</h3>

                        <div class="tw-space-y-4">
                            {{-- Search User --}}
                            <div>
                                <x-input-label for="searchUser" :value="__('Search User')" />
                                <x-text-input wire:model.live.debounce.300ms="searchUser" id="searchUser" type="text"
                                              class="tw-mt-1 tw-block tw-w-full" placeholder="Type to search users..." />
                            </div>

                            {{-- User Selection --}}
                            <div>
                                <x-input-label for="selectedUserId" :value="__('Select User')" />
                                <select wire:model="selectedUserId" id="selectedUserId"
                                        class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                    <option value="">-- Select User --</option>
                                    @foreach ($this->availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('selectedUserId')" class="tw-mt-2" />
                            </div>

                            {{-- Role Selection --}}
                            <div>
                                <x-input-label for="selectedRole" :value="__('Role in Project')" />
                                <select wire:model="selectedRole" id="selectedRole"
                                        class="tw-mt-1 tw-block tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                    <option value="Manager">Manager</option>
                                    <option value="Team Leader">Team Leader</option>
                                    <option value="Member">Member</option>
                                </select>
                                <x-input-error :messages="$errors->get('selectedRole')" class="tw-mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="tw-bg-gray-50 tw-px-4 tw-py-3 sm:tw-px-6 sm:tw-flex sm:tw-flex-row-reverse">
                        <button wire:click="assignMember" type="button"
                                class="tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-transparent tw-shadow-sm tw-px-4 tw-py-2 tw-bg-indigo-600 tw-text-base tw-font-medium tw-text-white hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            Assign
                        </button>
                        <button wire:click="closeAssignModal" type="button"
                                class="tw-mt-3 tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-gray-300 tw-shadow-sm tw-px-4 tw-py-2 tw-bg-white tw-text-base tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 sm:tw-mt-0 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
