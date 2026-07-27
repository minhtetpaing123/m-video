<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Posts --}}
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Posts</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_posts'] ?? 0) }}</h3>
        </div>
        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        </div>
    </div>

    {{-- Total Likes --}}
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Likes</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_likes'] ?? 0) }}</h3>
        </div>
        <div class="p-3 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
    </div>

    {{-- Total Comments --}}
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Comments</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_comments'] ?? 0) }}</h3>
        </div>
        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
    </div>

    {{-- Views --}}
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Views</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_views'] ?? 0) }}</h3>
        </div>
        <div class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
    </div>
</div>
