<div class="w-full">
    @if(count($posts) > 0)
        @php
            $gridClasses = match($viewMode) {
                'grid' => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4',
                'list' => 'grid-cols-1 gap-4',
                'netflix' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3',
                'youtube' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5',
                default => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4'
            };
        @endphp

        <div class="grid {{ $gridClasses }}">
            @foreach($posts as $index => $post)
                @if($post->video_cdn_url || $post->video_path || $post->link || $post->image)
                    <livewire:layout.video-card 
                        :post="$post" 
                        :index="$index"
                        :type="$cardType"
                        :view-mode="$viewMode"
                        :key="'video-card-'.$post->id"
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
                    {{ __('Create your first post') }}
                </a>
            @endauth
        </div>
    @endif
</div>