<header class="tw-sticky tw-top-0 tw-z-30 tw-bg-white tw-border-b tw-border-gray-200">
    <div class="tw-px-4 sm:tw-px-6">
        <div class="tw-flex tw-items-center tw-justify-between tw-h-16">

            <!-- Left Side: Hamburger -->
            <div class="tw-flex tw-items-center tw-gap-2">
                {{-- Desktop Toggle --}}
                <button @click="isSidebarOpen = !isSidebarOpen"
                        class="tw-hidden lg:tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-lg tw-text-gray-500 hover:tw-text-gray-700 hover:tw-bg-gray-100 focus:tw-outline-none tw-transition-colors tw-duration-200">
                    <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Mobile Toggle --}}
                <button @click="isMobileOpen = !isMobileOpen"
                        class="lg:tw-hidden tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-lg tw-text-gray-500 hover:tw-text-gray-700 hover:tw-bg-gray-100 focus:tw-outline-none tw-transition-colors tw-duration-200">
                    <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Right Side: Profile Area -->
            <div class="tw-flex tw-items-center tw-space-x-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="tw-flex tw-items-center tw-space-x-3 tw-rounded-lg tw-px-2 tw-py-1.5 hover:tw-bg-gray-50 focus:tw-outline-none tw-transition-colors tw-duration-200">
                            <div class="tw-text-right tw-hidden sm:tw-block">
                                <div class="tw-text-sm tw-font-semibold tw-text-gray-800"
                                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                     x-text="name"
                                     x-on:profile-updated.window="name = $event.detail.name"></div>
                                <div class="tw-text-xs tw-text-gray-500">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</div>
                            </div>
                            <div class="tw-h-9 tw-w-9 tw-rounded-full tw-bg-[#174D9D] tw-text-white tw-flex tw-items-center tw-justify-center tw-font-semibold tw-text-sm tw-flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="tw-w-full">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</header>
