<div class="tw-font-sans">
    {{-- ── Header ── --}}
    <div class="tw-mb-6">
        <nav class="tw-mb-2 tw-text-sm tw-font-medium tw-text-gray-500">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:tw-text-[#174D9D] tw-transition-colors">Home</a>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-700">System Settings</span>
            <span class="tw-mx-2">/</span>
            <span class="tw-text-gray-900">Automation</span>
        </nav>
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">System Settings / Automation</h1>
        <p class="tw-mt-1 tw-text-sm tw-text-gray-500">Configure automatic checkout rules and default working hours.</p>
    </div>

    {{-- ── Settings Card ── --}}
    <div class="tw-max-w-3xl">
        <div class="tw-bg-white tw-p-6 tw-rounded-lg tw-shadow-sm tw-border tw-border-gray-200">
            {{-- Card Header --}}
            <div class="tw-flex tw-items-center tw-gap-3 tw-mb-6 tw-pb-5 tw-border-b tw-border-gray-100">
                <div class="tw-w-10 tw-h-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center" style="background-color: rgba(23, 77, 157, 0.1);">
                    <svg class="tw-w-5 tw-h-5" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="tw-text-lg tw-font-bold tw-text-gray-900">Auto-Checkout Configuration</h2>
                    <p class="tw-text-sm tw-text-gray-500">Automatically check out employees who forgot to clock out.</p>
                </div>
            </div>

            <form wire:submit.prevent="save" class="tw-space-y-6">
                {{-- Toggle Switch ── Enable Auto-Checkout --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-border tw-border-gray-100">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center"
                             style="background-color: rgba(23, 77, 157, 0.08);">
                            <svg class="tw-w-4 tw-h-4" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="tw-text-sm tw-font-semibold tw-text-gray-900">Enable Auto-Checkout</span>
                            <p class="tw-text-xs tw-text-gray-400 tw-mt-0.5">Activate automatic clock-out for all employees.</p>
                        </div>
                    </div>
                    {{-- iOS/Modern Toggle Switch --}}
                    <label class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer tw-flex-shrink-0"
                           x-data="{ enabled: @entangle('auto_checkout_enabled') }">
                        <input type="checkbox" wire:model="auto_checkout_enabled" class="tw-sr-only" x-model="enabled">
                        <div class="tw-w-12 tw-h-7 tw-rounded-full tw-transition-colors tw-duration-200 tw-ease-in-out tw-relative tw-cursor-pointer"
                             :class="enabled ? '' : 'tw-bg-gray-300'"
                             :style="enabled ? 'background-color: #174D9D' : ''"
                             @click="enabled = !enabled; $wire.set('auto_checkout_enabled', enabled)">
                            <span class="tw-absolute tw-top-0.5 tw-left-0.5 tw-w-6 tw-h-6 tw-bg-white tw-rounded-full tw-shadow-md tw-transition-transform tw-duration-200 tw-ease-in-out tw-flex tw-items-center tw-justify-center"
                                  :class="enabled ? 'tw-translate-x-5' : 'tw-translate-x-0'">
                                <svg x-show="enabled" class="tw-w-3 tw-h-3" style="color: #174D9D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg x-show="!enabled" class="tw-w-3 tw-h-3 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </span>
                        </div>
                    </label>
                </div>

                {{-- Time Inputs Grid --}}
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5">
                    {{-- Execution Time --}}
                    <div>
                        <label for="auto_checkout_run_time" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">
                            Job Execution Time
                        </label>
                        <p class="tw-text-xs tw-text-gray-400 tw-mb-2">When the auto-checkout job runs daily.</p>
                        <div class="tw-relative">
                            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
                                <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input type="time" wire:model="auto_checkout_run_time" id="auto_checkout_run_time"
                                   class="tw-block tw-w-full tw-pl-10 tw-pr-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-transition-colors">
                        </div>
                        @error('auto_checkout_run_time')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500 tw-flex tw-items-center tw-gap-1">
                                <svg class="tw-w-3.5 tw-h-3.5 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Default Clock-Out Time --}}
                    <div>
                        <label for="default_clock_out_time" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-1.5">
                            Default Clock-Out Time
                        </label>
                        <p class="tw-text-xs tw-text-gray-400 tw-mb-2">Time applied when employees are auto-checked out.</p>
                        <div class="tw-relative">
                            <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
                                <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input type="time" wire:model="default_clock_out_time" id="default_clock_out_time"
                                   class="tw-block tw-w-full tw-pl-10 tw-pr-3 tw-py-2.5 tw-rounded-lg tw-border tw-border-gray-300 tw-text-sm tw-shadow-sm focus:tw-border-[#174D9D] focus:tw-ring-1 focus:tw-ring-[#174D9D] tw-transition-colors">
                        </div>
                        @error('default_clock_out_time')
                            <p class="tw-mt-1.5 tw-text-xs tw-text-red-500 tw-flex tw-items-center tw-gap-1">
                                <svg class="tw-w-3.5 tw-h-3.5 tw-flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="tw-flex tw-justify-end tw-pt-4 tw-border-t tw-border-gray-100">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-5 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-semibold tw-text-white tw-shadow-sm tw-transition-all hover:tw-shadow-md disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
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
                        <span wire:loading.remove wire:target="save">Save Settings</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
