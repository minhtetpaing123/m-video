@props(['post'])

{{-- Post Stats Card --}}
<div class="px-4 py-3 bg-gray-50/50">
    <div class="grid grid-cols-3 gap-2 text-center">
        <div class="bg-white rounded-lg p-2 shadow-sm">
            <div class="font-bold text-blue-600" id="likes-count-{{ $post->id }}">{{ $post->likes_count ?? 0 }}</div>
            <div class="text-xs text-gray-500">Likes</div>
        </div>
        <div class="bg-white rounded-lg p-2 shadow-sm">
            <div class="font-bold text-green-600" id="comments-count-{{ $post->id }}">{{ $post->comments_count ?? 0 }}</div>
            <div class="text-xs text-gray-500">Comments</div>
        </div>
        <div class="bg-white rounded-lg p-2 shadow-sm">
            <div class="font-bold text-purple-600" id="shares-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</div>
            <div class="text-xs text-gray-500">Shares</div>
        </div>
    </div>
</div>