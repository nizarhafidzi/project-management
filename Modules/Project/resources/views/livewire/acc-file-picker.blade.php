<div class="tw-font-sans" wire:init="loadData">

    {{-- Modal Backdrop --}}
    <div class="tw-fixed tw-inset-0 tw-z-40 tw-bg-black/40 tw-backdrop-blur-sm"></div>

    {{-- Modal Panel --}}
    <div class="tw-fixed tw-inset-0 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4">
        <div class="tw-bg-white tw-rounded-xl tw-shadow-2xl tw-w-full tw-max-w-2xl tw-max-h-[80vh] tw-flex tw-flex-col tw-overflow-hidden tw-border tw-border-gray-200">

            {{-- Header --}}
            <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h3 class="tw-text-base tw-font-bold tw-text-gray-900">Select File from ACC</h3>
                    <p class="tw-text-xs tw-text-gray-400 tw-mt-0.5">Browse Autodesk Construction Cloud files</p>
                </div>
                <button wire:click="$dispatch('close-acc-picker')" class="tw-p-1.5 tw-rounded-lg tw-text-gray-400 hover:tw-text-gray-600 hover:tw-bg-gray-100 tw-transition-colors">
                    <svg class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="tw-px-6 tw-py-3 tw-border-b tw-border-gray-100 tw-bg-gray-50/50">
                <div class="tw-relative">
                    <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
                        <svg class="tw-w-4 tw-h-4 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search files and folders..." class="tw-w-full tw-pl-10 tw-pr-4 tw-py-2.5 tw-text-sm tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500/20 focus:tw-border-blue-400 tw-placeholder-gray-400 tw-transition-all">
                </div>
            </div>

            {{-- Breadcrumbs --}}
            <div class="tw-px-6 tw-py-2.5 tw-border-b tw-border-gray-100 tw-bg-white">
                <nav class="tw-flex tw-items-center tw-text-xs tw-text-gray-500 tw-overflow-x-auto tw-whitespace-nowrap" aria-label="Breadcrumb">
                    <ol class="tw-inline-flex tw-items-center tw-space-x-1">
                        @foreach($breadcrumbs as $index => $crumb)
                            <li class="tw-inline-flex tw-items-center">
                                @if(!$loop->first)
                                    <svg class="tw-w-3 tw-h-3 tw-mx-1 tw-text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                @endif
                                <button wire:click="navigateBreadcrumb({{ $index }})" class="tw-inline-flex tw-items-center tw-text-xs tw-font-medium tw-transition-colors {{ $loop->last ? 'tw-text-gray-900 tw-font-semibold' : 'tw-text-gray-500 hover:tw-text-blue-600' }}">
                                    @if($loop->first)
                                        <svg class="tw-w-3.5 tw-h-3.5 tw-mr-1 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    @endif
                                    {{ $crumb['name'] }}
                                </button>
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>

            {{-- File/Folder List --}}
            <div class="tw-flex-1 tw-overflow-y-auto tw-relative">

                {{-- Loading Overlay --}}
                <div wire:loading.flex class="tw-absolute tw-inset-0 tw-z-10 tw-items-center tw-justify-center tw-bg-white/80 tw-backdrop-blur-[2px]">
                    <div class="tw-flex tw-flex-col tw-items-center">
                        <div class="tw-w-8 tw-h-8 tw-rounded-full tw-border-3 tw-border-gray-200 tw-animate-spin tw-mb-2" style="border-top-color: #174D9D;"></div>
                        <span class="tw-text-xs tw-text-gray-500">Loading...</span>
                    </div>
                </div>

                @if(!$readyToLoad)
                    {{-- Skeleton Loader --}}
                    <div class="tw-p-6">
                        <div class="tw-animate-pulse tw-space-y-3">
                            @for($i = 0; $i < 5; $i++)
                                <div class="tw-flex tw-items-center tw-gap-3 tw-py-2">
                                    <div class="tw-w-6 tw-h-6 tw-bg-gray-200 tw-rounded"></div>
                                    <div class="tw-h-4 tw-bg-gray-200 tw-rounded tw-w-{{ ['3/4', '1/2', '2/3', '1/3', '3/5'][$i] }}"></div>
                                </div>
                            @endfor
                        </div>
                        <p class="tw-text-center tw-text-xs tw-text-gray-400 tw-mt-4">Fetching folders from ACC...</p>
                    </div>
                @else
                    @forelse($items as $item)
                        <div class="tw-flex tw-items-center tw-justify-between hover:tw-bg-blue-50 tw-cursor-pointer tw-border-b tw-border-gray-100 tw-py-3 tw-px-4 tw-transition-colors tw-group">
                            <div class="tw-flex tw-items-center tw-gap-3 tw-min-w-0 tw-flex-1">
                                @if(str_contains($item['type'], 'Folder'))
                                    {{-- Folder Icon (Yellow) --}}
                                    <div class="tw-flex-shrink-0">
                                        <svg class="tw-w-6 tw-h-6 tw-text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                        </svg>
                                    </div>
                                    <button wire:click="openFolder('{{ $item['id'] }}', '{{ $item['name'] }}')" class="tw-text-sm tw-font-medium tw-text-gray-700 group-hover:tw-text-blue-700 tw-text-left tw-truncate tw-transition-colors">
                                        {{ $item['name'] }}
                                    </button>
                                    {{-- Folder Chevron --}}
                                    <svg class="tw-w-4 tw-h-4 tw-text-gray-300 group-hover:tw-text-blue-400 tw-ml-auto tw-flex-shrink-0 tw-transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                @else
                                    {{-- File Icon (Blue) --}}
                                    <div class="tw-flex-shrink-0">
                                        <svg class="tw-w-6 tw-h-6" fill="none" stroke="#174D9D" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="tw-text-sm tw-text-gray-700 tw-truncate">{{ $item['name'] }}</span>
                                @endif
                            </div>

                            @if(!str_contains($item['type'], 'Folder'))
                                <button wire:click="selectFile('{{ $item['urn'] }}', '{{ $item['name'] }}')"
                                    class="tw-flex-shrink-0 tw-ml-3 tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-white tw-rounded-lg tw-transition-all tw-shadow-sm hover:tw-shadow-md focus:tw-ring-2 focus:tw-ring-offset-1"
                                    style="background-color: #174D9D;"
                                    onmouseover="this.style.backgroundColor='#123d7e'" onmouseout="this.style.backgroundColor='#174D9D'">
                                    Select
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="tw-py-12 tw-text-center">
                            <svg class="tw-mx-auto tw-h-10 tw-w-10 tw-text-gray-200 tw-mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h5l2 2h5a2 2 0 012 2v8a2 2 0 01-2 2H5z" /></svg>
                            <p class="tw-text-sm tw-text-gray-400">This folder is empty.</p>
                        </div>
                    @endforelse
                @endif
            </div>

        </div>
    </div>
</div>
