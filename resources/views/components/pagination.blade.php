@props(['paginator', 'label' => 'data'])

@php
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
    $window      = 2;
@endphp

<div class="pagination-wrapper">
    <div class="pagination-info">
        Menampilkan
        <b>{{ $paginator->firstItem() }}</b>-<b>{{ $paginator->lastItem() }}</b>
        dari <b>{{ $paginator->total() }}</b> {{ $label }}
    </div>

    <nav aria-label="Page navigation">
        <ul class="perm-pagination">
            {{-- Previous --}}
            <li>
                @if($paginator->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif
            </li>

            @if($currentPage > $window + 1)
                <li class="page-number">
                    <a href="{{ $paginator->url(1) }}" class="page-btn">1</a>
                </li>
                @if($currentPage > $window + 2)
                    <li><span class="page-dots">...</span></li>
                @endif
            @endif

            @for($page = max(1, $currentPage - $window); $page <= min($lastPage, $currentPage + $window); $page++)
                <li class="page-number {{ $page == $currentPage ? 'active' : '' }}">
                    @if($page == $currentPage)
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="page-btn">{{ $page }}</a>
                    @endif
                </li>
            @endfor

            @if($currentPage < $lastPage - $window)
                @if($currentPage < $lastPage - $window - 1)
                    <li><span class="page-dots">...</span></li>
                @endif
                <li class="page-number">
                    <a href="{{ $paginator->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next --}}
            <li>
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </li>
        </ul>
    </nav>
</div>