{{-- Tampilan paginasi bawaan diganti versi CSS polos, tanpa Tailwind. --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Navigasi halaman">
        <span class="pagination__summary">
            Menampilkan {{ $paginator->firstItem() }} sampai {{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} laporan
        </span>

        <ul class="pagination__list">
            @if ($paginator->onFirstPage())
                <li>
                    <span class="pagination__link pagination__link--disabled">Sebelumnya</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination__link">Sebelumnya</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="pagination__link pagination__link--disabled">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="pagination__link pagination__link--current" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination__link">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination__link">Berikutnya</a>
                </li>
            @else
                <li>
                    <span class="pagination__link pagination__link--disabled">Berikutnya</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
