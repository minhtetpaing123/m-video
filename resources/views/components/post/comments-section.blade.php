
<!-- resources/views/components/post/comments-section.blade.php -->
@props(['comments', 'commentsCount', 'postId'])

{{-- Full Screen Comment Popup --}}
<div class="fixed inset-0 bg-white z-[9999] hidden flex flex-col" id="comment-popup-{{ $postId }}">
    {{-- Header --}}
    <div class="flex items-center px-4 py-3 border-b border-gray-200 bg-white">
        <button class="close-popup p-1 mr-4" data-post-id="{{ $postId }}">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-lg font-semibold comment-header">Comments ({{ $commentsCount }})</h2>
    </div>

    {{-- Comments List --}}
    <div class="flex-1 overflow-y-auto px-4 py-3 pb-4 bg-white" id="comments-list-{{ $postId }}">
        @forelse($comments as $comment)
            <x-post.comment.item 
                :comment="$comment" 
                :post-id="$postId" 
                :level="0" 
            />
        @empty
            <div class="text-center text-gray-500 text-sm py-8 empty-state">
                No comments yet. Be the first to comment!
            </div>
        @endforelse
    </div>

    {{-- Comment Form --}}
    <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 py-3">
        <div class="flex items-center space-x-2">
            {{-- Current User Avatar --}}
            <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0 overflow-hidden">
                @if(auth()->user() && auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
            
            {{-- Comment Input --}}
            <div class="flex-1 relative">
                <input type="text" 
                       id="main-comment-input-{{ $postId }}"
                       placeholder="Write a comment..." 
                       class="w-full text-sm bg-gray-100 border border-gray-300 rounded-full px-4 py-3 pr-12 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <button class="main-comment-submit absolute right-2 top-1/2 transform -translate-y-1/2 text-blue-500 font-semibold text-sm px-4 py-1 hover:text-blue-700"
                        data-post-id="{{ $postId }}">
                    Post
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="fixed inset-0 bg-black bg-opacity-50 z-[10000] hidden items-center justify-center" id="delete-modal">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Comment</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this comment? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800" id="cancel-delete">
                Cancel
            </button>
            <button class="px-4 py-2 text-sm font-semibold text-white bg-red-500 rounded-lg hover:bg-red-600" id="confirm-delete">
                Delete
            </button>
        </div>
    </div>
</div>

<style>
#comment-popup-{{ $postId }} {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    background-color: white;
}

#comment-popup-{{ $postId }}:not(.hidden) {
    display: flex !important;
}

.like-active {
    color: #2563eb !important;
    font-weight: 600;
}

.reply-indent-1 { margin-left: 2.5rem; }
.reply-indent-2 { margin-left: 5rem; }
.reply-indent-3 { margin-left: 7.5rem; }
.reply-indent-4 { margin-left: 10rem; }
</style>

