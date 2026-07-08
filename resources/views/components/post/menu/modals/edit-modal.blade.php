@props(['post'])

{{-- Edit Modal --}}
<div id="edit-modal-{{ $post->id }}" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[10002] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full animate-slideUp">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Edit Post</h3>
                <button onclick="closeEditModal({{ $post->id }})" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <textarea id="edit-content-{{ $post->id }}" 
                      class="w-full border border-gray-200 rounded-xl p-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-h-[150px]"
                      placeholder="What's on your mind?">{{ $post->content }}</textarea>
            
            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeEditModal({{ $post->id }})" 
                        class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium transition">
                    Cancel
                </button>
                <button onclick="saveEdit({{ $post->id }})" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium hover:opacity-90 transition shadow-lg">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>