<div class="relative bg-black w-full overflow-hidden flex items-center justify-center min-h-[240px]" onclick="event.stopPropagation();">
    
    {{-- Category Badge --}}
    @if($post->category)
        <div class="absolute top-3 left-3 z-30 select-none pointer-events-none">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold text-white bg-blue-600/90 rounded-full shadow-md backdrop-blur-md">
                ❤️ {{ $post->category_label ?? $post->category }}
            </span>
        </div>
    @endif

    {{-- Video/Image Content --}}
    @if($post->video_url)
        <div class="w-full h-full relative">
            <livewire:player.video-player :post="$post" :key="'player-'.$post->id" />
        </div>
    @elseif($post->image)
        <img src="{{ $post->image_url }}" alt="image" class="w-full h-full object-cover max-h-[500px]">
    @else
        <div class="w-full py-16 flex flex-col items-center justify-center bg-gray-900 text-gray-500">
            <svg class="w-8 h-8 opacity-50 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span class="text-xs">No Media Attached</span>
        </div>
    @endif
</div>
