@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    
    // Calculate sliding window range of exactly 5 pages (or less if total pages < 5)
    $start = max(1, $currentPage - 2);
    $end = min($lastPage, $currentPage + 2);
    
    if ($currentPage <= 3) {
        $start = 1;
        $end = min($lastPage, 5);
    } elseif ($currentPage > $lastPage - 3) {
        $start = max(1, $lastPage - 4);
        $end = $lastPage;
    }
@endphp

<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center items-center my-4">
    <div class="flex items-center gap-1.5 sm:gap-2">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-sm font-medium text-gray-300 bg-gray-50 dark:text-gray-600 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg cursor-not-allowed select-none shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-600 transition duration-150 shadow-sm" aria-label="{{ __('pagination.previous') }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Page Numbers (Always visible on mobile with compact w-8 h-8 size, desktop sm:w-10 sm:h-10) --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-xs sm:text-sm font-bold text-white bg-blue-800 dark:bg-blue-600 border border-blue-800 dark:border-blue-600 rounded-lg select-none shadow-sm">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-600 transition duration-150 shadow-sm">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-600 transition duration-150 shadow-sm" aria-label="{{ __('pagination.next') }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg cursor-not-allowed select-none shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
