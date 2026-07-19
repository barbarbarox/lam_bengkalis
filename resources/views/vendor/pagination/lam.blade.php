@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: rgba(0,0,0,0.05); color: var(--lam-text-l); border-radius: var(--radius); font-size: 0.9rem; cursor: not-allowed; opacity: 0.7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--lam-green); color: white; border-radius: var(--radius); font-size: 0.9rem; text-decoration: none; transition: background 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Sebelumnya
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; color: var(--lam-text-l); font-size: 0.9rem;">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: var(--lam-gold); color: white; border-radius: var(--radius-sm); font-weight: bold; font-size: 0.9rem;" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: var(--lam-bg-alt); color: var(--lam-text); border-radius: var(--radius-sm); font-size: 0.9rem; text-decoration: none; border: 1px solid var(--lam-border); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--lam-green)'; this.style.color='var(--lam-green)';" onmouseout="this.style.borderColor='var(--lam-border)'; this.style.color='var(--lam-text)';">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--lam-green); color: white; border-radius: var(--radius); font-size: 0.9rem; text-decoration: none; transition: background 0.2s;">
                Selanjutnya
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 0.5rem;"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        @else
            <span style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: rgba(0,0,0,0.05); color: var(--lam-text-l); border-radius: var(--radius); font-size: 0.9rem; cursor: not-allowed; opacity: 0.7;">
                Selanjutnya
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 0.5rem;"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </span>
        @endif

    </nav>
@endif
