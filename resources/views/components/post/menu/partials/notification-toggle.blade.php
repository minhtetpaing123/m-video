@props(['post'])

{{-- Notification Toggle with Built-in JS --}}
<div class="px-2 py-1">
    <div class="w-full px-4 py-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition-all duration-200 cursor-pointer"
         id="notification-container-{{ $post->id }}">
        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
        <div class="flex-1">
            <div class="font-semibold text-gray-800">Notifications</div>
            <div class="text-xs text-gray-500">Manage post notifications</div>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" 
                   class="sr-only peer notification-checkbox-{{ $post->id }}" 
                   {{ $post->notification_enabled ?? true ? 'checked' : '' }}
                   data-post-id="{{ $post->id }}">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const postId = {{ $post->id }};
    const container = document.getElementById(`notification-container-${postId}`);
    const checkbox = document.querySelector(`.notification-checkbox-${postId}`);
    
    if (!container || !checkbox) return;
    
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.notification-toast');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `notification-toast fixed top-5 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-2xl z-[10005] text-white font-medium animate-slideDown ${
            type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-pink-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    
    container.addEventListener('click', function(event) {
        event.stopPropagation();
        
        const currentState = checkbox.checked;
        const newState = !currentState;
        
        checkbox.checked = newState;
        
        fetch(`/posts/${postId}/notifications`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ enabled: newState })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                checkbox.checked = currentState;
                showToast(data.message || 'Failed to update', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = currentState;
            showToast('Failed to update notifications', 'error');
        });
    });
    
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });
})();
</script>
@endpush