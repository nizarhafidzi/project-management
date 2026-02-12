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

<div>
    <!-- Session Status -->
    <x-auth-session-status class="tw-mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="tw-block tw-mt-1 tw-w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="tw-mt-2" />
        </div>

        <!-- Password -->
        <div class="tw-mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="tw-block tw-mt-1 tw-w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="tw-mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="tw-block tw-mt-4">
            <label for="remember" class="tw-inline-flex tw-items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="tw-rounded tw-border-gray-300 tw-text-indigo-600 tw-shadow-sm focus:tw-ring-indigo-500" name="remember">
                <span class="tw-ms-2 tw-text-sm tw-text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
            @if (Route::has('password.request'))
                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="tw-ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
