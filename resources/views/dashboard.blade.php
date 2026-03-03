<x-app-layout>
    <x-page-header title="{{ __('Dashboard') }}" description="Welcome back, {{ auth()->user()->name }}." />

    <div class="tw-py-8">
        <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-space-y-6">

            {{-- Welcome Card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-p-6 tw-relative tw-overflow-hidden">
                <div class="tw-absolute tw-top-0 tw-right-0 tw-w-40 tw-h-40 tw-bg-gradient-to-bl tw-from-blue-50 tw-to-transparent tw-rounded-bl-full"></div>
                <div class="tw-relative tw-flex tw-items-center tw-gap-4">
                    <div class="tw-flex-shrink-0 tw-h-12 tw-w-12 tw-rounded-xl tw-bg-blue-100 tw-flex tw-items-center tw-justify-center">
                        <svg class="tw-w-6 tw-h-6 tw-text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">{{ __("You're logged in!") }}</h3>
                        <p class="tw-text-sm tw-text-gray-500">Use the sidebar to navigate to your projects, daily logs, and reports.</p>
                    </div>
                </div>
            </div>

            @role('superadmin')
                {{-- Autodesk Master Account Card --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 tw-bg-gray-50/30">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Autodesk Master Account</h3>
                        <p class="tw-mt-0.5 tw-text-sm tw-text-gray-500">
                            This account is used as a system-wide fallback for viewing 3D models.
                        </p>
                    </div>
                    <div class="tw-p-6">
                        @if(\App\Models\Setting::get('aps_master_access_token'))
                            <div class="tw-flex tw-items-center tw-gap-3">
                                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-bg-green-100 tw-text-green-800">
                                    ● Connected
                                </span>
                                <a href="{{ route('autodesk.connect.master') }}" class="tw-text-sm tw-font-medium tw-text-blue-600 hover:tw-text-blue-800 tw-transition-colors">
                                    Reconnect
                                </a>
                            </div>
                        @else
                            <a href="{{ route('autodesk.connect.master') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-border tw-border-transparent tw-rounded-lg tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-blue-500 active:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2 tw-transition tw-ease-in-out tw-duration-150">
                                Connect Master Account
                            </a>
                        @endif
                    </div>
                </div>
            @endrole

        </div>
    </div>
</x-app-layout>
