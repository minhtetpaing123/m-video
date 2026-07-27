<div>
    {{-- ============================================ --}}
    {{-- VIDEO CONTENT --}}
    {{-- ============================================ --}}
    <div class="w-full min-h-screen"
         style="background: var(--bg-primary); color: var(--text-primary);">
        <div class="max-w-4xl mx-auto">
            
            {{-- VIDEO PLAYER --}}
            <div class="w-full bg-black">
                @if($post->video_cdn_url)
                    <div class="block w-full overflow-hidden clear-both">
                        <x-common.video-player 
                            :src="$post->video_cdn_url"
                            :poster="$post->video_thumbnail_url"
                            :autoplay="false"
                            :title="$post->title ?? $post->content ?? 'Untitled'"
                            :views="number_format($post->views_count ?? 0)"
                            :time="$post->created_at->diffForHumans()"
                            :allow_download="false"
                        />
                    </div>
                @elseif($post->image)
                    <div class="bg-black">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title ?? 'Post image' }}" class="w-full max-h-[600px] object-contain">
                    </div>
                @endif
            </div>

            {{-- TITLE & CHANNEL / ACTION BAR --}}
            <div class="p-3 sm:p-4">
                {{-- TITLE --}}
                <h1 class="text-base sm:text-lg font-bold leading-snug line-clamp-2"
                    style="color: var(--text-primary);">
                    {{ $post->title ?? $post->content ?? 'Untitled' }}
                </h1>

                {{-- CHANNEL INFO & LIKE BUTTON --}}
                <div class="flex items-center justify-between mt-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-600 text-white font-bold flex items-center justify-center text-sm flex-shrink-0">
                            {{ substr($post->user->name ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold leading-tight"
                                style="color: var(--text-primary);">{{ $post->user->name ?? 'Cele Pop' }}</h3>
                            <span class="text-xs"
                                  style="color: var(--text-muted);">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- LIKE / DISLIKE PILL --}}
                    <div class="flex items-center rounded-full border divide-x"
                         style="background: var(--bg-secondary); border-color: var(--border-color);">
                        @auth
                            <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-l-full"
                                    style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/></svg>
                                <span>{{ number_format($post->likes_count ?? 0) }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-l-full"
                               style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/></svg>
                                <span>{{ number_format($post->likes_count ?? 0) }}</span>
                            </a>
                        @endauth
                        <button class="px-3 py-1.5 rounded-r-full"
                                style="color: var(--text-secondary);">
                            <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ACTION ICONS ROW --}}
                <div class="grid grid-cols-5 gap-2 text-center py-2 border-b"
                     style="border-color: var(--border-color);">
                    
                    {{-- Save Button --}}
                    @auth
                        <button id="saveBtn-{{ $post->id }}" class="flex flex-col items-center justify-center gap-1 transition"
                                style="color: var(--text-secondary);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Save') }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-1 transition"
                           style="color: var(--text-secondary);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Save') }}</span>
                        </a>
                    @endauth

                    {{-- Info Button --}}
                    @if($post->description)
                        <a href="{{ route('posts.description', $post->id) }}" 
                           class="flex flex-col items-center justify-center gap-1 transition"
                           style="color: var(--text-secondary);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Info') }}</span>
                        </a>
                    @else
                        <button onclick="showToast()" 
                                class="flex flex-col items-center justify-center gap-1 transition"
                                style="color: var(--text-secondary);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Info') }}</span>
                        </button>
                    @endif

                    {{-- Play Audio Button --}}
                    <button onclick="return false;" class="flex flex-col items-center justify-center gap-1 transition"
                            style="color: var(--text-secondary);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: var(--bg-secondary);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Play Audio') }}</span>
                    </button>

                    {{-- Share Button --}}
                    <button onclick="shareVideo()" class="flex flex-col items-center justify-center gap-1 transition"
                            style="color: var(--text-secondary);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </div>
                        <span class="text-xs font-medium" style="color: var(--text-muted);">{{ __('Share') }}</span>
                    </button>

                    {{-- ✅ Download Button (ပြင်ဆင်ပြီး) --}}
                    @if($post->video_cdn_url || $post->video_path)
                        <a href="{{ route('video.download.page', $post->id) }}" 
                           wire:navigate
                           class="flex flex-col items-center justify-center gap-1 transition"
                           style="color: var(--text-primary);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <span class="text-xs font-semibold">{{ __('Download') }}</span>
                        </a>
                    @else
                        <button onclick="return false;" class="flex flex-col items-center justify-center gap-1 transition"
                                style="color: var(--text-muted);">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <span class="text-xs font-semibold">{{ __('Download') }}</span>
                        </button>
                    @endif
                </div>

                {{-- YOUR ADS HERE --}}
                <div class="my-4 p-3 rounded-2xl flex items-center justify-center shadow-sm"
                     style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                    <span class="font-bold text-sm"
                          style="color: var(--text-muted);">{{ __('Your Ads Here') }}</span>
                </div>

                {{-- COMMENTS CARD --}}
                <div id="comment-section" class="rounded-2xl p-3 my-4"
                     style="background: var(--bg-secondary);">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm"
                                  style="color: var(--text-primary);">{{ __('Comments') }}</span>
                            <span class="text-xs"
                                  style="color: var(--text-muted);">• {{ number_format($post->comments_count ?? 0) }}</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             style="color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    </div>

                    @php $firstComment = $post->comments->first(); @endphp
                    @if($firstComment)
                        <div class="flex items-center gap-2 text-xs"
                             style="color: var(--text-secondary);">
                            <div class="w-6 h-6 rounded-full bg-red-500 text-white font-bold flex items-center justify-center text-[10px] flex-shrink-0">
                                {{ substr($firstComment->user->name ?? 'U', 0, 1) }}
                            </div>
                            <p class="truncate font-medium">{{ $firstComment->comment }}</p>
                        </div>
                    @else
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('No comments yet.') }}</p>
                    @endif

                    {{-- COMMENT INPUT BOX --}}
                    @auth
                        <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2 mt-3">
                            @csrf
                            <input type="text" name="comment" placeholder="{{ __('Write a comment...') }}"
                                   class="flex-1 text-xs rounded-full px-4 py-2 border focus:outline-none focus:ring-1 focus:ring-red-500"
                                   style="background: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required>
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-full transition">{{ __('Comment') }}</button>
                        </form>
                    @else
                        <div class="mt-2 text-xs" style="color: var(--text-muted);">
                            <a href="{{ route('login') }}" class="text-red-600 font-semibold hover:underline">{{ __('Login') }}</a> {{ __('to comment') }}
                        </div>
                    @endauth
                </div>

                {{-- RECOMMENDED VIDEOS --}}
                <div class="mt-6">
                    @php
                        $recommended = App\Models\Post::where('id', '!=', $post->id)
                            ->where('privacy', 'public')
                            ->where(function($q) {
                                $q->whereNotNull('video_cdn_url')
                                  ->orWhereNotNull('image');
                            })
                            ->latest()
                            ->limit(10)
                            ->get();
                    @endphp

                    <div class="space-y-4">
                        @forelse($recommended as $recommend)
                            <a href="{{ route('posts.show', $recommend->id) }}" class="block group">
                                <div class="relative w-full aspect-video rounded-xl overflow-hidden shadow-sm"
                                     style="background: var(--bg-secondary);">
                                    @if($recommend->video_thumbnail_url)
                                        <img src="{{ $recommend->video_thumbnail_url }}" alt="{{ $recommend->title ?? 'Video' }}" class="w-full h-full object-cover">
                                    @elseif($recommend->video_thumbnail)
                                        <img src="{{ Storage::url($recommend->video_thumbnail) }}" alt="{{ $recommend->title ?? 'Video' }}" class="w-full h-full object-cover">
                                    @elseif($recommend->image)
                                        <img src="{{ $recommend->image_url }}" alt="{{ $recommend->title ?? 'Image' }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"
                                             style="color: var(--text-muted);">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    @endif

                                    {{-- PLAY BUTTON OVERLAY --}}
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-black/60 flex items-center justify-center text-white">
                                            <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>

                                    @if($recommend->video_duration)
                                        <span class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] px-1.5 py-0.5 rounded font-mono">
                                            @php
                                                $h = floor($recommend->video_duration / 3600);
                                                $m = floor(($recommend->video_duration % 3600) / 60);
                                                $s = $recommend->video_duration % 60;
                                            @endphp
                                            @if($h > 0) {{ sprintf('%02d:%02d:%02d', $h, $m, $s) }} @else {{ sprintf('%02d:%02d', $m, $s) }} @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-sm font-bold line-clamp-2 leading-snug group-hover:text-red-600 transition"
                                        style="color: var(--text-primary);">{{ $recommend->title ?? $recommend->content ?? 'Untitled' }}</h4>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-center py-4" style="color: var(--text-muted);">{{ __('No recommendations available') }}</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- TOAST NOTIFICATION --}}
    {{-- ============================================ --}}
    <div id="toast-notification" 
         class="fixed bottom-24 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-500 ease-in-out"
         style="display: none; opacity: 0; transform: translateX(-50%) translateY(20px);">
        <div class="flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border"
             style="background: var(--bg-secondary); border-color: var(--border-color);">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                 style="background: rgba(239, 68, 68, 0.15);">
                <svg class="w-5 h-5" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold" style="color: var(--text-primary);">
                    {{ __('No Description') }}
                </p>
                <p class="text-xs" style="color: var(--text-muted);">
                    {{ __('This video does not have a description yet.') }}
                </p>
            </div>
            <button onclick="hideToast()" class="ml-2 p-1 rounded-full hover:bg-gray-700/50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- FLOATING CATEGORY FILTERS --}}
    {{-- ============================================ --}}
    <livewire:post.floating-category-filters />

    {{-- ============================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================ --}}
    <script>
    function shareVideo() {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({
                title: '{{ $post->title ?? "Video" }}',
                text: 'Check out this video!',
                url: url
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            }).catch(() => {
                const input = document.createElement('input');
                input.value = url;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                alert('Link copied to clipboard!');
            });
        }
    }

    function showToast() {
        const toast = document.getElementById('toast-notification');
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-50%) translateY(0)';
        }, 50);
        setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        const toast = document.getElementById('toast-notification');
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(20px)';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 500);
    }

    @auth
    document.addEventListener('DOMContentLoaded', function() {
        const saveBtn = document.getElementById('saveBtn-{{ $post->id }}');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const postId = {{ $post->id }};
                fetch('/api/posts/' + postId + '/save', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.saved) {
                        saveBtn.innerHTML = `
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-red-600">Saved</span>
                        `;
                    } else {
                        saveBtn.innerHTML = `
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--bg-secondary);">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-medium" style="color: var(--text-muted);">Save</span>
                        `;
                    }
                })
                .catch(() => {});
            });
        }
    });
    @endauth
    </script>
</div>