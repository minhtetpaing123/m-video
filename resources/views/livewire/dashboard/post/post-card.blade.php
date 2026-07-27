@php
    $layoutMode = session('user_feed_layout', 'normal');
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/60 dark:border-gray-700/50 overflow-visible flex flex-col justify-between h-full {{ $layoutMode === 'grid' ? 'mb-0' : 'mb-4' }}"
     wire:key="post-card-{{ $post->id }}"
     x-data="{ isVisible: true }"
     x-show="isVisible">
    
    <div>
        {{-- Post Header --}}
        <div class="p-4 flex items-center justify-between relative z-20">
            <div class="flex items-center gap-3">
                
                {{-- User Profile Picture (Clickable) --}}
                <a href="{{ route('profile.show', $post->user->id) }}" wire:navigate class="flex-shrink-0 cursor-pointer transition-transform hover:scale-105">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 overflow-hidden">
                        {{-- ✅ ပြင်ဆင်ချက်: avatar_url အား တိုက်ရိုက်ခေါ်သုံးခြင်း --}}
                        @if($post->user)
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                        @else
                            U
                        @endif
                    </div>
                </a>

                <div>
                    {{-- Username (Clickable) --}}
                    <a href="{{ route('profile.show', $post->user->id) }}" wire:navigate class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:underline">
                        {{ $post->user->name ?? 'Unknown User' }}
                    </a>
                    
                    {{-- Time and Facebook-style Privacy Icon --}}
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $post->created_at ? $post->created_at->diffForHumans() : '' }}</span>
                        <span>•</span>
                        
                        {{-- Privacy Indicator (Globe, Friends, Lock) --}}
                        @php
                            $visibility = $post->visibility ?? 'public';
                        @endphp

                        @switch($visibility)
                            @case('friends')
                                {{-- Friends Icon (လူနှစ်ဦးပုံ) --}}
                                <span title="Friends">
                                    <svg class="w-3.5 h-3.5 inline text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M13 8c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-2 1h-1.13c-.97.5-2.05.8-3.17.8s-2.2-.3-3.17-.8H3c-1.66 0-3 1.34-3 3v1c0 .55.45 1 1 1h10c.55 0 1-.45 1-1v-1c0-1.66-1.34-3-3-3zm-6-2c1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm9 4H1v-.5C1 10.9 3.68 9 7 9s6 1.9 6 3.5v.5z"/>
                                    </svg>
                                </span>
                                @break

                            @case('private')
                            @case('only_me')
                                {{-- Only Me Icon (Lock ပုံ) --}}
                                <span title="Only Me">
                                    <svg class="w-3.5 h-3.5 inline text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11 5V4a3 3 0 0 0-6 0v1H4v9h8V5h-1zm-5-1a2 2 0 1 1 4 0v1H6V4zm5 9H5V6h6v7z"/>
                                    </svg>
                                </span>
                                @break

                            @default
                                {{-- Public Icon (ကမ္ဘာလုံးပုံ) --}}
                                <span title="Public">
                                    <svg class="w-3.5 h-3.5 inline text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM5.5 1.51c1.23.63 2.15 1.7 2.5 3.01-.73-.2-1.5-.32-2.3-.35-.38-.97-.9-1.85-1.52-2.66-.46-.02-.92.03-1.38.16zm-3.18 1.1c.64.84 1.18 1.76 1.58 2.76-.87.05-1.74.2-2.58.46a6.51 6.51 0 0 1 1-3.22zm-.98 4.39c.9-.27 1.83-.43 2.78-.48.06.45.1 .9.12 1.37h-3c0-.3 0-.6.03-.89zm1.03 3.01c.78.26 1.6.41 2.45.46-.03.46-.07.92-.12 1.38-.95-.06-1.88-.22-2.78-.48.15-1.15.53-2.25 1.05-3.27zm2.42 4.14c-.37 1.32-1.3 2.4-2.55 3.04.47.14.94.2 1.41.18.63-.82 1.15-1.71 1.54-2.66-.13-.19-.26-.38-.4-.56zm1.18-1.53c.8-.03 1.58-.15 2.32-.35-.37 1.33-1.31 2.42-2.57 3.06-.15-.19-.3-.38-.45-.58.39-.68.73-1.42 1-2.13zm3.22-.38c.84-.26 1.65-.63 2.4-1.11.47 1 .77 2.1.88 3.25-.86.26-1.76.42-2.68.49.13-.88.29-1.75.4-2.63zm1.11-3.69c.01-.29.01-.59.01-.89h3c-.02.47-.07.93-.13 1.38-.95.05-1.88.2-2.78.47.05-.31.08-.63.1-.96zm-.97-2.39c-.36-1.32-1.3-2.41-2.57-3.06.14.19.29.38.44.57-.38.68-.72 1.41-1 2.12-.8-.03-1.59-.15-2.33-.35.37-1.33 1.31-2.42 2.57-3.06-.15.19-.29.38-.43.57z"/>
                                    </svg>
                                </span>
                        @endswitch
                    </div>
                </div>
            </div>

            {{-- Sub-component အဖြစ် သီးသန့် ခေါ်သုံးခြင်း --}}
            <livewire:dashboard.post.post-options-menu :post="$post" :key="'options-'.$post->id" />
        </div>

        {{-- Caption / Text Content --}}
        @if($post->title || $post->content)
            <div class="px-4 pb-3 text-sm text-gray-800 dark:text-gray-200 leading-relaxed line-clamp-2">
                {{ $post->title ?? $post->content }}
            </div>
        @endif

        {{-- Media Player --}}
        <livewire:dashboard.post.media-player :post="$post" :key="'media-'.$post->id" />
    </div>

    {{-- Like, Comment, Share Sub-component --}}
    <livewire:dashboard.post.post-footer :post="$post" :key="'footer-'.$post->id" />
</div>
