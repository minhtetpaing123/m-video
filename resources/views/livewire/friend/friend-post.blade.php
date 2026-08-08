<div class="w-full space-y-3">
    {{-- Header --}}
    <div class="flex items-center justify-between pt-1 px-4">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">
            Friends' Posts
        </h2>
    </div>

    {{-- Posts Feed (Full Width Screen Alignment) --}}
    <div class="-mx-4 sm:mx-0 space-y-3">
        @forelse($posts as $post)
            <div wire:key="friend-post-wrapper-{{ $post->id }}" class="w-full">
                @livewire('dashboard.post.post-card', ['post' => $post], key('friend-post-card-'.$post->id))
            </div>
        @empty
            <div class="mx-4 text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-800 px-4">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">No Posts from Friends Yet</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Posts shared by your friends will show up here.</p>
            </div>
        @endforelse
    </div>
</div>
