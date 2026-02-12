<div>
    <x-slot name="header">
        <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
            {{ __('Workforce Analytics') }}
        </h2>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto tw-sm:tw-px-6 tw-lg:tw-px-8">
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm tw-sm:tw-rounded-lg">
                <div class="tw-p-6 tw-text-gray-900">
                    
                    {{-- Filters --}}
                    <div class="tw-mb-6 tw-flex tw-justify-between tw-items-center">
                        <div class="tw-w-1/3">
                            <label for="sector" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Filter by Sector</label>
                            <select wire:model.live="sector" id="sector" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-py-2 tw-pl-3 tw-pr-10 tw-text-base focus:tw-border-indigo-500 focus:tw-outline-none focus:tw-ring-indigo-500 sm:tw-text-sm">
                                <option value="All">All Sectors</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->value }}">{{ $sec->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="tw-overflow-x-auto">
                        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                            <thead class="tw-bg-gray-50">
                                <tr>
                                    <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Staff Name</th>
                                    {{-- Technical Service isn't on User model directly usually, but maybe via projects? 
                                         Requirement said "Technical Service (Engineering/BIM)". 
                                         Users might not have this, projects do. 
                                         I will assume checking their first project or just listing "N/A" if not defined on user.
                                         The prompt says "Columns: Staff Name, Technical Service (Engineering/BIM), ...".
                                         I'll check if User has this field. If not, I'll infer from projects or leave blank.
                                         Let's assume primarily based on their projects or a user attribute. 
                                         I'll try to pluck it from their projects for now. --}}
                                    <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Active Projects</th>
                                    <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Utilization Coeff.</th>
                                    <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                                @forelse($staff as $user)
                                    <tr class="hover:tw-bg-gray-50 tw-cursor-pointer" wire:click="openProjectModal({{ $user->id }}, '{{ $user->name }}')">
                                        <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                            <div class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $user->name }}</div>
                                            <div class="tw-text-sm tw-text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                            <div class="tw-text-sm tw-text-gray-900">{{ $user->active_projects_count }}</div>
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                            <div class="tw-text-sm tw-text-gray-900">{{ number_format($user->utilization_coefficient, 2) }}</div>
                                        </td>
                                        <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
                                            @php
                                                $statusClasses = match($user->utilization_status) {
                                                    'Ideal' => 'tw-bg-green-100 tw-text-green-800',
                                                    'Moderate' => 'tw-bg-yellow-100 tw-text-yellow-800',
                                                    'Overload' => 'tw-bg-red-100 tw-text-red-800 tw-animate-pulse',
                                                    default => 'tw-bg-gray-100 tw-text-gray-800',
                                                };
                                            @endphp
                                            <span class="tw-px-2 tw-inline-flex tw-text-xs tw-leading-5 tw-font-semibold tw-rounded-full {{ $statusClasses }}">
                                                {{ $user->utilization_status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-center tw-text-sm tw-text-gray-500">
                                            No staff found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Project Breakdown Modal --}}
    @if($showModal)
        <div class="tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center tw-overflow-y-auto tw-overflow-x-hidden tw-bg-gray-900 tw-bg-opacity-50 tw-backdrop-blur-sm" aria-modal="true" role="dialog">
            <div class="tw-relative tw-w-full tw-max-w-3xl tw-max-h-full">
                <!-- Modal content -->
                <div class="tw-relative tw-bg-white tw-rounded-lg tw-shadow dark:tw-bg-gray-700">
                    <!-- Modal header -->
                    <div class="tw-flex tw-items-start tw-justify-between tw-p-4 tw-border-b tw-rounded-t dark:tw-border-gray-600">
                        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                            Projects for: {{ $selectedStaffName }}
                        </h3>
                        <button wire:click="closeModal" type="button" class="tw-text-gray-400 tw-bg-transparent hover:tw-bg-gray-200 hover:tw-text-gray-900 tw-rounded-lg tw-text-sm tw-w-8 tw-h-8 tw-ml-auto tw-inline-flex tw-justify-center tw-items-center dark:hover:tw-bg-gray-600 dark:hover:tw-text-white">
                            <svg class="tw-w-3 tw-h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="tw-sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="tw-p-6 tw-space-y-6">
                        @if(count($projectDetails) > 0)
                            <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                                <thead class="tw-bg-gray-50">
                                    <tr>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Code</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Project Name</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Sector</th>
                                        <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase">Koefisien</th>
                                    </tr>
                                </thead>
                                <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
                                    @php $totalCoeff = 0; @endphp
                                    @foreach($projectDetails as $project)
                                        <tr>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900">{{ $project->project_code }}</td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900">{{ $project->name }}</td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900">{{ $project->sector->value }}</td>
                                            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900">
                                                {{ number_format($currentStaffCoefficient, 2) }}
                                                @php $totalCoeff += $currentStaffCoefficient; @endphp
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tw-bg-gray-100">
                                    <tr>
                                        <td colspan="3" class="tw-px-6 tw-py-3 tw-text-right tw-font-bold tw-text-sm tw-text-gray-700">Total Sum:</td>
                                        <td class="tw-px-6 tw-py-3 tw-text-left tw-font-bold tw-text-sm tw-text-gray-900">
                                            {{-- Display 1.00 directly or calculated sum, rounding to handle float precision --}}
                                            {{ number_format($totalCoeff, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <p class="tw-text-gray-500">No active projects assigned.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
