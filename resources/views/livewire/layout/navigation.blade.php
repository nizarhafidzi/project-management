<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="tw-bg-white tw-border-b tw-border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
        <div class="tw-flex tw-justify-between tw-h-16">
            <div class="tw-flex">
                <!-- Logo -->
                <div class="tw-shrink-0 tw-flex tw-items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="tw-block tw-h-9 tw-w-auto tw-fill-current tw-text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="tw-hidden tw-space-x-8 sm:-tw-my-px sm:tw-ms-10 sm:tw-flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @hasanyrole('Superadmin|Manager|Team Leader')
                    <x-nav-link :href="route('project.index')" :active="request()->routeIs('project.*')" wire:navigate>
                        {{ __('Projects') }}
                    </x-nav-link>
                    @endhasanyrole

                    @hasanyrole('Superadmin|Manager')
                    <x-nav-link :href="route('reporting.workforce-analytics')" :active="request()->routeIs('reporting.workforce-analytics')" wire:navigate>
                        {{ __('Workforce Analytics') }}
                    </x-nav-link>
                    @endhasanyrole

                    @role('Superadmin')
                    <x-nav-link :href="route('system.auto-checkout')" :active="request()->routeIs('system.auto-checkout')" wire:navigate>
                        {{ __('System Settings') }}
                    </x-nav-link>
                    @endrole
                    <x-nav-link :href="route('operations.daily-log')" :active="request()->routeIs('operations.daily-log')" wire:navigate>
                        {{ __('Daily Log') }}
                    </x-nav-link>
                    <x-nav-link :href="route('operations.approvals')" :active="request()->routeIs('operations.approvals')" wire:navigate>
                        {{ __('Approvals') }}
                    </x-nav-link>
                    <x-nav-link :href="route('operations.bim-viewer')" :active="request()->routeIs('operations.bim-viewer')" wire:navigate>
                        {{ __('BIM Viewer') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="tw-hidden sm:tw-flex sm:tw-items-center sm:tw-ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-white hover:tw-text-gray-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="tw-ms-1">
                                <svg class="tw-fill-current tw-h-4 tw-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="tw-w-full tw-text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-tw-me-2 tw-flex tw-items-center sm:tw-hidden">
                <button @click="open = ! open" class="tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-500 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 focus:tw-text-gray-500 tw-transition tw-duration-150 tw-ease-in-out">
                    <svg class="tw-h-6 tw-w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="tw-inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="tw-hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="tw-hidden sm:tw-hidden">
        <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @hasanyrole('Superadmin|Manager|Team Leader')
            <x-responsive-nav-link :href="route('project.index')" :active="request()->routeIs('project.*')" wire:navigate>
                {{ __('Projects') }}
            </x-responsive-nav-link>
            @endhasanyrole
            <x-responsive-nav-link :href="route('operations.daily-log')" :active="request()->routeIs('operations.daily-log')" wire:navigate>
                {{ __('Daily Log') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('operations.approvals')" :active="request()->routeIs('operations.approvals')" wire:navigate>
                {{ __('Approvals') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('operations.bim-viewer')" :active="request()->routeIs('operations.bim-viewer')" wire:navigate>
                {{ __('BIM Viewer') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
            <div class="tw-px-4">
                <div class="tw-font-medium tw-text-base tw-text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="tw-font-medium tw-text-sm tw-text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="tw-mt-3 tw-space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="tw-w-full tw-text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
