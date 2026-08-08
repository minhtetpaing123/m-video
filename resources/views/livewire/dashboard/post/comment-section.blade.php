<div x-data="{ 
        confirmModalOpen: false, 
        targetCommentId: null,
        highlightId: new URLSearchParams(window.location.search).get('comment_id')
     }" 
     class="relative z-50">
    
    <!-- Highlight Animation -->
    <style>
        @keyframes fbGreenHighlight {
            0% { background-color: rgba(34, 197, 94, 0.35); box-shadow: 0 0 12px rgba(34, 197, 94, 0.4); }
            85% { background-color: rgba(34, 197, 94, 0.35); box-shadow: 0 0 12px rgba(34, 197, 94, 0.4); }
            100% { background-color: transparent; box-shadow: none; }
        }
        .fb-green-highlight { animation: fbGreenHighlight 60s cubic-bezier(0.4, 0, 0.2, 1) forwards !important; }
    </style>

    <!-- Backdrop Overlay (အမဲရောင် နောက်ခံ - နှိပ်ပါက ချက်ချင်း ပိတ်မည်) -->
    <div 
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity cursor-pointer"
        @click="$dispatch('close-comments-modal-{{ $post->id }}')"
    ></div>

    <!-- Responsive Container -->
    <div class="fixed inset-x-0 bottom-0 sm:inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 pointer-events-none">
        
        <!-- Modal Content Box -->
        <div 
            class="bg-white dark:bg-gray-800 w-full sm:max-w-lg h-[80vh] sm:h-[650px] max-h-[90vh] rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden transition-all duration-300 transform pointer-events-auto"
        >
            <!-- 1. Header Area -->
            <div class="p-3 px-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-10 flex-shrink-0">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto absolute left-1/2 -translate-x-1/2 top-2 sm:hidden"></div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-2 sm:mt-0">Comments</h3>
                
                <!-- X Close Button -->
                <button 
                    @click="$dispatch('close-comments-modal-{{ $post->id }}')" 
                    type="button"
                    class="p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- 2. Scrollable Comments Body -->
            <div class="flex-1 overflow-y-auto overscroll-contain p-4 space-y-4">
                @forelse($comments as $comment)
                    @php $isOwnComment = $comment->user_id === auth()->id(); @endphp

                    <div 
                        id="comment-{{ $comment->id }}"
                        class="flex gap-2.5 items-start text-xs p-1 rounded-xl transition-all duration-300"
                        wire:key="comment-{{ $comment->id }}"
                        x-init="
                            if (highlightId == '{{ $comment->id }}' || '{{ request('comment_id') }}' == '{{ $comment->id }}') {
                                setTimeout(() => {
                                    $el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }, 250);
                            }
                        "
                    >
                        <!-- User Avatar -->
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 mt-0.5">
                            @if($comment->user && $comment->user->avatar_url)
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full {{ $isOwnComment ? 'bg-blue-600' : 'bg-gradient-to-br from-gray-500 to-gray-700' }} flex items-center justify-center text-white font-semibold text-xs">
                                    {{ $comment->user ? substr($comment->user->name, 0, 1) : 'U' }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <!-- Edit Form -->
                            @if(isset($editingCommentId) && $editingCommentId === $comment->id)
                                <div class="mt-1">
                                    <input 
                                        type="text" 
                                        wire:model="editText" 
                                        wire:keydown.enter="updateComment({{ $comment->id }})"
                                        wire:keydown.escape="cancelEdit"
                                        class="w-full bg-gray-100 dark:bg-gray-700 text-xs rounded-xl px-3 py-2 border border-blue-500 focus:outline-none text-gray-800 dark:text-gray-200"
                                    >
                                    <div class="flex gap-2 justify-end mt-1 text-[11px]">
                                        <button wire:click="cancelEdit" type="button" class="text-gray-500 hover:underline">Cancel</button>
                                        <button wire:click="updateComment({{ $comment->id }})" type="button" class="text-blue-600 font-bold hover:underline">Save</button>
                                    </div>
                                </div>
                            @else
                                <!-- Comment Bubble -->
                                <div class="rounded-2xl px-3.5 py-2 inline-block max-w-[85%] transition-all duration-300
                                     {{ $isOwnComment 
                                        ? 'bg-blue-600 text-white shadow-sm' 
                                        : 'bg-gray-100 dark:bg-gray-700/60 text-gray-800 dark:text-gray-200' }}"
                                     :class="(highlightId == '{{ $comment->id }}' || '{{ request('comment_id') }}' == '{{ $comment->id }}') ? 'fb-green-highlight' : ''">
                                    
                                    <span class="font-bold block text-[11px] mb-0.5 {{ $isOwnComment ? 'text-blue-100' : 'text-gray-900 dark:text-gray-100' }}">
                                        {{ $comment->user->name ?? 'User' }}
                                    </span>
                                    <p class="leading-relaxed text-xs break-words {{ $isOwnComment ? 'text-white' : '' }}">{{ $comment->content }}</p>
                                </div>

                                <!-- Action Links -->
                                <div class="flex items-center gap-3 text-[11px] text-gray-500 mt-1 ml-2">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    <button wire:click="toggleReply({{ $comment->id }})" type="button" class="font-bold hover:underline text-gray-600 dark:text-gray-400">Reply</button>
                                    
                                    @if($isOwnComment)
                                        <button wire:click="editComment({{ $comment->id }})" type="button" class="font-bold hover:underline text-blue-600 dark:text-blue-400">Edit</button>
                                    @endif

                                    @if($isOwnComment || $post->user_id === auth()->id())
                                        <button @click="targetCommentId = {{ $comment->id }}; confirmModalOpen = true;" type="button" class="font-bold hover:underline text-red-500">Delete</button>
                                    @endif
                                </div>
                            @endif

                            <!-- Reply Input Field -->
                            @if(isset($replyingToCommentId) && $replyingToCommentId === $comment->id)
                                <div class="flex gap-2 items-center mt-2 ml-2">
                                    <input 
                                        type="text" 
                                        wire:model="replyText" 
                                        placeholder="Write a reply..." 
                                        class="flex-1 bg-gray-100 dark:bg-gray-700 text-xs rounded-full px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 dark:text-gray-200"
                                        wire:keydown.enter="addReply({{ $comment->id }})"
                                    >
                                    <button wire:click="addReply({{ $comment->id }})" type="button" class="text-xs text-blue-600 font-bold hover:underline">
                                        Reply
                                    </button>
                                </div>
                            @endif

                            <!-- Nested Replies Area -->
                            @if($comment->replies && $comment->replies->count() > 0)
                                <div class="mt-2 space-y-2 border-l-2 border-gray-200 dark:border-gray-700 pl-3 ml-3">
                                    @foreach($comment->replies as $reply)
                                        @php $isOwnReply = $reply->user_id === auth()->id(); @endphp
                                        <div 
                                            id="comment-{{ $reply->id }}"
                                            class="flex gap-2 items-start text-xs group p-0.5 rounded-lg transition-all duration-300"
                                            wire:key="reply-{{ $reply->id }}"
                                            x-init="
                                                if (highlightId == '{{ $reply->id }}' || '{{ request('comment_id') }}' == '{{ $reply->id }}') {
                                                    setTimeout(() => {
                                                        $el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                                    }, 250);
                                                }
                                            "
                                        >
                                            <div class="w-6 h-6 rounded-full overflow-hidden flex-shrink-0 mt-0.5">
                                                @if($reply->user && $reply->user->avatar_url)
                                                    <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full {{ $isOwnReply ? 'bg-blue-600' : 'bg-gray-600' }} flex items-center justify-center text-white font-semibold text-[10px]">
                                                        {{ $reply->user ? substr($reply->user->name, 0, 1) : 'U' }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center gap-1">
                                                    <div class="rounded-2xl px-3 py-1.5 inline-block transition-all duration-300 
                                                        {{ $isOwnReply 
                                                            ? 'bg-blue-600 text-white shadow-sm' 
                                                            : 'bg-gray-100 dark:bg-gray-700/40 text-gray-800 dark:text-gray-200' }}"
                                                         :class="(highlightId == '{{ $reply->id }}' || '{{ request('comment_id') }}' == '{{ $reply->id }}') ? 'fb-green-highlight' : ''">
                                                        <span class="font-bold block text-[10px] {{ $isOwnReply ? 'text-blue-100' : 'text-gray-900 dark:text-gray-100' }}">{{ $reply->user->name ?? 'User' }}</span>
                                                        <p class="leading-relaxed text-xs break-words {{ $isOwnReply ? 'text-white' : '' }}">{{ $reply->content }}</p>
                                                    </div>

                                                    @if($isOwnReply || $post->user_id === auth()->id())
                                                        <button 
                                                            type="button"
                                                            @click="
                                                                targetCommentId = {{ $reply->id }}; 
                                                                confirmModalOpen = true;
                                                            "
                                                            class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 text-[10px] p-1 transition-opacity"
                                                        >
                                                            ✕
                                                        </button>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-gray-400 mt-0.5 block ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-xs">
                        No comments yet. Be the first to comment!
                    </div>
                @endforelse

                @if(isset($hasMore) && $hasMore)
                    <button wire:click="loadMore" type="button" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline py-2 block mx-auto">
                        View more comments...
                    </button>
                @endif
            </div>

            <!-- 3. Fixed Footer Input Box -->
            <div class="p-3 pb-16 sm:pb-3 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex-shrink-0">
                <form wire:submit.prevent="addComment" class="flex gap-2 items-center">
                    <input 
                        type="text" 
                        wire:model="commentText" 
                        placeholder="Write a comment..." 
                        class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs rounded-full px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="bg-blue-600 text-white font-medium text-xs rounded-full px-4 py-2.5 hover:bg-blue-700 transition-colors">
                        Post
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirmation Delete Modal -->
    <div 
        x-show="confirmModalOpen" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-cloak
    >
        <div 
            @click.away="confirmModalOpen = false" 
            class="bg-white dark:bg-gray-800 rounded-2xl max-w-xs w-full p-5 text-center shadow-2xl border border-gray-100 dark:border-gray-700 transform transition-all"
        >
            <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Delete Comment?</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-5">Are you sure you want to delete this? This action cannot be undone.</p>

            <div class="flex gap-2">
                <button 
                    type="button"
                    @click="confirmModalOpen = false" 
                    class="flex-1 py-2 px-3 text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="button"
                    @click="
                        $wire.deleteComment(targetCommentId); 
                        confirmModalOpen = false;
                    " 
                    class="flex-1 py-2 px-3 text-xs font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-md shadow-red-500/20 transition-colors"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
