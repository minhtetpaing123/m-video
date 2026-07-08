<!-- resources/views/components/post/header.blade.php -->
@props(['post'])

<div class="flex items-center p-3">
    {{-- Avatar --}}
    <div class="w-10 h-10 rounded-full overflow-hidden mr-2">
        @if($post->user->profile_photo)
            <img src="{{ Storage::url($post->user->profile_photo) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                {{ substr($post->user->name, 0, 1) }}
            </div>
        @endif
    </div>
    
    {{-- User Info --}}
    <div class="flex-1">
        <div class="flex items-center">
            <h3 class="font-semibold text-gray-800 text-sm">{{ $post->user->name }}</h3>
            @if($post->user->verified)
                <svg class="w-4 h-4 ml-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
        <div class="flex items-center text-xs text-gray-500">
            <span>{{ $post->created_at->diffForHumans() }}</span>
            <span class="mx-1">·</span>
            @if($post->privacy == 'public')
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.5 5.173 6.958 5.1 7.418 5.1H8.6c.397 0 .78.124 1.097.354.318.23.55.552.656.916.09.313.136.637.136.962 0 .324-.045.648-.135.96-.106.364-.338.686-.656.916a1.902 1.902 0 01-1.098.354h-1.18c-.46 0-.918.074-1.305.222l-1.387.53a.19.19 0 01-.125-.002.184.184 0 01-.08-.073 6.012 6.012 0 01-1.474-3.592zM12 13.4a3.6 3.6 0 00-3.6-3.6H7.8a3.6 3.6 0 00-3.6 3.6V15h7.8v-1.6z" />
                </svg>
            @elseif($post->privacy == 'friends')
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
            @endif
        </div>
    </div>
    
    {{-- Three Dots Menu Component --}}
    <x-post.menu-button :post="$post" />
</div>