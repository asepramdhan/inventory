<div>
  @if ($paginator->hasPages())
  <nav role="navigation" aria-label="Pagination Navigation"
    class="flex items-center justify-between px-6 py-4 bg-gray-50/50 dark:bg-zinc-900/50 border-t border-gray-200 dark:border-zinc-800">
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-zinc-400">
          Showing
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
          to
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
          of
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
          results
        </p>
      </div>

      <div class="flex gap-2">
        {{-- Previous Page Button --}}
        @if ($paginator->onFirstPage())
        <span
          class="px-4 py-2 text-sm font-medium text-zinc-500 bg-gray-100 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-800 rounded-lg cursor-not-allowed opacity-50">
          Previous
        </span>
        @else
        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-800 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all active:scale-95">
          Previous
        </button>
        @endif

        {{-- Next Page Button --}}
        @if ($paginator->onLastPage())
        <span
          class="px-4 py-2 text-sm font-medium text-zinc-500 bg-gray-100 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-800 rounded-lg cursor-not-allowed opacity-50">
          Next
        </span>
        @else
        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
          class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-800 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all active:scale-95">
          Next
        </button>
        @endif
      </div>
    </div>
  </nav>
  @endif
</div>