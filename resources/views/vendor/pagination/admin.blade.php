@if ($paginator->hasPages())
<nav class="pagination-wrapper">
    <span class="pagination-info">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }}
        of {{ $paginator->total() }} results
    </span>

    <ul class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>‹</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}">‹</a></li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}">›</a></li>
        @else
            <li class="disabled"><span>›</span></li>
        @endif
    </ul>
</nav>
@endif
