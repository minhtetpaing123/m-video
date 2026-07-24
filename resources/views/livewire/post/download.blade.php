<div class="bg-black min-h-screen flex items-center justify-center py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        
        {{-- Video Info Card --}}
        <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 shadow-2xl">
            
            {{-- Thumbnail --}}
            <div class="relative aspect-video bg-black">
                @if($post->video_thumbnail_url)
                    <img src="{{ $post->video_thumbnail_url }}" 
                         alt="{{ $post->title }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500">
                        <span class="text-6xl">🎬</span>
                    </div>
                @endif
            </div>
            
            {{-- Info --}}
            <div class="p-6">
                <h1 class="text-white text-2xl font-bold mb-2">{{ $post->title ?? 'Untitled' }}</h1>
                <p class="text-gray-400 text-sm mb-4">{{ $post->user->name ?? 'Unknown' }} • {{ $post->created_at->diffForHumans() }}</p>
                
                <div class="flex items-center gap-3 text-sm text-gray-400 mb-4">
                    <span>👁️ {{ number_format($post->views_count ?? 0) }} views</span>
                    @if($post->video_size)
                        <span>•</span>
                        <span>💾 {{ number_format($post->video_size / 1048576, 2) }} MB</span>
                    @endif
                    @if($post->video_duration)
                        <span>•</span>
                        <span>⏱️ 
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
                        </span>
                    @endif
                </div>
                
                {{-- ADS PLACEHOLDER --}}
                <div class="bg-gray-800/50 rounded-xl p-4 mb-6 text-center border border-gray-700">
                    <p class="text-gray-500 text-xs uppercase tracking-wider mb-2">📢 Advertisement</p>
                    <div class="h-16 flex items-center justify-center text-gray-600">
                        <span class="text-sm">Your Ad Here</span>
                    </div>
                </div>
                
                {{-- DOWNLOAD BUTTON --}}
                <a href="{{ route('video.download.file', $post->id) }}" 
                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg shadow-green-600/30">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                        </svg>
                        <span>Download Video</span>
                    </div>
                    <span class="text-sm text-green-300 mt-1 block">File size: {{ number_format($post->video_size / 1048576, 2) }} MB</span>
                </a>
                
                {{-- Back Button --}}
                <a href="{{ route('posts.show', $post->id) }}" 
                   wire:navigate
                   class="block w-full text-center text-gray-400 hover:text-white mt-4 transition-colors">
                    ← Back to Video
                </a>
            </div>
        </div>
    </div>
</div>