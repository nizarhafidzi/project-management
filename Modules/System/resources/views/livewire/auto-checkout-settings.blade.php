<div>
    <x-slot name="header">
        <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-p-6 tw-bg-white tw-border-b tw-border-gray-200">
                    <h2 class="tw-text-xl tw-font-bold tw-mb-4 tw-text-gray-800">Auto-Checkout Configuration</h2>

                    <form wire:submit.prevent="save" class="tw-space-y-4">
                        <!-- Toggle Switch -->
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <span class="tw-text-gray-700 tw-font-medium">Enable Auto-Checkout</span>
                            <label class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer">
                                <input type="checkbox" wire:model="auto_checkout_enabled" class="tw-sr-only peer">
                                <div class="tw-w-11 tw-h-6 tw-bg-gray-200 peer-focus:tw-outline-none peer-focus:tw-ring-4 peer-focus:tw-ring-blue-300 tw-rounded-full peer peer-checked:after:tw-translate-x-full peer-checked:after:tw-border-white after:tw-content-[''] after:tw-absolute after:tw-top-[2px] after:tw-left-[2px] after:tw-bg-white after:tw-border-gray-300 after:tw-border after:tw-rounded-full after:tw-h-5 after:tw-w-5 after:tw-transition-all peer-checked:tw-bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Execution Time -->
                        <div>
                            <label class="tw-block tw-font-medium tw-text-gray-700">Execution Time (Run Time)</label>
                            <input type="time" wire:model="auto_checkout_run_time" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring focus:tw-ring-blue-200">
                            @error('auto_checkout_run_time') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Default Clock-Out Time -->
                        <div>
                            <label class="tw-block tw-font-medium tw-text-gray-700">Default Clock-Out Time</label>
                            <input type="time" wire:model="default_clock_out_time" class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring focus:tw-ring-blue-200">
                            @error('default_clock_out_time') <span class="tw-text-red-500 tw-text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Save Button -->
                        <div class="tw-flex tw-justify-end">
                            <button type="submit" class="tw-px-4 tw-py-2 tw-bg-blue-600 tw-text-white tw-rounded-md hover:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-blue-500 disabled:tw-opacity-50" wire:loading.attr="disabled">
                                <span wire:loading.remove>Save Changes</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
