<div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-py-8 tw-font-sans">
    {{-- Page Header --}}
    <div class="tw-mb-8 tw-flex tw-flex-col tw-gap-4 md:tw-flex-row md:tw-items-center md:tw-justify-between">
        <div>
            <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
                <span class="tw-mx-2">/</span>
                <span class="tw-text-gray-900">Projects</span>
            </nav>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-tracking-tight">Projects</h1>
            <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Manage and monitor all your projects in one place.</p>
        </div>
        @hasanyrole('Superadmin|Manager|Team Leader')
        <a href="{{ route('project.create') }}" wire:navigate
           class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-lg tw-px-5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all tw-duration-200 focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-blue-100"
           style="background-color: #174D9D;"
           onmouseover="this.style.backgroundColor='#123d7e'"
           onmouseout="this.style.backgroundColor='#174D9D'">
            <svg class="tw-mr-2 tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Project
        </a>
        @endhasanyrole
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="tw-mb-6 tw-rounded-lg tw-bg-green-50 tw-p-4 tw-border tw-border-green-200">
            <div class="tw-flex tw-items-center">
                <svg class="tw-h-5 tw-w-5 tw-text-green-500 tw-flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <p class="tw-ml-3 tw-text-sm tw-font-medium tw-text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="tw-mb-6 tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-p-5">
        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4">
            <svg class="tw-h-5 tw-w-5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <h3 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wider">Filters</h3>
        </div>
        <div class="tw-grid tw-gap-4 md:tw-grid-cols-3">
            {{-- Search --}}
            <div class="tw-relative">
                <div class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-3">
                    <svg class="tw-h-4 tw-w-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                       class="tw-block tw-w-full tw-rounded-lg tw-border-gray-300 tw-pl-10 tw-pr-4 tw-py-2.5 tw-text-sm tw-placeholder-gray-400 focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors"
                       placeholder="Search by name, code, or contract...">
            </div>

            {{-- Status Filter --}}
            <div>
                <select wire:model.live="filterStatus"
                        class="tw-block tw-w-full tw-rounded-lg tw-border-gray-300 tw-py-2.5 tw-text-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed</option>
                    <option value="On-Hold">On-Hold</option>
                </select>
            </div>

            {{-- Sector Filter --}}
            <div>
                <select wire:model.live="filterSector"
                        class="tw-block tw-w-full tw-rounded-lg tw-border-gray-300 tw-py-2.5 tw-text-sm focus:tw-border-[#174D9D] focus:tw-ring-[#174D9D] tw-transition-colors">
                    <option value="">All Sectors</option>
                    <option value="Building">Building</option>
                    <option value="Water Resources">Water Resources</option>
                    <option value="Infrastructure">Infrastructure</option>
                    <option value="Energy">Energy</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Projects Table Card --}}
    <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-border tw-border-gray-200 tw-overflow-hidden">
        <div class="tw-overflow-x-auto">
            <table class="tw-w-full tw-text-left tw-text-sm">
                <thead>
                    <tr class="tw-bg-gray-50 tw-border-b tw-border-gray-200">
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Project Code</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Name</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Type</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Sector</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Service</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500">Status</th>
                        <th scope="col" class="tw-px-6 tw-py-3.5 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wider tw-text-gray-500 tw-text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="tw-divide-y tw-divide-gray-100">
                    @forelse ($projects as $project)
                        <tr class="hover:tw-bg-gray-50/70 tw-transition-colors tw-duration-150">
                            <td class="tw-px-6 tw-py-4">
                                <span class="tw-inline-flex tw-items-center tw-rounded-md tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-ring-1 tw-ring-inset"
                                      style="background-color: rgba(23, 77, 157, 0.08); color: #174D9D; --tw-ring-color: rgba(23, 77, 157, 0.2);">
                                    {{ $project->project_code }}
                                </span>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-font-medium tw-text-gray-900">
                                {{ $project->name }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-text-gray-600">
                                {{ $project->project_type->value }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-text-gray-600">
                                {{ $project->sector->value }}
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-text-gray-600">
                                {{ $project->technical_service->value }}
                            </td>
                            <td class="tw-px-6 tw-py-4">
                                @php
                                    $statusConfig = match($project->status->value) {
                                        'Active' => ['tw-bg-emerald-50 tw-text-emerald-700 tw-ring-emerald-600/20', '●'],
                                        'Completed' => ['tw-bg-blue-50 tw-text-blue-700 tw-ring-blue-600/20', '●'],
                                        'On-Hold' => ['tw-bg-amber-50 tw-text-amber-700 tw-ring-amber-600/20', '●'],
                                        default => ['tw-bg-gray-50 tw-text-gray-700 tw-ring-gray-600/20', '●'],
                                    };
                                @endphp
                                <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-ring-1 tw-ring-inset {{ $statusConfig[0] }}">
                                    <span class="tw-text-[0.5rem]">{{ $statusConfig[1] }}</span>
                                    {{ $project->status->value }}
                                </span>
                            </td>
                            <td class="tw-px-6 tw-py-4 tw-text-right">
                                <div class="tw-flex tw-justify-end tw-items-center tw-gap-1">
                                    <a href="{{ route('project.show', $project) }}" wire:navigate
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-lg tw-p-2 tw-text-gray-600 hover:tw-text-[#174D9D] hover:tw-bg-blue-50 tw-transition-all tw-duration-200" title="View">
                                        <svg class="tw-h-5 tw-w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    @hasanyrole('Superadmin|Manager|Team Leader') 
                                    <a href="{{ route('project.edit', $project) }}" wire:navigate
                                       class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-lg tw-p-2 tw-text-gray-600 hover:tw-text-[#174D9D] hover:tw-bg-blue-50 tw-transition-all tw-duration-200" title="Edit">
                                        <svg class="tw-h-5 tw-w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                        </svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $project->id }})"
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-lg tw-p-2 tw-text-gray-600 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-all tw-duration-200" title="Delete">
                                        <svg class="tw-h-5 tw-w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tw-px-6 tw-py-16 tw-text-center">
                                <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                    <div class="tw-rounded-full tw-bg-gray-100 tw-p-4 tw-mb-4">
                                        <svg class="tw-h-8 tw-w-8 tw-text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                                        </svg>
                                    </div>
                                    <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">No projects found</h3>
                                    <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Get started by creating a new project.</p>
                                    <a href="{{ route('project.create') }}" wire:navigate
                                       class="tw-mt-4 tw-inline-flex tw-items-center tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-white tw-shadow-sm tw-transition-all"
                                       style="background-color: #174D9D;">
                                        <svg class="tw-mr-1.5 tw-h-4 tw-w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        New Project
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($projects->hasPages())
            <div class="tw-border-t tw-border-gray-200 tw-px-6 tw-py-4 tw-bg-gray-50/50">
                {{ $projects->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-overflow-y-auto" x-data x-transition>
            <div class="tw-flex tw-min-h-screen tw-items-center tw-justify-center tw-p-4">
                {{-- Backdrop with blur --}}
                <div class="tw-fixed tw-inset-0 tw-bg-gray-900/60 tw-backdrop-blur-sm tw-transition-opacity" wire:click="cancelDelete"></div>

                {{-- Modal Card --}}
                <div class="tw-relative tw-w-full tw-max-w-md tw-transform tw-overflow-hidden tw-rounded-lg tw-bg-white tw-shadow-2xl tw-transition-all">
                    <div class="tw-p-6">
                        <div class="tw-flex tw-items-start tw-gap-4">
                            <div class="tw-flex tw-h-12 tw-w-12 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full tw-bg-red-100">
                                <svg class="tw-h-6 tw-w-6 tw-text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Delete Project</h3>
                                <p class="tw-mt-2 tw-text-sm tw-text-gray-500">Are you sure you want to delete this project? All related data will be permanently removed. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-border-t tw-border-gray-100 tw-bg-gray-50 tw-px-6 tw-py-4">
                        <button wire:click="cancelDelete" type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-gray-300">
                            Cancel
                        </button>
                        <button wire:click="deleteProject" type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-transparent tw-bg-red-600 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-white tw-shadow-sm hover:tw-bg-red-700 tw-transition-colors focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-500">
                            Delete Project
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
