<div>
    @if($notification)
        <x-toast 
            :message="$notification['message']" 
            :type="$notification['type']"
            :undo="$notification['undo'] ?? false"
            :post-id="$notification['postId'] ?? null"
            :post-title="$notification['postTitle'] ?? null"
        />
    @endif

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-16 pt-4 transition-colors duration-300">
        <div class="w-full max-w-xl mx-auto px-2 sm:px-4">
            
            {{-- Create Post Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 mb-4 border border-gray-200/60 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                        {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                    </div>
                    <button wire:click="$dispatch('open-create-post-modal')"
                            class="flex-1 bg-gray-100 dark:bg-gray-700/60 hover:bg-gray-200/80 dark:hover:bg-gray-750 rounded-full px-5 py-2.5 text-gray-500 dark:text-gray-400 text-sm cursor-pointer transition-all duration-200 font-medium text-left">
                        What's on your mind, {{ auth()->user() ? auth()->user()->name : 'User' }}?
                    </button>
                </div>
            </div>

            {{-- 🔥 Progress Bar Component (Pure JavaScript) --}}
            <x-processbar.progress-bar 
                id="uploadProgress" 
                title="Uploading your post..." 
                autoInit="true" 
            />

            {{-- Create Post Modal --}}
            <livewire:post.create-post />

            {{-- Feed --}}
            @if($posts->count() > 0)
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <div wire:key="post-card-{{ $post->id }}" 
                             x-data="{ isVisible: true }" 
                             x-show="isVisible" 
                             x-on:remove-post-{{ $post->id }}.window="isVisible = false"
                             x-on:post-deleted.window="removeLoading({{ $post->id }})"
                             x-on:post-restored.window="if ({{ $post->id }} === $event.detail.postId) { isVisible = true; removeLoading({{ $post->id }}); }"
                             class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/60 dark:border-gray-700/50 overflow-hidden relative transition-all duration-300">
                            
                            {{-- Post Header --}}
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.show', ['user' => $post->user->id ?? 1]) }}" class="font-semibold text-gray-900 dark:text-slate-100 text-sm hover:underline hover:text-blue-600 transition">
                                            {{ $post->user->name ?? 'Unknown User' }}
                                        </a>
                                        <div class="text-xs text-gray-400 dark:text-gray-400 flex items-center gap-1.5 mt-0.5">
                                            <span>{{ $post->created_at->diffForHumans() }}</span>
                                            <span>•</span>
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm8.5-4.43c-.16-.46-.57-.5-1.5-.5H15v-3c0-.55-.45-1-1-1h-4v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                                    <livewire:post.dropdown-menu :post="$post" :key="'dropdown-'.$post->id" />
                                </div>
                            </div>

                            {{-- Post Texts --}}
                            @if($post->title || $post->content)
                                <div class="px-4 pb-3 text-gray-800 dark:text-slate-200 text-sm">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="inline-block group">
                                        <h2 class="font-semibold text-base text-gray-900 dark:text-white mb-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                            {{ $post->title }}
                                        </h2>
                                    </a>
                                    <p class="text-gray-600 dark:text-slate-400 text-sm leading-relaxed whitespace-pre-line">{{ $post->content }}</p>
                                </div>
                            @endif

                            {{-- Media --}}
                            <div class="relative bg-gray-50 dark:bg-slate-900 w-full overflow-hidden flex items-center justify-center min-h-[220px]" onclick="event.stopPropagation();">
                                
                                @if($post->category)
                                    <div class="absolute top-3 left-3 z-30 select-none pointer-events-none">
                                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.35)] backdrop-blur-[2px]">
                                            {{ $post->category_label }}
                                        </span>
                                    </div>
                                @endif

                                @if($post->video_url)
                                    <div class="w-full h-full relative">
                                        <livewire:player.video-player :post="$post" :key="'player-'.$post->id" />
                                    </div>
                                @elseif($post->image)
                                    <img src="{{ $post->image_url }}" alt="image" class="w-full h-full object-cover max-h-[500px]">
                                @else
                                    <div class="w-full py-16 flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-850 text-slate-400 dark:text-slate-500 border-y border-gray-100 dark:border-gray-700/30">
                                        <div class="p-3 rounded-full bg-white/60 dark:bg-gray-800/60 shadow-sm mb-2">
                                            <svg class="w-6 h-6 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium opacity-70">No Media Attached</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Engagement --}}
                            <div class="px-4 py-2.5 flex items-center justify-between text-xs text-gray-500 dark:text-slate-400 border-b border-gray-100 dark:border-slate-700/60" onclick="event.stopPropagation();">
                                <div class="flex items-center gap-1.5">
                                    <div class="flex items-center justify-center w-4 h-4 rounded-full bg-blue-500 text-[9px] text-white shadow-sm font-bold">👍</div>
                                    <span class="font-medium">{{ number_format($post->likes_count ?? 0) }} Likes</span>
                                </div>
                                <div class="flex items-center gap-3 font-medium">
                                    <span>{{ number_format($post->views_count ?? 0) }} Views</span>
                                    <span>•</span>
                                    <span>{{ $post->comments_count ?? 0 }} Comments</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex justify-between items-center p-1.5 bg-gray-50/50 dark:bg-gray-800/40" onclick="event.stopPropagation();">
                                <form action="{{ route('posts.react', $post->id) }}" method="POST" class="flex-1" onclick="event.stopPropagation();">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/60 font-semibold text-sm transition">
                                        <svg class="w-5 h-5 fill-current text-gray-500 dark:text-slate-400" viewBox="0 0 24 24"><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/></svg>
                                        <span>Like</span>
                                    </button>
                                </form>

                                <button type="button" class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/60 font-semibold text-sm transition">
                                    <svg class="w-5 h-5 fill-current text-gray-500 dark:text-slate-400" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                    <span>Comment</span>
                                </button>

                                <button type="button" class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/60 font-semibold text-sm transition">
                                    <svg class="w-5 h-5 fill-current text-gray-500 dark:text-slate-400" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                                    <span>Share</span>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/60 dark:border-gray-700/50 shadow-sm">
                    <div class="text-5xl mb-3">📭</div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No posts found in the feed</p>
                </div>
            @endif
        </div>
    </div>

    <x-floating-category-filters />

    <script>
    function showLoading(postId) {
        const postCard = document.querySelector(`[wire\\:key='post-card-${postId}']`);
        if (postCard) {
            const existingLoading = document.getElementById(`loading-${postId}`);
            if (existingLoading) existingLoading.remove();
            
            const loadingEl = document.createElement('div');
            loadingEl.id = `loading-${postId}`;
            loadingEl.className = 'absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-gray-900/80 z-20';
            loadingEl.style.pointerEvents = 'auto';
            loadingEl.innerHTML = `
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Deleting...</span>
                </div>
            `;
            
            postCard.style.position = 'relative';
            postCard.appendChild(loadingEl);
        }
    }

    function removeLoading(postId) {
        const loadingEl = document.getElementById(`loading-${postId}`);
        if (loadingEl) loadingEl.remove();
        const postCard = document.querySelector(`[wire\\:key='post-card-${postId}']`);
        if (postCard) postCard.style.position = '';
    }

    function removeAllLoading() {
        document.querySelectorAll('[id^="loading-"]').forEach(el => el.remove());
        document.querySelectorAll('[wire\\:key^="post-card-"]').forEach(el => el.style.position = '');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="options-dropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    document.addEventListener('livewire:initialized', function() {
        console.log('🎯 Livewire initialized');

        // 🔥 Post deleted event
        Livewire.on('post-deleted', function() {
            console.log('🗑️ Post deleted');
            removeAllLoading();
        });
        
        // 🔥 Post created event - Feed ကို Refresh လုပ်မယ် (page reload မလုပ်ဘူး)
        Livewire.on('post-created', function() {
            console.log('✅ Post created, refreshing feed WITHOUT page reload');
            // 🔥 $refresh က component ကို refresh လုပ်မယ် (page reload မလုပ်ဘူး)
            @this.dispatch('$refresh');
        });

        // 🔥 Refresh posts event
        Livewire.on('refresh-posts', function() {
            console.log('🔄 Refresh posts called');
            @this.dispatch('$refresh');
        });

        // 🔥 Post restored event
        Livewire.on('post-restored', function(data) {
            var postId = data.postId || (data[0] ? data[0].postId : null);
            if(postId) {
                console.log('♻️ Post restored:', postId);
                removeLoading(postId);
                var postCard = document.querySelector('[wire\\:key="post-card-' + postId + '"]');
                if (postCard && postCard.__x) postCard.__x.$data.isVisible = true;
            }
        });
        
        Livewire.on('clear-restored-id', function() {
            Livewire.dispatch('clearRestoredId');
        });
    });
    </script>
    <x-user-header />
<livewire:layout.nav active="home" />
</div>