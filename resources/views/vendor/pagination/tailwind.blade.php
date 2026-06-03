@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Mobile --}}
        <div class="flex gap-2 items-center justify-between sm:hidden">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-200">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-200">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="px-4 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between">

            {{-- Info --}}
            <div>
                <p class="text-sm text-gray-700">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- Pagination --}}
            <div>
                @php
                    $current = $paginator->currentPage();
                    $last = $paginator->lastPage();
                    $range = 1;

                    $start = max($current - $range, 1);
                    $end = min($current + $range, $last);
                @endphp

                <span class="inline-flex shadow-sm rounded-md">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="px-2 py-2 text-gray-400 bg-white border border-gray-300 rounded-l-md cursor-not-allowed">
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="px-2 py-2 bg-white border border-gray-300 rounded-l-md hover:bg-gray-200">
                            ‹
                        </a>
                    @endif

                    {{-- First page --}}
                    @if ($start > 1)
                        <a href="{{ $paginator->url(1) }}" class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-200">
                            1
                        </a>

                        @if ($start > 2)
                            <span class="px-3 py-2 border border-gray-300 bg-white">...</span>
                        @endif
                    @endif

                    {{-- Middle pages --}}
                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $current)
                            <span class="px-4 py-2 border border-gray-300 bg-gray-200 font-semibold">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $paginator->url($i) }}" class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-200">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    {{-- Last page --}}
                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="px-3 py-2 border border-gray-300 bg-white">...</span>
                        @endif

                        <a href="{{ $paginator->url($last) }}" class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-200">
                            {{ $last }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-2 py-2 bg-white border border-gray-300 rounded-r-md hover:bg-gray-200">
                            ›
                        </a>
                    @else
                        <span class="px-2 py-2 text-gray-400 bg-white border border-gray-300 rounded-r-md cursor-not-allowed">
                            ›
                        </span>
                    @endif

                </span>
            </div>
        </div>
    </nav>
@endif