@if ($paginator->hasPages())
<nav aria-label="Navigasi halaman" style="display: flex; align-items: center;">
    <ul style="display: flex; flex-direction: row; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0;">

        {{-- Tombol Previous (‹) --}}
        @if ($paginator->onFirstPage())
            <li>
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; color: #cbd5e1; font-size: 0.85rem; cursor: not-allowed;" aria-disabled="true">&lsaquo;</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 0.85rem; text-decoration: none; transition: all 0.15s;"
                   onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';"
                   onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">&lsaquo;</a>
            </li>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li>
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; color: #94a3b8; font-size: 0.8rem;">{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; background: #321270; color: #ffffff; font-size: 0.8rem; font-weight: 700; border: 1px solid #321270;" aria-current="page">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                               style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 0.8rem; font-weight: 500; text-decoration: none; transition: all 0.15s;"
                               onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#c4b5fd'; this.style.color='#321270';"
                               onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0'; this.style.color='#475569';">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next (›) --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"
                   style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 0.85rem; text-decoration: none; transition: all 0.15s;"
                   onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';"
                   onmouseout="this.style.background='#fff'; this.style.borderColor='#e2e8f0';">&rsaquo;</a>
            </li>
        @else
            <li>
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc; color: #cbd5e1; font-size: 0.85rem; cursor: not-allowed;" aria-disabled="true">&rsaquo;</span>
            </li>
        @endif

    </ul>
</nav>
@endif
