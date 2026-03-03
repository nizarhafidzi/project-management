<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="tw-flex tw-items-center tw-justify-between">
            <div class="tw-flex tw-justify-between tw-flex-1 sm:tw-hidden">
                @if ($paginator->onFirstPage())
                    <span class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-cursor-default tw-leading-5 tw-rounded-md">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <button type="button" wire:click="previousPage" class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 tw-leading-5 tw-rounded-md hover:tw-text-gray-500 focus:tw-outline-none focus:tw-ring focus:tw-ring-blue-300 active:tw-bg-gray-100 tw-transition tw-ease-in-out tw-duration-150">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage" class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-ml-3 tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 tw-leading-5 tw-rounded-md hover:tw-text-gray-500 focus:tw-outline-none focus:tw-ring focus:tw-ring-blue-300 active:tw-bg-gray-100 tw-transition tw-ease-in-out tw-duration-150">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-ml-3 tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-cursor-default tw-leading-5 tw-rounded-md">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>

            <div class="tw-hidden sm:tw-flex-1 sm:tw-flex sm:tw-items-center sm:tw-justify-between">
                <div>
                    <p class="tw-text-sm tw-text-gray-700 tw-leading-5">
                        {!! __('Showing') !!}
                        <span class="tw-font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="tw-font-medium">{{ $paginator->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="tw-font-medium">{{ $paginator->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

                <div>
                    <span class="tw-relative tw-z-0 tw-inline-flex tw-shadow-sm tw-rounded-md">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="tw-relative tw-inline-flex tw-items-center tw-px-2 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-cursor-default tw-rounded-l-md tw-leading-5" aria-hidden="true">
                                    <svg class="tw-w-5 tw-h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage" rel="prev" class="tw-relative tw-inline-flex tw-items-center tw-px-2 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-rounded-l-md tw-leading-5 hover:tw-text-gray-400 focus:tw-z-10 focus:tw-outline-none focus:tw-ring focus:tw-ring-blue-300 active:tw-bg-gray-100 tw-transition tw-ease-in-out tw-duration-150" aria-label="{{ __('pagination.previous') }}">
                                <svg class="tw-w-5 tw-h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 -tw-ml-px tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 tw-cursor-default tw-leading-5">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 -tw-ml-px tw-text-sm tw-font-medium tw-text-white tw-border tw-border-gray-300 tw-cursor-default tw-leading-5" style="background-color: #174D9D; border-color: #174D9D;">{{ $page }}</span>
                                        </span>
                                    @else
                                        <button type="button" wire:click="gotoPage({{ $page }})" class="tw-relative tw-inline-flex tw-items-center tw-px-4 tw-py-2 -tw-ml-px tw-text-sm tw-font-medium tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 tw-leading-5 hover:tw-text-gray-500 focus:tw-z-10 focus:tw-outline-none focus:tw-ring focus:tw-ring-blue-300 active:tw-bg-gray-100 tw-transition tw-ease-in-out tw-duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage" rel="next" class="tw-relative tw-inline-flex tw-items-center tw-px-2 tw-py-2 -tw-ml-px tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-rounded-r-md tw-leading-5 hover:tw-text-gray-400 focus:tw-z-10 focus:tw-outline-none focus:tw-ring focus:tw-ring-blue-300 active:tw-bg-gray-100 tw-transition tw-ease-in-out tw-duration-150" aria-label="{{ __('pagination.next') }}">
                                <svg class="tw-w-5 tw-h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="tw-relative tw-inline-flex tw-items-center tw-px-2 tw-py-2 -tw-ml-px tw-text-sm tw-font-medium tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-cursor-default tw-rounded-r-md tw-leading-5" aria-hidden="true">
                                    <svg class="tw-w-5 tw-h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
