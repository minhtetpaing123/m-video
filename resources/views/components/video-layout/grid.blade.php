{{-- resources/views/components/video-layout/grid.blade.php --}}
@props([
    'posts',
    'emptyMessage' => 'No posts found',
    'cardType' => 'guest',
])

<div class="w-full">
    @if($posts->count() > 0)
        {{-- 
            YouTube Style Grid - All Screen Sizes
            xs: 1 col, sm: 2 cols, lg: 3 cols, xl: 4 cols, 2xl: 5 cols
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 sm:gap-4 md:gap-5 lg:gap-x-4 lg:gap-y-8">
            @foreach($posts as $index => $post)
                @if($post->video_cdn_url || $post->video_path || $post->link || $post->image)
                    <x-video-layout.card 
                        :post="$post" 
                        :index="$index"
                        :type="$cardType"
                    />
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🎬</div>
            <p class="text-gray-400 text-lg">{{ $emptyMessage }}</p>
            @auth
                <a href="#" onclick="openCreatePostModal()" 
                   class="text-blue-400 hover:underline inline-block mt-2">
                    Create your first post
                </a>
            @endauth
        </div>
    @endif
</div>