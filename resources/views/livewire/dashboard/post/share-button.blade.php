<div x-data="{ 
    showShareModal: false,
    shareUrl: '{{ url('/posts/' . $post->id) }}',
    postTitle: '{{ addslashes($post->caption ?? $post->title ?? 'Check out this post!') }}',
    copied: false,

    doShare() {
        this.showShareModal = true;
    },
    copyToClipboard() {
        navigator.clipboard.writeText(this.shareUrl);
        this.copied = true;
        $wire.sharePost();
        setTimeout(() => { this.copied = false; this.showShareModal = false; }, 1500);
    }
}">
    <!-- Share Button -->
    <button @click="doShare()" type="button" class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
        </svg>
        <span>Share</span>
    </button>

    <!-- Facebook Style Custom Share Modal -->
    <div 
        x-show="showShareModal" 
        x-transition.opacity
        @click.away="showShareModal = false"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/60 backdrop-blur-sm"
        style="display: none;"
        x-cloak
    >
        <div class="bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-2xl shadow-xl max-w-md w-full p-4 border border-gray-100 dark:border-gray-700">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Share</h3>
                <button @click="showShareModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg">✕</button>
            </div>

            <!-- Share Options Grid -->
            <div class="grid grid-cols-4 gap-4 py-4 text-center">
                
                <!-- Copy Link -->
                <button @click="copyToClipboard()" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-[11px] text-gray-600 dark:text-gray-400" x-text="copied ? 'Copied!' : 'Copy link'"></span>
                </button>

                <!-- Facebook Share -->
                <a :href="'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl)" target="_blank" @click="$wire.sharePost(); showShareModal = false;" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 dark:text-gray-400">Facebook</span>
                </a>

                <!-- Telegram Share -->
                <a :href="'https://t.me/share/url?url=' + encodeURIComponent(shareUrl)" target="_blank" @click="$wire.sharePost(); showShareModal = false;" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center text-sky-500 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.56 8.16l-1.97 9.28c-.15.67-.54.83-1.1.52l-3.02-2.23-1.46 1.4c-.16.16-.3.3-.61.3l.22-3.1 5.64-5.1c.25-.22-.05-.34-.38-.12L7.87 13.4l-3-.94c-.65-.2-.66-.65.14-.97l11.73-4.52c.54-.2 1.02.13.82.82z"/></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 dark:text-gray-400">Telegram</span>
                </a>

                <!-- More System Share -->
                <button @click="if (navigator.share) { navigator.share({ title: postTitle, url: shareUrl }); $wire.sharePost(); showShareModal = false; }" class="flex flex-col items-center gap-1.5 group">
                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 dark:text-gray-400">More</span>
                </button>

            </div>
        </div>
    </div>
</div>
