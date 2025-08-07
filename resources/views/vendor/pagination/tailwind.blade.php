@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center space-x-1 content-start">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-base text-gray-400 bg-white border border-gray-200 rounded cursor-not-allowed">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-base text-gray-800 bg-white border border-gray-200 rounded hover:bg-gray-100">Prev</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-base base-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 text-base font-semibold text-black bg-gray-100 border border-gray-300 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-base text-gray-800 bg-white border border-gray-200 rounded hover:bg-gray-100">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-base text-gray-800 bg-white border border-gray-200 rounded hover:bg-gray-100">Next</a>
        @else
            <span class="px-3 py-1 text-base text-gray-400 bg-white border border-gray-200 rounded cursor-not-allowed">Next</span>
        @endif
    </nav>
@endif
