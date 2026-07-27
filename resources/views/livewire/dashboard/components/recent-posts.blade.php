<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Posts</h2>
        <a href="#" class="text-xs text-blue-600 hover:underline">View All</a>
    </div>

    <div class="space-y-3">
        @foreach($posts->take(5) as $post)
            <div wire:key="recent-post-{{ $post->id }}" class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-sm">
                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $post->title ?? 'Untitled Post' }}</p>
                        <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <livewire:post.dropdown-menu :post="$post" :key="'recent-dropdown-'.$post->id" />
            </div>
        @endforeach
    </div>
</div>
