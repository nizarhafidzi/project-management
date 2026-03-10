<div class="tw-font-sans">

    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-700">System Settings</span>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Holiday Manager</span>
        </nav>
        <div class="tw-flex tw-items-center tw-justify-between">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">System Settings / Holiday Manager</h1>
                <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Manage national holidays and red-letter dates for workforce utilization calculation.</p>
            </div>
            <button wire:click="openModal"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md"
                    style="background-color: #174D9D;"
                    onmouseover="this.style.backgroundColor='#123f82'"
                    onmouseout="this.style.backgroundColor='#174D9D'">
                <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Holiday
            </button>
        </div>
    </div>

    {{-- ── Flash Message ── --}}
    @if (session()->has('message'))
        <div class="tw-mb-4 tw-px-4 tw-py-2.5 tw-rounded-md tw-text-sm tw-font-medium tw-flex tw-items-center tw-gap-2 tw-bg-emerald-50 tw-text-emerald-700 tw-border tw-border-emerald-200">
            <svg class="tw-w-4 tw-h-4 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- ── Data Table Card ── --}}
    <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
        <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
            <thead class="tw-bg-gray-50">
                <tr>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Date</th>
                    <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Description</th>
                    <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="tw-bg-white tw-divide-y tw-divide-gray-100">
                @forelse ($holidays as $holiday)
                    <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                        {{-- Date --}}
                        <td class="tw-px-6 tw-py-4">
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-flex-shrink-0" style="background-color: rgba(23, 77, 157, 0.08);">
                                    <svg class="tw-w-4 tw-h-4" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="tw-text-sm tw-font-semibold tw-text-gray-900">{{ $holiday->date->format('d M Y') }}</span>
                            </div>
                        </td>

                        {{-- Description --}}
                        <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-600">{{ $holiday->description }}</td>

                        {{-- Actions --}}
                        <td class="tw-px-6 tw-py-4 tw-text-right">
                            <div class="tw-flex tw-items-center tw-justify-end tw-gap-2">
                                {{-- Edit --}}
                                <button wire:click="edit({{ $holiday->id }})"
                                        title="Edit Holiday"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-text-gray-500 hover:tw-text-[#174D9D] hover:tw-bg-blue-50 tw-transition-colors">
                                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <button wire:click="delete({{ $holiday->id }})"
                                        wire:confirm="Are you sure you want to delete this holiday?"
                                        title="Delete Holiday"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-text-gray-400 hover:tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors">
                                    <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="tw-px-6 tw-py-16 tw-text-center">
                            <div class="tw-flex tw-flex-col tw-items-center tw-gap-2">
                                <div class="tw-w-12 tw-h-12 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mb-1" style="background-color: rgba(23, 77, 157, 0.07);">
                                    <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="tw-text-sm tw-font-medium tw-text-gray-400">No holidays configured</p>
                                <p class="tw-text-xs tw-text-gray-400">Click "Add Holiday" to create a new holiday entry.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($holidays->hasPages())
            <div class="tw-px-6 tw-py-4 tw-border-t tw-border-gray-100">
                {{ $holidays->links() }}
            </div>
        @endif
    </div>

    {{-- ── Modal ── --}}
    @if ($isModalOpen)
        <div class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4">
            <div class="tw-bg-white tw-rounded-lg tw-shadow-xl tw-w-full tw-max-w-lg" wire:click.stop>

                {{-- Modal Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                            <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">
                            {{ $holidayId ? 'Edit Holiday' : 'Add New Holiday' }}
                        </h2>
                    </div>
                    <button wire:click="closeModal" class="tw-text-gray-400 hover:tw-text-gray-600 tw-transition-colors tw-p-1 tw-rounded-lg hover:tw-bg-gray-100">
                        <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="tw-px-6 tw-py-5 tw-space-y-5">

                    {{-- Date --}}
                    <div>
                        <label for="holiday_date" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Date</label>
                        <input type="date"
                               wire:model="date"
                               id="holiday_date"
                               class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors @error('date') tw-border-red-400 @enderror">
                        @error('date')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="holiday_description" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">Description</label>
                        <input type="text"
                               wire:model="description"
                               id="holiday_description"
                               placeholder="e.g. Independence Day"
                               class="tw-block tw-w-full tw-px-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-outline-none tw-transition-colors @error('description') tw-border-red-400 @enderror">
                        @error('description')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-px-6 tw-py-4 tw-border-t tw-border-gray-100 tw-bg-gray-50 tw-rounded-b-lg">
                    <button wire:click="closeModal"
                            class="tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 hover:tw-bg-gray-50 tw-transition-colors tw-shadow-sm">
                        Cancel
                    </button>
                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                            style="background-color: #174D9D;"
                            onmouseover="this.style.backgroundColor='#123f82'"
                            onmouseout="this.style.backgroundColor='#174D9D'">
                        <svg wire:loading.remove wire:target="save" class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg wire:loading wire:target="save" class="tw-animate-spin tw-w-4 tw-h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Save Holiday</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
