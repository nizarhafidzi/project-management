<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
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
        {{ __('Reset Password') }}
    </h2>

    <p class="tw-text-sm tw-text-gray-500 tw-text-center tw-mb-6 tw-leading-relaxed">
        {{ __('Enter your new password below to regain access to your account.') }}
    </p>

    <form wire:submit="resetPassword">

        {{-- Email Address --}}
        <div class="tw-mb-4">
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

        {{-- Password --}}
        <div class="tw-mb-4">
            <label for="password" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">
                {{ __('New Password') }}
            </label>
            <input
                wire:model="password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] sm:tw-text-sm"
            />
            @error('password')
                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="tw-mb-6">
            <label for="password_confirmation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">
                {{ __('Confirm Password') }}
            </label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="tw-appearance-none tw-block tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-[#174D9D] focus:tw-border-[#174D9D] sm:tw-text-sm"
            />
            @error('password_confirmation')
                <p class="tw-mt-1 tw-text-sm tw-text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            class="tw-w-full tw-flex tw-justify-center tw-py-2.5 tw-px-4 tw-border tw-border-transparent tw-rounded-md tw-shadow-sm tw-text-sm tw-font-medium tw-text-white tw-bg-[#174D9D] hover:tw-bg-blue-800 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#174D9D] tw-transition-colors"
        >
            {{ __('Reset Password') }}
        </button>

    </form>

    {{-- Footer --}}
    <p class="tw-mt-6 tw-text-center tw-text-xs tw-text-gray-400">
        &copy; {{ date('Y') }} PT. Buana Enjiniring Konsultan. All rights reserved.
    </p>
</div>
