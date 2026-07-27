<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Most Engaging Posts</h2>
    </div>

    <div class="space-y-3">
        @foreach($topPosts ?? $posts->take(5) as $post)
            <div wire:key="top-post-{{ $post->id }}" class="flex items-center justify-between p-3 border-b border-gray-100 dark:border-gray-700/50 last:border-none">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $post->title ?? 'Untitled' }}</p>
                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <span>👍 {{ $post->likes_count ?? 0 }}</span>
                        <span>💬 {{ $post->comments_count ?? 0 }}</span>
                    </div>
                </div>
                <span class="text-xs font-medium px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full">
                    Top
                </span>
            </div>
        @endforeach
    </div>
</div>
