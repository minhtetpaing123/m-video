<div>
    {{-- Header Container (Header ကို Mobile Screen မူလ Size အသေထိန်းချုပ်ထားသည်) --}}
    <div class="fixed top-0 left-0 right-0 z-30 flex justify-center bg-white dark:bg-gray-900 shadow-sm border-b border-gray-100 dark:border-gray-800">
        <div class="w-full max-w-xl px-2 sm:px-4">
            <livewire:dashboard.user-header />
        </div>
    </div>

    {{-- Toast Notification --}}
    @if($notification)
        <x-toast 
            :message="$notification['message']" 
            :type="$notification['type']"
            :undo="$notification['undo'] ?? false"
            :post-id="$notification['postId'] ?? null"
            :post-title="$notification['postTitle'] ?? null"
        />
    @endif

    @php
        // Session မရှိပါက YouTube ('grid') ကို Default ထားမည်
        $layoutMode = session('user_feed_layout', 'grid');
    @endphp

    {{-- Main Background Container --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-20 pt-16 sm:pt-20 transition-colors duration-300">
        
        {{-- Top Section: Create Post Card & Progress Bar (အမြဲ Center ထဲတွင်ပဲ ရှိမည်) --}}
        <div class="w-full max-w-xl mx-auto px-2 sm:px-4">
            {{-- Create Post Input Card --}}
            <livewire:dashboard.post.create-post-card />

            {{-- Progress Bar --}}
            <x-processbar.progress-bar 
                id="uploadProgress" 
                title="Uploading your post..." 
                autoInit="true" 
            />

            {{-- Create Post Modal Component --}}
            <livewire:post.create-post />
        </div>

        {{-- Video Feed Section (ဖုန်းမှာ Single Column အဖြစ်ထိန်းထားပြီး Screen ကျယ်မှ Grid ခွဲမည်) --}}
        <div class="w-full mx-auto px-2 sm:px-4 mt-4
            @if(in_array($layoutMode, ['grid', 'netflix']))
                max-w-7xl
            @elseif($layoutMode === 'wide')
                max-w-4xl
            @else
                max-w-xl
            @endif
        ">
            @if($posts->count() > 0)
                <div class="
                    @if($layoutMode === 'grid')
                        grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 space-y-0
                    @elseif($layoutMode === 'netflix')
                        grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 space-y-0
                    @else
                        space-y-4
                    @endif
                ">
                    @foreach($posts as $post)
                        {{-- Post Card --}}
                        @include('livewire.dashboard.post.post-card', ['post' => $post])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="max-w-xl mx-auto">
                    <livewire:dashboard.post.empty-state />
                </div>
            @endif
        </div>

    </div>

    {{-- Floating Filter Button --}}
    @include('livewire.dashboard.post.category-filter')

    {{-- Scripts --}}
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
            document.querySelectorAll('[id^="optionsdropdown-"]').forEach(el => el.classList.add('hidden'));
        }
    });

    document.addEventListener('livewire:initialized', function() {
        Livewire.on('post-deleted', function() {
            removeAllLoading();
        });
        
        Livewire.on('post-created', function() {
            $wire.$refresh();
        });

        Livewire.on('refresh-posts', function() {
            $wire.$refresh();
        });

        Livewire.on('post-restored', function(data) {
            var postId = data.postId || (data[0] ? data[0].postId : null);
            if(postId) {
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

    {{-- Bottom Navigation Bar --}}
    <livewire:layout.nav active="home" />
</div>
