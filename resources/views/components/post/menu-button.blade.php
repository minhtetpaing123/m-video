@props(['post'])

<div class="relative" id="menu-{{ $post->id }}">
    {{-- Three Dots Button --}}
    <button class="menu-btn text-gray-500 hover:bg-gray-100 rounded-full p-2 transition-colors"
            onclick="toggleMenu({{ $post->id }})">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div id="dropdown-{{ $post->id }}" class="dropdown-menu hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 py-2 text-sm max-h-[80vh] overflow-y-auto">
        
        @if(auth()->id() === $post->user_id)
            {{-- Owner Menu --}}
            <x-post.menu-owner :post="$post" />
        @else
            {{-- Other Users Menu --}}
            <x-post.menu-other :post="$post" />
        @endif
    </div>
</div>

<script>
// Global functions for menu functionality
function toggleMenu(postId) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('Toggle menu for post:', postId);
    
    // Hide all other menus
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu.id !== 'dropdown-' + postId) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle this menu
    const dropdown = document.getElementById('dropdown-' + postId);
    dropdown.classList.toggle('hidden');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.menu-btn') && !event.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// ========== OWNER FUNCTIONS ==========
function editPost(postId) {
    console.log('Edit post:', postId);
    // TODO: Implement edit functionality
    alert('Edit post feature - Open edit modal');
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

function deletePost(postId) {
    console.log('Delete post:', postId);
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        // TODO: Implement delete via AJAX
        fetch(`/posts/${postId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-post-id="${postId}"]`).remove();
                alert('Post deleted successfully');
            } else {
                alert('Failed to delete post');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete post');
        });
    }
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

// ========== OTHER USERS FUNCTIONS ==========
function savePost(postId) {
    console.log('Save post:', postId);
    // TODO: Implement save functionality
    alert('Post saved to your collection');
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

function hidePost(postId) {
    console.log('Hide post:', postId);
    // TODO: Implement hide functionality
    if (confirm('Hide this post?')) {
        document.querySelector(`[data-post-id="${postId}"]`).style.display = 'none';
    }
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

function reportPost(postId) {
    console.log('Report post:', postId);
    // TODO: Implement report functionality
    const reason = prompt('Please tell us why you are reporting this post:', 'Spam');
    if (reason) {
        alert('Thank you for your report. We will review this post.');
    }
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

function copyLink(postId) {
    const dummy = document.createElement('input');
    const url = window.location.href;
    dummy.value = url;
    document.body.appendChild(dummy);
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    
    alert('Link copied to clipboard!');
    document.getElementById('dropdown-' + postId).classList.add('hidden');
}

// Optional: Close dropdown on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
</script>