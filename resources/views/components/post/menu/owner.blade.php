@props(['post'])

{{-- Modern Owner Menu with Real Data --}}
<div class="divide-y divide-gray-100">
    {{-- Header with gradient --}}
    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-900 text-sm">Post Owner Controls</div>
                <div class="text-xs text-gray-500">Manage your post</div>
            </div>
        </div>
    </div>

    {{-- Stats Card --}}
    <x-post.menu.partials.stats-card :post="$post" />

    {{-- Edit Post --}}
    <div class="px-2 py-1">
        <div onclick="openEditModal({{ $post->id }})" 
             class="w-full px-4 py-3 hover:bg-blue-50 rounded-lg flex items-center gap-3 transition-all duration-200 group cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="font-semibold text-gray-800">Edit Post</div>
                <div class="text-xs text-gray-500">Modify your post content</div>
            </div>
            <span class="text-gray-400 group-hover:text-blue-500">✏️</span>
        </div>
    </div>

    {{-- Delete Post --}}
    <div class="px-2 py-1">
        <div onclick="openDeleteModal({{ $post->id }})" 
             class="w-full px-4 py-3 hover:bg-red-50 rounded-lg flex items-center gap-3 transition-all duration-200 group cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="font-semibold text-red-600">Delete Post</div>
                <div class="text-xs text-gray-500">Permanently remove this post</div>
            </div>
            <span class="text-gray-400 group-hover:text-red-500">🗑️</span>
        </div>
    </div>

    {{-- Privacy Control --}}
    <x-post.menu.partials.privacy-control :post="$post" />

    {{-- Pin Toggle --}}
    <x-post.menu.partials.pin-toggle :post="$post" />

    {{-- View Insights --}}
    <div class="px-2 py-1">
        <div onclick="viewInsights({{ $post->id }})" 
             class="w-full px-4 py-3 hover:bg-purple-50 rounded-lg flex items-center gap-3 transition-all duration-200 cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="font-semibold text-gray-800">View Insights</div>
                <div class="text-xs text-gray-500">See how your post is performing</div>
            </div>
            <span class="text-gray-400">📊</span>
        </div>
    </div>

    {{-- Notification Toggle --}}
    <x-post.menu.partials.notification-toggle :post="$post" />
</div>

{{-- Modals --}}
<x-post.menu.modals.edit-modal :post="$post" />

{{-- Delete Modal will be created dynamically via JavaScript --}}

<script>
// ==================== EDIT FUNCTIONS ====================
function openEditModal(postId) {
    event?.stopPropagation();
    const modal = document.getElementById(`edit-modal-${postId}`);
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
}

function closeEditModal(postId) {
    const modal = document.getElementById(`edit-modal-${postId}`);
    if (modal) {
        modal.classList.add('hidden');
    }
}

function saveEdit(postId) {
    const newContent = document.getElementById(`edit-content-${postId}`).value.trim();
    
    if (!newContent) {
        showToast('Please enter some content', 'error');
        return;
    }
    
    fetch(`/posts/${postId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ content: newContent })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal(postId);
            showToast('Post updated!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Failed to update post', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to update post', 'error');
    });
}

// ==================== DELETE FUNCTIONS ====================
function openDeleteModal(postId) {
    event?.stopPropagation();
    
    const existingModal = document.getElementById(`delete-modal-${postId}`);
    if (existingModal) existingModal.remove();
    
    const modal = document.createElement('div');
    modal.id = `delete-modal-${postId}`;
    modal.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[10003] flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full animate-slideUp p-6">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Delete Post?</h3>
            <p class="text-gray-500 text-center mb-6">This action cannot be undone. This post will be permanently deleted.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal('${postId}')" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button onclick="confirmDelete(${postId})" class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium hover:opacity-90 transition shadow-lg">
                    Delete
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function closeDeleteModal(postId) {
    const modal = document.getElementById(`delete-modal-${postId}`);
    if (modal) modal.remove();
}

function confirmDelete(postId) {
    fetch(`/posts/${postId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteModal(postId);
            showToast('Post deleted!', 'success');
            const postElement = document.querySelector(`#post-${postId}, [data-post-id="${postId}"]`);
            if (postElement) postElement.remove();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Failed to delete post', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete post', 'error');
    });
}

// ==================== PIN FUNCTION ====================
function togglePin(postId) {
    const checkbox = document.querySelector(`.pin-checkbox-${postId}`);
    const currentState = checkbox.checked;
    checkbox.checked = !currentState;
    
    fetch(`/posts/${postId}/pin`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.pinned ? 'Post pinned' : 'Post unpinned', 'success');
        } else {
            checkbox.checked = currentState;
            showToast('Failed to update pin', 'error');
        }
    })
    .catch(error => {
        checkbox.checked = currentState;
        showToast('Failed to update pin', 'error');
    });
}

// ==================== PRIVACY FUNCTION ====================
function changePrivacy(postId, privacy) {
    fetch(`/posts/${postId}/privacy`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ privacy: privacy })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`privacy-badge-${postId}`).textContent = privacy;
            document.querySelectorAll(`.privacy-btn-${postId}`).forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            const selectedBtn = Array.from(document.querySelectorAll(`.privacy-btn-${postId}`))
                .find(btn => btn.dataset.privacy === privacy);
            if (selectedBtn) {
                selectedBtn.classList.remove('bg-gray-100', 'text-gray-700');
                selectedBtn.classList.add('bg-blue-500', 'text-white');
            }
            showToast(`Privacy changed to ${privacy}`, 'success');
        } else {
            showToast('Failed to change privacy', 'error');
        }
    })
    .catch(error => showToast('Failed to change privacy', 'error'));
}

// ==================== VIEW INSIGHTS ====================
function viewInsights(postId) {
    window.location.href = `/posts/${postId}/insights`;
}

// ==================== NOTIFICATIONS ====================
function toggleNotifications(postId) {
    const checkbox = event.currentTarget.querySelector('input[type="checkbox"]');
    const currentState = checkbox.checked;
    
    fetch(`/posts/${postId}/notifications`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            checkbox.checked = data.enabled;
            showToast(data.enabled ? 'Notifications on' : 'Notifications off', 'success');
        } else {
            checkbox.checked = currentState;
            showToast('Failed to update notifications', 'error');
        }
    })
    .catch(error => {
        checkbox.checked = currentState;
        showToast('Failed to update notifications', 'error');
    });
}

// ==================== TOAST FUNCTION ====================
function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-5 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-2xl z-[10005] text-white font-medium animate-slideDown ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-pink-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ==================== STYLES ====================
if (!document.querySelector('#live-update-styles')) {
    const style = document.createElement('style');
    style.id = 'live-update-styles';
    style.textContent = `
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translate(-50%, -20px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
        .animate-slideUp { animation: slideUp 0.3s ease-out; }
        .animate-slideDown { animation: slideDown 0.3s ease-out; }
    `;
    document.head.appendChild(style);
}
</script>