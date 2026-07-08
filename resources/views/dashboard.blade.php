<x-app-layout>
    <x-slot name="header">
        {{-- Empty --}}
    </x-slot>

    <x-user-header />
    <x-user.nav active="home" />
    
    <div style="background: #F0F2F5; min-height: 100vh; padding-bottom: 64px; padding-top: 12px;">
        <div style="max-width: 680px; margin: 0 auto;">
            
            {{-- JavaScript for Modal --}}
            <script>
                function openCreatePostModal() {
                    document.getElementById('createPostModal').style.display = 'flex';
                }
            </script>

            {{-- Create Post Box Component --}}
            <x-post.create-box onclick="openCreatePostModal()" />

            {{-- Create Post Modal Component --}}
            <x-post.create-modal :show="false" id="createPostModal" />

            {{-- Posts Feed with Real Data --}}
            <div id="posts-container" class="space-y-3">
                @foreach($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>

            {{-- Loading Indicator --}}
            <div id="loading-indicator" style="text-align: center; padding: 20px; display: none;">
                <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="color: #666; margin-top: 10px;">Loading more posts...</p>
            </div>

            {{-- End of Posts Message --}}
            <div id="end-message" style="text-align: center; padding: 20px; color: #666; display: none;">
                No more posts to load
            </div>

            {{-- Hidden data for infinite scroll --}}
            <div id="pagination-data" 
                 data-next-page="{{ $posts->nextPageUrl() }}"
                 data-last-page="{{ $posts->lastPage() }}"
                 data-current-page="{{ $posts->currentPage() }}"
                 style="display: none;">
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
    let loading = false;
    let endOfPosts = false;
    let nextPageUrl = document.getElementById('pagination-data')?.dataset.nextPage;
    let lastPage = document.getElementById('pagination-data')?.dataset.lastPage;
    let currentPage = document.getElementById('pagination-data')?.dataset.currentPage;

    // Function to load more posts
    function loadMorePosts() {
        if (loading || endOfPosts || !nextPageUrl || nextPageUrl === 'null') return;
        
        loading = true;
        document.getElementById('loading-indicator').style.display = 'block';
        
        fetch(nextPageUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Create temporary container
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            // Extract posts from response (they are inside posts-container)
            const newPosts = temp.querySelectorAll('#posts-container > div');
            const container = document.getElementById('posts-container');
            
            // Append new posts
            newPosts.forEach(post => {
                container.appendChild(post.cloneNode(true));
            });
            
            // Update pagination data from the response
            const newPaginationData = temp.querySelector('#pagination-data');
            if (newPaginationData) {
                nextPageUrl = newPaginationData.dataset.nextPage;
                lastPage = newPaginationData.dataset.lastPage;
                currentPage = newPaginationData.dataset.currentPage;
            }
            
            // Check if end of posts
            if (!nextPageUrl || nextPageUrl === 'null' || currentPage === lastPage) {
                endOfPosts = true;
                document.getElementById('end-message').style.display = 'block';
            }
            
            loading = false;
            document.getElementById('loading-indicator').style.display = 'none';
        })
        .catch(error => {
            console.error('Error loading more posts:', error);
            loading = false;
            document.getElementById('loading-indicator').style.display = 'none';
        });
    }

    // Infinite scroll with Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !endOfPosts && !loading) {
                loadMorePosts();
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '100px'
    });

    // Watch the loading indicator
    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator) {
        observer.observe(loadingIndicator);
    }

    // Also handle scroll event as fallback
    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            if (!endOfPosts && !loading && nextPageUrl && nextPageUrl !== 'null') {
                loadMorePosts();
            }
        }
    });

    // Open modal function
    function openCreatePostModal() {
        document.getElementById('createPostModal').style.display = 'flex';
    }
    </script>
    <script>
$(document).ready(function() {
    console.log('Like/Comment system initializing...');
    
    // Like button click handler
    $('.like-button').click(function(e) {
        e.preventDefault();
        const button = $(this);
        const postId = button.data('post-id');
        
        console.log('Liking post:', postId);
        
        $.ajax({
            url: `/posts/${postId}/react`,
            method: 'POST',
            data: { type: 'like' },
            success: function(response) {
                console.log('Like success:', response);
                if (response.success) {
                    // Update like count
                    $('.likes-count[data-post-id="'+postId+'"]').text(response.likes_count);
                }
            },
            error: function(xhr) {
                console.error('Like error:', xhr);
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
            }
        });
    });
    
    // Comment button click handler (to focus on input)
    $('.comment-button').click(function(e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        $(`input[name="content"][data-post-id="${postId}"]`).focus();
    });
    
    // Comment form submit handler
    $('.comment-form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const postId = form.data('post-id');
        const input = form.find('input[name="content"]');
        const content = input.val().trim();
        
        if (!content) {
            alert('Please write a comment');
            return;
        }
        
        console.log('Submitting comment for post:', postId, 'Content:', content);
        
        // Disable button while submitting
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('...');
        
        $.ajax({
            url: `/posts/${postId}/comments`,
            method: 'POST',
            data: { content: content },
            success: function(response) {
                console.log('Comment success:', response);
                if (response.success) {
                    // Clear input
                    input.val('');
                    
                    // Add comment to list
                    const commentsList = $(`#comments-${postId}`);
                    if (commentsList.length) {
                        const newComment = `
                            <div class="comment-item flex items-start space-x-2 mt-2">
                                <div class="w-6 h-6 rounded-full bg-gray-300 flex-shrink-0"></div>
                                <div class="flex-1">
                                    <div class="bg-gray-100 rounded-2xl px-3 py-1 inline-block">
                                        <span class="font-semibold text-xs">${response.comment.user.name}</span>
                                        <span class="text-sm ml-1">${response.comment.content}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        commentsList.prepend(newComment);
                    }
                    
                    // Update comment count
                    $(`.comments-count[data-post-id="${postId}"]`).text(response.comments_count + ' comments');
                }
            },
            error: function(xhr) {
                console.error('Comment error:', xhr);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to add comment'));
            },
            complete: function() {
                // Re-enable button
                submitBtn.prop('disabled', false).html('Post');
            }
        });
    });
});
</script>
</x-app-layout>