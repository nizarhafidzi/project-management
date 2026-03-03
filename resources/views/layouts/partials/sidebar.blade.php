{{-- Sidebar: Desktop --}}
<aside class="tw-bg-white tw-border-r tw-border-gray-200 tw-h-screen tw-flex tw-flex-col tw-transition-all tw-duration-300 tw-ease-in-out tw-shadow-sm tw-flex-shrink-0 tw-hidden lg:tw-flex"
       :class="isSidebarOpen ? 'tw-w-64' : 'tw-w-20'">

    <!-- Logo Area -->
    <div class="tw-h-16 tw-flex tw-items-center tw-border-b tw-border-gray-200 tw-px-4"
         :class="isSidebarOpen ? 'tw-justify-start' : 'tw-justify-center'">
        <a href="{{ route('dashboard') }}" wire:navigate class="tw-flex tw-items-center tw-gap-3 tw-w-full">
            <img src="https://ptbek.co.id/wp-content/uploads/2024/08/Logo-BEK-Header.png" alt="Logo BEK" class="tw-h-8 tw-w-auto tw-object-contain" x-show="isSidebarOpen" x-transition.opacity.duration.200ms />
            <div class="tw-h-9 tw-w-9 tw-rounded-lg tw-bg-[#174D9D] tw-flex tw-items-center tw-justify-center tw-flex-shrink-0" x-show="!isSidebarOpen">
                <span class="tw-text-white tw-font-bold tw-text-xs">BEK</span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="tw-flex-1 tw-overflow-y-auto tw-py-4 tw-px-3 tw-space-y-1">

        {{-- Dashboard (Active Example) --}}
        <a href="{{ route('dashboard') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('dashboard') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'Dashboard' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>Dashboard</span>
        </a>

        {{-- Projects --}}
        @hasanyrole('Superadmin|Manager|Team Leader')
        <a href="{{ route('project.index') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('project.*') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'Projects' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>Projects</span>
        </a>
        @endhasanyrole

        {{-- Workforce Analytics --}}
        @hasanyrole('Superadmin|Manager')
        <a href="{{ route('reporting.workforce-analytics') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('reporting.workforce-analytics') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'Workforce Analytics' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>Workforce Analytics</span>
        </a>
        @endhasanyrole

        {{-- Daily Log --}}
        <a href="{{ route('operations.daily-log') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('operations.daily-log') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'Daily Log' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>Daily Log</span>
        </a>

        {{-- Approvals --}}
        <a href="{{ route('operations.approvals') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('operations.approvals') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'Approvals' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>Approvals</span>
        </a>

        {{-- System Section --}}
        @role('Superadmin')
        <div class="tw-mt-8 tw-mb-2 tw-px-3 tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>
            System
        </div>
        <a href="{{ route('system.auto-checkout') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('system.auto-checkout') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'System Settings' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>System Settings</span>
        </a>
        <a href="{{ route('system.users') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-group
           {{ request()->routeIs('system.users') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}"
           :title="!isSidebarOpen ? 'User Management' : ''">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="tw-ml-3 tw-whitespace-nowrap" x-show="isSidebarOpen" x-transition.opacity.duration.200ms>User Management</span>
        </a>
        @endrole

    </nav>
</aside>

{{-- Sidebar: Mobile Overlay --}}
<div x-show="isMobileOpen" x-transition:enter="tw-transition-opacity tw-ease-linear tw-duration-300" x-transition:enter-start="tw-opacity-0" x-transition:enter-end="tw-opacity-100" x-transition:leave="tw-transition-opacity tw-ease-linear tw-duration-300" x-transition:leave-start="tw-opacity-100" x-transition:leave-end="tw-opacity-0" class="tw-fixed tw-inset-0 tw-z-40 tw-bg-black/50 lg:tw-hidden" @click="isMobileOpen = false" style="display:none;"></div>

<aside x-show="isMobileOpen" x-transition:enter="tw-transition tw-ease-in-out tw-duration-300 tw-transform" x-transition:enter-start="-tw-translate-x-full" x-transition:enter-end="tw-translate-x-0" x-transition:leave="tw-transition tw-ease-in-out tw-duration-300 tw-transform" x-transition:leave-start="tw-translate-x-0" x-transition:leave-end="-tw-translate-x-full" class="tw-fixed tw-inset-y-0 tw-left-0 tw-z-50 tw-w-64 tw-bg-white tw-border-r tw-border-gray-200 tw-shadow-xl tw-flex tw-flex-col lg:tw-hidden" style="display:none;" @click.away="isMobileOpen = false">

    <!-- Logo Area -->
    <div class="tw-h-16 tw-flex tw-items-center tw-justify-between tw-border-b tw-border-gray-200 tw-px-4">
        <a href="{{ route('dashboard') }}" wire:navigate class="tw-flex tw-items-center tw-gap-3">
            <img src="https://ptbek.co.id/wp-content/uploads/2024/08/Logo-BEK-Header.png" alt="Logo BEK" class="tw-h-8 tw-w-auto tw-object-contain" />
        </a>
        <button @click="isMobileOpen = false" class="tw-p-1 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-600 hover:tw-bg-gray-100">
            <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation Menu (Mobile) -->
    <nav class="tw-flex-1 tw-overflow-y-auto tw-py-4 tw-px-3 tw-space-y-1">

        <a href="{{ route('dashboard') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('dashboard') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="tw-ml-3">Dashboard</span>
        </a>

        @hasanyrole('Superadmin|Manager|Team Leader')
        <a href="{{ route('project.index') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('project.*') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <span class="tw-ml-3">Projects</span>
        </a>
        @endhasanyrole

        @hasanyrole('Superadmin|Manager')
        <a href="{{ route('reporting.workforce-analytics') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('reporting.workforce-analytics') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="tw-ml-3">Workforce Analytics</span>
        </a>
        @endhasanyrole

        <a href="{{ route('operations.daily-log') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('operations.daily-log') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="tw-ml-3">Daily Log</span>
        </a>

        <a href="{{ route('operations.approvals') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('operations.approvals') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="tw-ml-3">Approvals</span>
        </a>

        @role('Superadmin')
        <div class="tw-mt-8 tw-mb-2 tw-px-3 tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider">
            System
        </div>
        <a href="{{ route('system.auto-checkout') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('system.auto-checkout') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="tw-ml-3">System Settings</span>
        </a>
        <a href="{{ route('system.users') }}" wire:navigate
           class="tw-flex tw-items-center tw-py-2.5 tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200
           {{ request()->routeIs('system.users') ? 'tw-bg-blue-50 tw-border-l-4 tw-border-[#174D9D] tw-text-[#174D9D] tw-pl-3' : 'tw-text-gray-600 hover:tw-bg-gray-100 hover:tw-text-gray-900 tw-px-3' }}">
            <svg class="tw-w-5 tw-h-5 tw-flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="tw-ml-3">User Management</span>
        </a>
        @endrole

    </nav>
</aside>
