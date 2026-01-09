{{-- 
    Pagination Component - Styled pagination
    
    Usage:
    <x-pagination :paginator="$users" />
    <x-pagination :paginator="$items" simple />
--}}

@props([
    'paginator' => null,
    'simple' => false,
])

@if($paginator && $paginator->hasPages())
    <nav {{ $attributes }}>
        <ul class="pagination mb-0" style="gap: 0.25rem;">
            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" style="border-radius: var(--radius); border: 1px solid var(--bs-gray-200); background: var(--bs-gray-100);">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" style="border-radius: var(--radius); border: 1px solid var(--bs-gray-200);">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            @unless($simple)
                {{-- Page Numbers --}}
                @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link" style="border-radius: var(--radius); background: var(--bs-primary); border-color: var(--bs-primary);">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}" style="border-radius: var(--radius); border: 1px solid var(--bs-gray-200);">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @else
                <li class="page-item disabled">
                    <span class="page-link border-0 bg-transparent">
                        Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                    </span>
                </li>
            @endunless

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" style="border-radius: var(--radius); border: 1px solid var(--bs-gray-200);">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" style="border-radius: var(--radius); border: 1px solid var(--bs-gray-200); background: var(--bs-gray-100);">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