<script>
// ==================== COMPLETE LIVE UPDATE SYSTEM (DELETE ONLY PAGE RELOAD) ====================
(function() {
    if (window.commentSystemFinal) return;
    window.commentSystemFinal = true;

    console.log('✅ Comment System Loaded');

    $(document).ready(function() {
        let scrollPosition = 0;
        let commentToDelete = null;
        let isProcessing = false;
        const currentPostId = '{{ $postId }}';
        const currentUser = {
            id: {{ auth()->id() ?? 'null' }},
            name: '{{ auth()->user()->name ?? "You" }}',
            avatar: '{{ auth()->user()->avatar ?? null }}'
        };

        // ========== POPUP ==========
        $(document).on('click', '.comment-toggle', function(e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            scrollPosition = $(window).scrollTop();
            $('[id^="comment-popup-"]').addClass('hidden');
            $(`#comment-popup-${postId}`).removeClass('hidden');
        });

        $(document).on('click', '.close-popup', function(e) {
            e.preventDefault();
            $('[id^="comment-popup-"]').addClass('hidden');
            $(window).scrollTop(scrollPosition);
        });

        // ========== LIKE ==========
        $(document).on('click', '.like-comment', function(e) {
            e.preventDefault();
            const btn = $(this);
            btn.toggleClass('like-active');
            btn.text(btn.hasClass('like-active') ? 'Liked' : 'Like');
            
            // Optional: Send to server
            $.ajax({
                url: `/comments/${btn.data('comment-id')}/like`,
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') }
            });
        });

        // ========== REPLY FORM ==========
        $(document).on('click', '.reply-button', function(e) {
            e.preventDefault();
            const commentId = $(this).data('comment-id');
            const username = $(this).data('username');
            
            $('.reply-form, .edit-form').addClass('hidden');
            const replyForm = $(`#reply-form-${commentId}`).removeClass('hidden');
            replyForm.find('.reply-input')
                .attr('placeholder', `Reply to ${username}...`)
                .focus();
        });

        // ========== EDIT FORM ==========
        $(document).on('click', '.edit-comment', function(e) {
            e.preventDefault();
            const commentId = $(this).data('comment-id');
            $('.reply-form, .edit-form').addClass('hidden');
            $(`#edit-form-${commentId}`).removeClass('hidden');
        });

        $(document).on('click', '.cancel-edit', function(e) {
            e.preventDefault();
            $(`#edit-form-${$(this).data('comment-id')}`).addClass('hidden');
        });

        // ========== DELETE MODAL - PAGE RELOAD ONLY ==========
        $(document).on('click', '.delete-comment', function(e) {
            e.preventDefault();
            commentToDelete = $(this).data('comment-id');
            $('#delete-modal').removeClass('hidden').css('display', 'flex');
        });

        $('#cancel-delete').click(function(e) {
            e.preventDefault();
            $('#delete-modal').addClass('hidden');
            commentToDelete = null;
        });

        $('#confirm-delete').click(function(e) {
            e.preventDefault();
            
            if (!commentToDelete) {
                $('#delete-modal').addClass('hidden');
                return;
            }
            
            const deleteBtn = $(this);
            deleteBtn.text('...').prop('disabled', true);
            
            $.ajax({
                url: `/comments/${commentToDelete}`,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    // PAGE RELOAD FOR DELETE ONLY
                    location.reload();
                },
                error: function() {
                    alert('Delete failed');
                    $('#delete-modal').addClass('hidden');
                    deleteBtn.text('Delete').prop('disabled', false);
                    commentToDelete = null;
                }
            });
        });

        // ========== MAIN COMMENT - FIXED ==========
        $(document).on('click', '.main-comment-submit', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const postId = btn.data('post-id');
            const input = $(`#main-comment-input-${postId}`);
            const content = input.val().trim();
            
            if (!content) return;

            btn.prop('disabled', true).text('...');

            $.ajax({
                url: `/posts/${postId}/comments`,
                method: 'POST',
                data: {
                    content: content,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.success) {
                        input.val('');
                        addComment(res.comment);
                        updateCounts(1);
                    } else {
                        alert('Failed to post comment');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('Error posting comment. Check console.');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Post');
                }
            });
        });

        // ========== REPLY SUBMIT ==========
        $(document).on('click', '.reply-submit', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const commentId = btn.data('comment-id');
            const parentId = btn.data('parent-id');
            const username = btn.data('username');
            const input = $(`.reply-input[data-comment-id="${commentId}"]`);
            const content = input.val().trim();
            
            if (!content) return;

            btn.prop('disabled', true).text('...');

            $.ajax({
                url: `/posts/${currentPostId}/comments`,
                method: 'POST',
                data: {
                    content: content,
                    parent_id: parentId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.success) {
                        input.val('');
                        addReply(res.comment, parentId, username);
                        updateCounts(1);
                        $(`#reply-form-${commentId}`).addClass('hidden');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('Error posting reply');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Reply');
                }
            });
        });

        // ========== EDIT SAVE ==========
        $(document).on('click', '.save-edit', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const commentId = btn.data('comment-id');
            const input = $(`.edit-input[data-comment-id="${commentId}"]`);
            const newContent = input.val().trim();
            
            if (!newContent) return;

            btn.prop('disabled', true).text('...');

            $.ajax({
                url: `/comments/${commentId}`,
                method: 'PUT',
                data: {
                    content: newContent,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $(`#content-${commentId} .comment-text`).text(newContent);
                    $(`#edit-form-${commentId}`).addClass('hidden');
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('Error updating comment');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save');
                }
            });
        });

        // ========== HELPERS ==========
        function updateCounts(change) {
            const header = $('.comment-header');
            const match = header.text().match(/\d+/);
            if (match) {
                header.text(`Comments (${parseInt(match[0]) + change})`);
            }
            
            const mainCount = $(`.comment-toggle[data-post-id="${currentPostId}"] .text-xs`);
            if (mainCount.length) {
                const current = parseInt(mainCount.text()) || 0;
                mainCount.text(current + change);
            }
        }

        function addComment(c) {
            const avatar = c.user.avatar 
                ? `<img src="/storage/${c.user.avatar}" class="w-full h-full object-cover">`
                : `<div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">${c.user.name.charAt(0)}</div>`;
            
            const isOwner = c.user_id === currentUser.id;
            const ownerBtns = isOwner ? `
                <button class="edit-comment text-xs text-gray-500 hover:text-green-600" data-comment-id="${c.id}">Edit</button>
                <button class="delete-comment text-xs text-gray-500 hover:text-red-600" data-comment-id="${c.id}">Delete</button>
            ` : '';
            
            const html = `
                <div class="mb-3 comment-wrapper" id="comment-${c.id}">
                    <div class="flex items-start gap-2">
                        <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">${avatar}</div>
                        <div class="flex-1">
                            <div class="bg-gray-100 rounded-2xl px-3 py-2" id="content-${c.id}">
                                <span class="font-semibold text-sm">${c.user.name}</span>
                                <span class="text-sm ml-1 comment-text">${c.content}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 ml-2 text-xs">
                                <button class="like-comment text-gray-500 hover:text-blue-600" data-comment-id="${c.id}">Like</button>
                                <button class="reply-button text-gray-500 hover:text-blue-600" data-comment-id="${c.id}" data-username="${c.user.name}">Reply</button>
                                ${ownerBtns}
                                <span class="text-gray-400">just now</span>
                            </div>
                        </div>
                    </div>
                    <div class="edit-form hidden mt-2 ml-12" id="edit-form-${c.id}">
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden">${avatar}</div>
                            <div class="flex-1 relative">
                                <input type="text" class="edit-input w-full text-sm bg-gray-100 border rounded-full px-4 py-2 pr-24" value="${c.content}" data-comment-id="${c.id}">
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 flex gap-2">
                                    <button class="cancel-edit text-xs text-gray-500 px-2" data-comment-id="${c.id}">Cancel</button>
                                    <button class="save-edit text-xs bg-blue-500 text-white px-3 py-1 rounded-full" data-comment-id="${c.id}">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reply-form hidden mt-2 ml-12" id="reply-form-${c.id}">
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden">${avatar}</div>
                            <div class="flex-1 relative">
                                <input type="text" class="reply-input w-full text-sm bg-gray-100 border rounded-full px-4 py-2 pr-12" placeholder="Write a reply..." data-comment-id="${c.id}">
                                <button class="reply-submit absolute right-2 top-1/2 -translate-y-1/2 text-blue-500 text-sm px-3" data-comment-id="${c.id}" data-parent-id="${c.id}" data-username="${c.user.name}">Reply</button>
                            </div>
                        </div>
                    </div>
                    <div class="nested-replies mt-2" id="replies-${c.id}"></div>
                </div>
            `;
            
            $(`#comments-list-${currentPostId}`).find('.empty-state').remove();
            $(`#comments-list-${currentPostId}`).prepend(html);
        }

        function addReply(r, parentId, username) {
            const avatar = r.user.avatar 
                ? `<img src="/storage/${r.user.avatar}" class="w-full h-full object-cover">`
                : `<div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">${r.user.name.charAt(0)}</div>`;
            
            const isOwner = r.user_id === currentUser.id;
            const ownerBtns = isOwner ? `
                <button class="edit-comment text-xs text-gray-500 hover:text-green-600" data-comment-id="${r.id}">Edit</button>
                <button class="delete-comment text-xs text-gray-500 hover:text-red-600" data-comment-id="${r.id}">Delete</button>
            ` : '';
            
            const html = `
                <div class="mb-2" id="comment-${r.id}">
                    <div class="flex items-start gap-2 ml-12">
                        <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden">${avatar}</div>
                        <div class="flex-1">
                            <div class="bg-gray-100 rounded-2xl px-3 py-2">
                                <span class="font-semibold text-sm">${r.user.name}</span>
                                <span class="text-xs text-blue-600 mx-1">→ @${username}</span>
                                <span class="text-sm ml-1">${r.content}</span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 ml-2 text-xs">
                                <button class="like-comment text-gray-500 hover:text-blue-600" data-comment-id="${r.id}">Like</button>
                                <button class="reply-button text-gray-500 hover:text-blue-600" data-comment-id="${r.id}" data-username="${r.user.name}">Reply</button>
                                ${ownerBtns}
                                <span class="text-gray-400">just now</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            let container = $(`#replies-${parentId}`);
            if (!container.length) {
                $(`#comment-${parentId}`).append(`<div class="ml-12 mt-2" id="replies-${parentId}"></div>`);
                container = $(`#replies-${parentId}`);
            }
            container.append(html);
        }
    });
})();
</script>