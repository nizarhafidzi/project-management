<div>
    <x-slot name="header">
        <div class="tw-flex tw-justify-between tw-items-center">
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('project.create') }}" wire:navigate
               class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-indigo-600 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-indigo-700 focus:tw-bg-indigo-700 active:tw-bg-indigo-900 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2 tw-transition tw-ease-in-out tw-duration-150">
                <svg class="tw-w-4 tw-h-4 tw-mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('New Project') }}
            </a>
        </div>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8">

            {{-- Flash Message --}}
            @if (session()->has('message'))
                <div class="tw-mb-4 tw-rounded-md tw-bg-green-50 tw-p-4">
                    <div class="tw-flex">
                        <svg class="tw-h-5 tw-w-5 tw-text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-green-800">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            {{-- Filters --}}
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg tw-mb-6">
                <div class="tw-p-6">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Search</label>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                   placeholder="Search by name, code, or contract..."
                                   class="tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Status</label>
                            <select wire:model.live="filterStatus"
                                    class="tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Completed">Completed</option>
                                <option value="On-Hold">On-Hold</option>
                            </select>
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Sector</label>
                            <select wire:model.live="filterSector"
                                    class="tw-w-full tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-border-indigo-500 focus:tw-ring-indigo-500 tw-text-sm">
                                <option value="">All Sectors</option>
                                <option value="Building">Building</option>
                                <option value="Water Resources">Water Resources</option>
                                <option value="Infrastructure">Infrastructure</option>
                                <option value="Energy">Energy</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Projects Table --}}
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-overflow-x-auto">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                        <thead class="tw-bg-gray-50">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Project Code</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Name</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Type</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Sector</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Service</th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Status</th>
                                <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                            @forelse ($projects as $project)
                                <tr class="hover:tw-bg-gray-50 tw-transition-colors tw-duration-150">
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                        <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium tw-bg-indigo-100 tw-text-indigo-800">
                                            {{ $project->project_code }}
                                        </span>
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-font-medium tw-text-gray-900">
                                        {{ $project->name }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        {{ $project->project_type->value }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        {{ $project->sector->value }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-500">
                                        {{ $project->technical_service->value }}
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                        @php
                                            $statusColor = match($project->status->value) {
                                                'Active' => 'tw-bg-green-100 tw-text-green-800',
                                                'Completed' => 'tw-bg-blue-100 tw-text-blue-800',
                                                'On-Hold' => 'tw-bg-yellow-100 tw-text-yellow-800',
                                                default => 'tw-bg-gray-100 tw-text-gray-800',
                                            };
                                        @endphp
                                        <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium {{ $statusColor }}">
                                            {{ $project->status->value }}
                                        </span>
                                    </td>
                                    <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-right tw-text-sm tw-font-medium">
                                        <div class="tw-flex tw-justify-end tw-space-x-2">
                                            <a href="{{ route('project.show', $project) }}" wire:navigate
                                               class="tw-text-indigo-600 hover:tw-text-indigo-900 tw-transition-colors" title="View">
                                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('project.edit', $project) }}" wire:navigate
                                               class="tw-text-yellow-600 hover:tw-text-yellow-900 tw-transition-colors" title="Edit">
                                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <button wire:click="confirmDelete({{ $project->id }})"
                                                    class="tw-text-red-600 hover:tw-text-red-900 tw-transition-colors" title="Delete">
                                                <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="tw-px-6 tw-py-12 tw-text-center tw-text-gray-500">
                                        <svg class="tw-mx-auto tw-h-12 tw-w-12 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <h3 class="tw-mt-2 tw-text-sm tw-font-medium tw-text-gray-900">No projects found</h3>
                                        <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Get started by creating a new project.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($projects->hasPages())
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-200">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto">
            <div class="tw-flex tw-items-end tw-justify-center tw-min-h-screen tw-pt-4 tw-px-4 tw-pb-20 tw-text-center sm:tw-block sm:tw-p-0">
                <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity" wire:click="cancelDelete"></div>
                <div class="tw-inline-block tw-align-bottom tw-bg-white tw-rounded-lg tw-text-left tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-my-8 sm:tw-align-middle sm:tw-max-w-lg sm:tw-w-full">
                    <div class="tw-bg-white tw-px-4 tw-pt-5 tw-pb-4 sm:tw-p-6 sm:tw-pb-4">
                        <div class="sm:tw-flex sm:tw-items-start">
                            <div class="tw-mx-auto tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-h-12 tw-w-12 tw-rounded-full tw-bg-red-100 sm:tw-mx-0 sm:tw-h-10 sm:tw-w-10">
                                <svg class="tw-h-6 tw-w-6 tw-text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="tw-mt-3 tw-text-center sm:tw-mt-0 sm:tw-ml-4 sm:tw-text-left">
                                <h3 class="tw-text-lg tw-leading-6 tw-font-medium tw-text-gray-900">Delete Project</h3>
                                <div class="tw-mt-2">
                                    <p class="tw-text-sm tw-text-gray-500">Are you sure you want to delete this project? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tw-bg-gray-50 tw-px-4 tw-py-3 sm:tw-px-6 sm:tw-flex sm:tw-flex-row-reverse">
                        <button wire:click="deleteProject" type="button"
                                class="tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-transparent tw-shadow-sm tw-px-4 tw-py-2 tw-bg-red-600 tw-text-base tw-font-medium tw-text-white hover:tw-bg-red-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-red-500 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            Delete
                        </button>
                        <button wire:click="cancelDelete" type="button"
                                class="tw-mt-3 tw-w-full tw-inline-flex tw-justify-center tw-rounded-md tw-border tw-border-gray-300 tw-shadow-sm tw-px-4 tw-py-2 tw-bg-white tw-text-base tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 sm:tw-mt-0 sm:tw-ml-3 sm:tw-w-auto sm:tw-text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
