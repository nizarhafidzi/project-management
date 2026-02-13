<div class="tw-p-4" wire:init="loadData">
    {{-- Breadcrumbs --}}
    <nav class="tw-flex tw-mb-4 tw-text-sm tw-text-gray-600 tw-overflow-x-auto tw-whitespace-nowrap" aria-label="Breadcrumb">
        <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-3">
            @foreach($breadcrumbs as $index => $crumb)
                <li class="tw-inline-flex tw-items-center">
                    @if(!$loop->first)
                        <svg class="tw-w-3 tw-h-3 tw-mx-1 tw-text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                    @endif
                    <button wire:click="navigateBreadcrumb({{ $index }})" class="tw-inline-flex tw-items-center tw-text-sm tw-font-medium hover:tw-text-blue-600 {{ $loop->last ? 'tw-text-gray-900 tw-font-bold' : 'tw-text-gray-500' }}">
                        {{ $crumb['name'] }}
                    </button>
                </li>
            @endforeach
        </ol>
    </nav>

    {{-- File List --}}
    <div class="tw-border tw-rounded-md tw-divide-y tw-max-h-96 tw-overflow-y-auto tw-relative">
        {{-- Loading Overlay --}}
        <div wire:loading.flex class="tw-absolute tw-inset-0 tw-z-10 tw-items-center tw-justify-center tw-bg-white tw-bg-opacity-75">
            <div class="tw-flex tw-flex-col tw-items-center">
                <div class="tw-animate-spin tw-rounded-full tw-h-8 tw-w-8 tw-border-b-2 tw-border-blue-600 tw-mb-2"></div>
                <span class="tw-text-sm tw-text-gray-500">Loading...</span>
            </div>
        </div>

        @if(!$readyToLoad)
             <div class="tw-p-8 tw-text-center tw-text-gray-500">
                <div class="tw-animate-pulse tw-flex tw-flex-col tw-items-center">
                    <div class="tw-h-4 tw-bg-gray-200 tw-rounded tw-w-3/4 tw-mb-4"></div>
                    <div class="tw-h-4 tw-bg-gray-200 tw-rounded tw-w-1/2"></div>
                </div>
                <p class="tw-mt-4">Fetching folders from ACC...</p>
            </div>
        @else
            @forelse($items as $item)
                <div class="tw-flex tw-items-center tw-justify-between tw-p-3 hover:tw-bg-gray-50 tw-transition">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        @if(str_contains($item['type'], 'Folder'))
                            {{-- Folder Icon --}}
                            <svg class="tw-w-6 tw-h-6 tw-text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <button wire:click="openFolder('{{ $item['id'] }}', '{{ $item['name'] }}')" class="tw-font-medium tw-text-gray-700 hover:tw-text-blue-600 tw-text-left">
                                {{ $item['name'] }}
                            </button>
                        @else
                            {{-- File Icon --}}
                            <svg class="tw-w-6 tw-h-6 tw-text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="tw-text-gray-700">{{ $item['name'] }}</span>
                        @endif
                    </div>

                    @if(!str_contains($item['type'], 'Folder'))
                        <button wire:click="selectFile('{{ $item['urn'] }}', '{{ $item['name'] }}')" 
                            class="tw-px-3 tw-py-1 tw-text-xs tw-font-medium tw-text-white tw-bg-blue-600 tw-rounded hover:tw-bg-blue-700 focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-1">
                            Select
                        </button>
                    @endif
                </div>
            @empty
                <div class="tw-p-4 tw-text-center tw-text-gray-500">
                    <p>This folder is empty.</p>
                </div>
            @endforelse
        @endif
    </div>
</div>
