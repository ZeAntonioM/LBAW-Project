<section class="pagination">
    <a href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-chevron-left"></i> Previous</a>

    <section class="pages">
        @php
            $start = max(1, $paginator->currentPage() - 3);
            $end = min($paginator->lastPage(), $paginator->currentPage() + 3);
        @endphp

        @if($paginator->currentPage() > 4)
            <a class="page last" href="{{ $paginator->url(1) }}">1</a>
        @elseif($paginator->currentPage() !== 1)
            <a class="page first" href="{{ $paginator->url(1) }}">1</a>
        @endif

        @if($paginator->currentPage() > 5)
            <span class="last">...</span>
        @elseif($paginator->currentPage() !== 1)
            <span class="first">...</span>
        @endif

        @for($page = $start; $page <= $end; $page++)
            @if($page === $paginator->currentPage())
                <a class="page current" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @else
                <a class="page priority{{ abs($paginator->currentPage() - $page) }}"
                   href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endfor

        @if($paginator->currentPage() + 4 < $paginator->lastPage())
            <span class="last">...</span>
        @elseif($paginator->currentPage() !== $paginator->lastPage())
            <span class="first">...</span>
        @endif

        @if($paginator->currentPage() + 3 < $paginator->lastPage())
            <a class="page last" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @elseif($paginator->currentPage() !== $paginator->lastPage())
            <a class="page first" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @endif
    </section>

    <a href="{{ $paginator->nextPageUrl() }}">Next <i class="fas fa-chevron-right"></i></a>
</section>
