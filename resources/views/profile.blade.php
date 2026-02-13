<x-app-layout>
    <x-slot name="header">
        <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-space-y-6">
            <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-max-w-xl">
                    <section>
                        <header>
                            <h2 class="tw-text-lg tw-font-medium tw-text-gray-900">
                                {{ __('Autodesk Integration') }}
                            </h2>

                            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                                {{ __("Connect your personal Autodesk account to access your projects and files.") }}
                            </p>
                        </header>

                        <div class="tw-mt-6">
                            @if(auth()->user()->aps_access_token)
                                <div class="tw-flex tw-items-center tw-gap-4">
                                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-green-100 tw-text-green-800">
                                        ● Connected
                                    </span>
                                    <a href="{{ route('autodesk.disconnect') }}" class="tw-text-sm tw-text-red-600 hover:tw-text-red-900">
                                        Disconnect
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('autodesk.connect.user') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-gray-800 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-gray-700 focus:tw-bg-gray-700 active:tw-bg-gray-900 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2 tw-transition tw-ease-in-out tw-duration-150">
                                    {{ __('Connect Autodesk Account') }}
                                </a>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="tw-p-4 sm:tw-p-8 tw-bg-white tw-shadow sm:tw-rounded-lg">
                <div class="tw-max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
