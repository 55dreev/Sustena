@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                @include('vendor.pagination._chevron-left')
            </span>
        @else
            <a class="page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                @include('vendor.pagination._chevron-left')
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                @include('vendor.pagination._chevron-right')
            </a>
        @else
            <span class="page-btn disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                @include('vendor.pagination._chevron-right')
            </span>
        @endif
    </nav>
@endif
