<x-app-layout>
    <x-slot name="header">
        <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="tw-py-12">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8">
            <div class="tw-bg-white tw-overflow-hidden tw-shadow-sm sm:tw-rounded-lg">
                <div class="tw-p-6 tw-text-gray-900">
                    {{ __("You're logged in!") }}

                    @role('superadmin')
                        <div class="tw-mt-4 tw-p-4 tw-bg-gray-100 tw-rounded-lg">
                            <h3 class="tw-text-lg tw-font-medium tw-text-gray-900">Autodesk Master Account</h3>
                            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                                This account is used as a system-wide fallback for viewing 3D models.
                            </p>
                            <div class="tw-mt-4">
                                @if(\App\Models\Setting::get('aps_master_access_token'))
                                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-green-100 tw-text-green-800">
                                        ● Connected
                                    </span>
                                    <a href="{{ route('autodesk.connect.master') }}" class="tw-ml-3 tw-text-sm tw-text-blue-600 hover:tw-text-blue-900">
                                        Reconnect
                                    </a>
                                @else
                                    <a href="{{ route('autodesk.connect.master') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-blue-500 active:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2 tw-transition tw-ease-in-out tw-duration-150">
                                        Connect Master Account
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
