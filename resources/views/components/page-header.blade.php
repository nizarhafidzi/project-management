@props(['title', 'description' => null])

<div class="tw-bg-white/80 tw-backdrop-blur-sm tw-border-b tw-border-gray-200 tw-relative tw-overflow-hidden">
    {{-- Subtle gradient accent at bottom --}}
    <div class="tw-absolute tw-bottom-0 tw-left-0 tw-right-0 tw-h-[2px] tw-bg-gradient-to-r tw-from-blue-500 tw-via-indigo-500 tw-to-purple-500 tw-opacity-60"></div>
    <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
        <div class="tw-flex tw-flex-wrap tw-justify-between tw-items-center tw-gap-4">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-tracking-tight tw-text-gray-900">
                    {{ $title }}
                </h1>
                @if($description)
                    <p class="tw-mt-1 tw-text-sm tw-text-gray-500">
                        {{ $description }}
                    </p>
                @endif
            </div>
            @if(isset($actions))
                <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>
