<div class="bg-black min-h-screen py-6">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('home') }}" 
               wire:navigate
               class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Home
            </a>
        </div>

        {{-- Search Form --}}
        <div class="bg-gray-900/50 rounded-2xl border border-gray-800/50 p-4 md:p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-3">
                {{-- Search Input with Modern Button Inside --}}
                <div class="flex-1 relative">
                    <input 
                        wire:model.live.debounce.300ms="q"
                        type="text" 
                        placeholder="Search for videos..."
                        class="w-full bg-gray-800 text-white rounded-xl px-4 py-3 pl-11 pr-28 border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                        wire:keydown.enter="search"
                    >
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                    
                    {{-- Modern Search Button --}}
                    <button 
                        wire:click="search"
                        class="absolute right-1.5 top-1.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium px-5 py-2 rounded-lg transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                        Search
                    </button>
                </div>
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap gap-2 mt-4">
                {{-- Type Filter --}}
                <select 
                    wire:model.live="type"
                    class="bg-gray-800 text-white rounded-lg px-3 py-2 text-sm border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                >
                    <option value="all">All</option>
                    <option value="videos">Videos</option>
                    <option value="channels">Channels</option>
                </select>

                {{-- Sort Filter --}}
                <select 
                    wire:model.live="sort"
                    class="bg-gray-800 text-white rounded-lg px-3 py-2 text-sm border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                >
                    <option value="relevance">Relevance</option>
                    <option value="latest">Latest</option>
                    <option value="oldest">Oldest</option>
                    <option value="most_viewed">Most Viewed</option>
                    <option value="most_liked">Most Liked</option>
                </select>

                {{-- Category Filter --}}
                <select 
                    wire:model.live="category"
                    class="bg-gray-800 text-white rounded-lg px-3 py-2 text-sm border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                >
                    <option value="">All Categories</option>
                    <option value="action">🎬 Action & Adventure</option>
                    <option value="action_comedy">🎬 Action Comedy</option>
                    <option value="action_thriller">🎬 Action Thriller</option>
                    <option value="martial_arts">🥋 Martial Arts</option>
                    <option value="spy">🕵️ Spy</option>
                    <option value="comedy">😂 Comedy</option>
                    <option value="romantic_comedy">❤️ Romantic Comedy</option>
                    <option value="dark_comedy">🖤 Dark Comedy</option>
                    <option value="standup">🎤 Stand-up Comedy</option>
                    <option value="satire">🎭 Satire</option>
                    <option value="drama">😢 Drama</option>
                    <option value="period_drama">📜 Period Drama</option>
                    <option value="crime_drama">🔫 Crime Drama</option>
                    <option value="teen_drama">🧑‍🎓 Teen Drama</option>
                    <option value="melodrama">🎭 Melodrama</option>
                    <option value="romance">❤️ Romance</option>
                    <option value="romantic_drama">💔 Romantic Drama</option>
                    <option value="horror">😱 Horror</option>
                    <option value="thriller">🔪 Thriller</option>
                    <option value="psychological">🧠 Psychological</option>
                    <option value="sci_fi">🔬 Sci-Fi</option>
                    <option value="fantasy">🐉 Fantasy</option>
                    <option value="superhero">🦸 Superhero</option>
                    <option value="space">🚀 Space</option>
                    <option value="documentary">📖 Documentary</option>
                    <option value="biography">📝 Biography</option>
                    <option value="history">🏛️ History</option>
                    <option value="nature">🌿 Nature</option>
                    <option value="music">🎵 Music</option>
                    <option value="concert">🎸 Concert</option>
                    <option value="musical">🎭 Musical</option>
                    <option value="sports">⚽ Sports</option>
                    <option value="fitness">💪 Fitness</option>
                    <option value="kids">🧒 Kids</option>
                    <option value="family">👨‍👩‍👧 Family</option>
                    <option value="animation">🎨 Animation</option>
                    <option value="anime">🎌 Anime</option>
                    <option value="crime">📺 Crime</option>
                    <option value="reality">📺 Reality TV</option>
                    <option value="talk_show">🎙️ Talk Show</option>
                    <option value="cooking">🍳 Cooking</option>
                    <option value="travel">✈️ Travel</option>
                    <option value="fashion">👗 Fashion</option>
                    <option value="education">📚 Education</option>
                    <option value="technology">💻 Technology</option>
                    <option value="gaming">🎮 Gaming</option>
                    <option value="other">📌 Other</option>
                </select>

                {{-- Duration Filter --}}
                <select 
                    wire:model.live="duration"
                    class="bg-gray-800 text-white rounded-lg px-3 py-2 text-sm border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                >
                    <option value="">Any Duration</option>
                    <option value="short">Short (&lt; 5 min)</option>
                    <option value="medium">Medium (5-30 min)</option>
                    <option value="long">Long (&gt; 30 min)</option>
                </select>

                {{-- ✅ 18+ Mature Filter --}}
                <select 
                    wire:model.live="mature"
                    class="bg-gray-800 text-white rounded-lg px-3 py-2 text-sm border border-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                >
                    <option value="">All Content</option>
                    <option value="0">🟢 Safe (PG)</option>
                    <option value="1">🔞 18+ Mature</option>
                </select>

                {{-- Clear Filters --}}
                @if($q || $category || $duration || $type != 'all' || $sort != 'relevance' || $mature !== '')
                    <button 
                        wire:click="clearSearch"
                        class="text-gray-400 hover:text-white text-sm px-3 py-2 transition hover:bg-gray-800/50 rounded-lg"
                    >
                        Clear Filters ✕
                    </button>
                @endif
            </div>
        </div>

        {{-- Results Count --}}
        @if($q || $category)
            <div class="mb-4">
                <p class="text-gray-400 text-sm">
                    Found <span class="text-white font-medium">{{ number_format($totalResults) }}</span> results
                    @if($q)
                        for "<span class="text-white font-medium">{{ $q }}</span>"
                    @endif
                </p>
            </div>
        @endif

        {{-- Results Grid --}}
        @if($results->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($results as $post)
                    <div class="bg-gray-900/50 rounded-xl overflow-hidden border border-gray-800/50 hover:border-gray-700 transition group">
                        {{-- Thumbnail --}}
                        <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                            <div class="relative aspect-video bg-gray-800">
                                @if($post->video_thumbnail_url)
                                    <img 
                                        src="{{ $post->video_thumbnail_url }}" 
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                @endif
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
                                {{-- 18+ Badge --}}
                                @if($post->is_mature)
                                    <div class="absolute top-2 left-2 bg-red-600/90 text-white text-xs px-2 py-1 rounded font-bold">
                                        🔞 18+
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Info --}}
                        <div class="p-3">
                            <a href="{{ route('posts.show', $post->id) }}" wire:navigate>
                                <h3 class="text-white font-semibold text-sm line-clamp-2 hover:text-blue-400 transition">
                                    {{ $post->title ?? 'Untitled' }}
                                </h3>
                            </a>
                            <p class="text-gray-400 text-xs mt-1">{{ $post->user->name ?? 'Unknown' }}</p>
                            <div class="flex items-center gap-2 text-gray-500 text-xs mt-1">
                                <span>👁️ {{ number_format($post->views_count ?? 0) }}</span>
                                <span>•</span>
                                <span>👍 {{ number_format($post->likes_count ?? 0) }}</span>
                                @if($post->category_label)
                                    <span>•</span>
                                    <span class="text-blue-400">{{ $post->category_label }}</span>
                                @endif
                                @if($post->is_mature)
                                    <span>•</span>
                                    <span class="text-red-400">🔞</span>
                                @endif
                            </div>
                            <p class="text-gray-500 text-xs mt-1">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center">
                {{ $results->links() }}
            </div>
        @elseif($q || $category)
            {{-- No Results --}}
            <div class="bg-gray-900/50 rounded-2xl border border-gray-800/50 p-12 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-white text-xl font-bold mb-2">No results found</h3>
                <p class="text-gray-400 text-sm">
                    Try adjusting your search or filters to find what you're looking for.
                </p>
                <button 
                    wire:click="clearSearch"
                    class="mt-4 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-500/30"
                >
                    Clear Search
                </button>
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-gray-900/50 rounded-2xl border border-gray-800/50 p-12 text-center">
                <div class="text-6xl mb-4">🔎</div>
                <h3 class="text-white text-xl font-bold mb-2">Search for videos</h3>
                <p class="text-gray-400 text-sm">
                    Enter a keyword above to start searching for videos.
                </p>
            </div>
        @endif
    </div>
</div>