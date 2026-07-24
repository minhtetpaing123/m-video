<div>
    {{-- ⚠️ 18+ Warning Overlay (Category Page မှာပဲပြမယ်) --}}
    @if($showWarning)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-md" 
         style="animation: fadeIn 0.3s ease;">
        <div class="max-w-md mx-4 p-8 bg-gray-900/90 rounded-2xl border border-red-500/30 text-center shadow-2xl shadow-red-500/20">
            
            <div class="text-7xl mb-4">🔞</div>
            <h2 class="text-3xl font-bold text-red-500 mb-3">{{ __('18+ Mature Content') }}</h2>
            <p class="text-gray-300 text-sm mb-6 leading-relaxed">
                {{ __('This section contains mature content that may not be suitable for all audiences.') }} 
                {{ __('Please confirm that you are') }} <span class="text-white font-bold">{{ __('18 years or older') }}</span> {{ __('to continue.') }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button wire:click="confirmAge" 
                        class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-red-500/30 hover:shadow-red-500/50 transform hover:scale-[1.02] active:scale-95">
                    ✅ {{ __('I\'m 18+ Continue') }}
                </button>
                <a href="{{ route('home') }}" 
                   wire:navigate
                   class="px-8 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95">
                    ← {{ __('Go Back') }}
                </a>
            </div>
            
            <p class="text-gray-500 text-xs mt-4">
                {{ __('By continuing, you agree to view mature content.') }}
            </p>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <span class="text-3xl">🔞</span>
                <h1 class="text-2xl font-bold text-red-500">{{ __('18+ Mature Content') }}</h1>
            </div>
            <span class="text-sm text-gray-400">({{ $posts->total() }} {{ __('videos') }})</span>
        </div>

        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-sm flex items-center gap-2">
                <span class="text-lg">⚠️</span>
                {{ __('This content is for adults only. Viewer discretion is advised.') }}
            </p>
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($posts as $post)
                    <div class="bg-gray-800/50 rounded-xl overflow-hidden border border-gray-700/50 hover:border-gray-600 transition group">
                        <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                            <div class="relative aspect-video bg-gray-900">
                                @if($post->video_thumbnail_url)
                                    <img src="{{ $post->video_thumbnail_url }}" 
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @elseif($post->video_thumbnail)
                                    <img src="{{ Storage::url($post->video_thumbnail) }}" 
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @elseif($post->image)
                                    <img src="{{ $post->image_url }}" 
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2 bg-red-600/90 text-white text-xs px-2 py-1 rounded font-bold">
                                    🔞 {{ __('18+') }}
                                </div>
                                @if($post->video_duration)
                                    <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                                        @php
                                            $hours = floor($post->video_duration / 3600);
                                            $minutes = floor(($post->video_duration % 3600) / 60);
                                            $seconds = $post->video_duration % 60;
                                        @endphp
                                        @if($hours > 0)
                                            {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                                        @else
                                            {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-3">
                            <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                                <h3 class="text-white font-semibold text-sm line-clamp-2 hover:text-blue-400 transition">
                                    {{ $post->title ?? __('Untitled') }}
                                </h3>
                            </a>
                            <p class="text-gray-400 text-xs mt-1">{{ $post->user->name ?? 'Unknown' }}</p>
                            <div class="flex items-center gap-2 text-gray-500 text-xs mt-1">
                                <span>👁️ {{ number_format($post->views_count ?? 0) }}</span>
                                <span>•</span>
                                <span>👍 {{ number_format($post->likes_count ?? 0) }}</span>
                            </div>
                            <p class="text-gray-500 text-xs mt-1">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-gray-800/30 rounded-2xl border border-gray-700/50">
                <div class="text-5xl mb-3">🔞</div>
                <p class="text-gray-400 font-medium text-lg">{{ __('No mature content available') }}</p>
                <p class="text-gray-500 text-sm mt-2">{{ __('Check back later for more content') }}</p>
                <a href="{{ route('home') }}" wire:navigate class="inline-block mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-full transition">
                    {{ __('Browse All') }}
                </a>
            </div>
        @endif
    </div>

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    </style>
</div>