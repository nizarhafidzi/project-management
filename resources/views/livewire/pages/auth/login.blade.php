<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="tw-max-w-md tw-w-full tw-bg-white tw-rounded-xl tw-shadow-lg tw-border tw-border-gray-100 tw-p-8">

    {{-- Corporate Header --}}
    <div class="tw-mb-8 tw-flex tw-flex-col tw-items-center">
        <img src="https://ptbek.co.id/wp-content/uploads/2024/08/Logo-BEK-Header.png" alt="Logo BEK" class="tw-h-16 tw-w-auto tw-mb-4" />
        <p class="tw-text-center tw-text-sm tw-text-gray-500">
            Enterprise Project Management System
        </p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="tw-mb-4 tw-text-sm tw-font-medium tw-text-green-600 tw-text-center">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">

        {{-- Email Address --}}
        <div class="tw-mb-4">
            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">
                {{ __('Email Address') }}
            </label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="you@company.com"
                class="tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] sm:tw-text-sm"
            />
            @error('form.email')
                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="tw-mb-4">
            <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">
                {{ __('Password') }}
            </label>
            <input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] sm:tw-text-sm"
            />
            @error('form.password')
                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="tw-flex tw-items-center tw-justify-between tw-mt-4 tw-mb-6">
            <label for="remember" class="tw-inline-flex tw-items-center tw-cursor-pointer">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    name="remember"
                    class="tw-rounded tw-border-gray-300 tw-shadow-sm tw-text-[#174D9D] focus:tw-ring-[#174D9D]"
                    style="accent-color: #174D9D;"
                />
                <span class="tw-ms-2 tw-text-sm tw-text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    wire:navigate
                    class="tw-text-sm tw-text-[#174D9D] tw-font-medium hover:tw-underline focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] tw-rounded-md"
                >
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            class="tw-w-full tw-flex tw-justify-center tw-py-2.5 tw-px-4 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-[#174D9D] hover:tw-bg-blue-800 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] tw-transition-colors"
        >
            {{ __('Sign in to your account') }}
        </button>

    </form>

    {{-- Footer --}}
    <p class="tw-mt-6 tw-text-center tw-text-xs tw-text-gray-400">
        &copy; {{ date('Y') }} PT. Buana Enjiniring Konsultan. All rights reserved.
    </p>
</div>
