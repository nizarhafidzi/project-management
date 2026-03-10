<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
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

    {{-- Title --}}
    <h2 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-text-center tw-mb-2">
        {{ __('Forgot Password') }}
    </h2>

    {{-- Description --}}
    <p class="tw-text-sm tw-text-gray-500 tw-text-center tw-mb-6 tw-leading-relaxed">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="tw-mb-4 tw-text-sm tw-font-medium tw-text-green-600 tw-text-center tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-lg tw-px-4 tw-py-3">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink">

        {{-- Email Address --}}
        <div class="tw-mb-6">
            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">
                {{ __('Email Address') }}
            </label>
            <input
                wire:model="email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="you@company.com"
                class="tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] sm:tw-text-sm"
            />
            @error('email')
                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            class="tw-w-full tw-flex tw-justify-center tw-py-2.5 tw-px-4 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-[#174D9D] hover:tw-bg-blue-800 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] tw-transition-colors"
        >
            {{ __('Email Password Reset Link') }}
        </button>

    </form>

    {{-- Back to Login --}}
    <div class="tw-mt-6 tw-text-center">
        <a
            href="{{ route('login') }}"
            wire:navigate
            class="tw-text-sm tw-text-[#174D9D] tw-font-medium hover:tw-underline focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] tw-rounded-md"
        >
            &larr; {{ __('Back to Sign In') }}
        </a>
    </div>

    {{-- Footer --}}
    <p class="tw-mt-6 tw-text-center tw-text-xs tw-text-gray-400">
        &copy; {{ date('Y') }} PT. Buana Enjiniring Konsultan. All rights reserved.
    </p>
</div>
