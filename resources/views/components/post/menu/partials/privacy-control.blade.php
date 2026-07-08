@props(['post'])

{{-- Privacy Control --}}
<div class="px-2 py-1">
    <div class="px-4 py-2">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500">PRIVACY</span>
            <span class="privacy-badge text-xs bg-gray-100 px-2 py-1 rounded-full capitalize" id="privacy-badge-{{ $post->id }}">{{ $post->privacy ?? 'public' }}</span>
        </div>
        <div class="flex gap-2">
            <button onclick="changePrivacy({{ $post->id }}, 'public')" 
                    class="privacy-btn-{{ $post->id }} flex-1 py-2 px-3 rounded-lg {{ $post->privacy == 'public' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} text-xs font-medium hover:opacity-90 transition"
                    data-privacy="public">
                🌍 Public
            </button>
            <button onclick="changePrivacy({{ $post->id }}, 'friends')" 
                    class="privacy-btn-{{ $post->id }} flex-1 py-2 px-3 rounded-lg {{ $post->privacy == 'friends' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} text-xs font-medium hover:opacity-90 transition"
                    data-privacy="friends">
                👥 Friends
            </button>
            <button onclick="changePrivacy({{ $post->id }}, 'private')" 
                    class="privacy-btn-{{ $post->id }} flex-1 py-2 px-3 rounded-lg {{ $post->privacy == 'private' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} text-xs font-medium hover:opacity-90 transition"
                    data-privacy="private">
                🔒 Private
            </button>
        </div>
    </div>
</div>