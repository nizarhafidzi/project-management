<nav class="tw--mx-3 tw-flex tw-flex-1 tw-justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="tw-rounded-md tw-px-3 tw-py-2 tw-text-black tw-ring-1 tw-ring-transparent tw-transition hover:tw-text-black/70 focus:tw-outline-none focus-visible:tw-ring-[#FF2D20] dark:tw-text-white dark:tw-hover:text-white/80 dark:tw-focus-visible:ring-white"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="tw-rounded-md tw-px-3 tw-py-2 tw-text-black tw-ring-1 tw-ring-transparent tw-transition hover:tw-text-black/70 focus:tw-outline-none focus-visible:tw-ring-[#FF2D20] dark:tw-text-white dark:tw-hover:text-white/80 dark:tw-focus-visible:ring-white"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="tw-rounded-md tw-px-3 tw-py-2 tw-text-black tw-ring-1 tw-ring-transparent tw-transition hover:tw-text-black/70 focus:tw-outline-none focus-visible:tw-ring-[#FF2D20] dark:tw-text-white dark:tw-hover:text-white/80 dark:tw-focus-visible:ring-white"
            >
                Register
            </a>
        @endif
    @endauth
</nav>
