@props(['post'])

{{-- Other Users Menu Items --}}
<div class="px-4 py-2 bg-gray-50 text-gray-600 font-semibold text-xs border-b border-gray-100">
    Why you're seeing this
</div>

{{-- Why seeing this post --}}
<div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
    <div class="font-semibold text-gray-900 text-sm">Why am I seeing this post?</div>
    <div class="text-xs text-gray-500 mt-1">This post is suggested based on your activity.</div>
</div>

{{-- Interested --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-green-600 text-lg">👍</span>
    <div>
        <div class="font-medium text-gray-800">Interested</div>
        <div class="text-xs text-gray-500">More suggested posts like this</div>
    </div>
</div>

{{-- Not interested --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-red-600 text-lg">👎</span>
    <div>
        <div class="font-medium text-gray-800">Not interested</div>
        <div class="text-xs text-gray-500">Fewer posts like this</div>
    </div>
</div>

<div class="border-t border-gray-100 my-1"></div>

{{-- Save post --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors" onclick="savePost({{ $post->id }})">
    <span class="text-gray-600 text-lg">🔖</span>
    <div>
        <div class="font-medium text-gray-800">Save post</div>
        <div class="text-xs text-gray-500">Add to your saved items</div>
    </div>
</div>

{{-- Hide post --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors" onclick="hidePost({{ $post->id }})">
    <span class="text-gray-600 text-lg">🙈</span>
    <div>
        <div class="font-medium text-gray-800">Hide post</div>
        <div class="text-xs text-gray-500">See fewer posts like this</div>
    </div>
</div>

{{-- Report post --}}
<div class="px-4 py-3 hover:bg-red-50 cursor-pointer flex items-center gap-3 transition-colors" onclick="reportPost({{ $post->id }})">
    <span class="text-red-600 text-lg">🚩</span>
    <div>
        <div class="font-medium text-gray-800">Report post</div>
        <div class="text-xs text-gray-500">We'll keep your report anonymous</div>
    </div>
</div>

<div class="border-t border-gray-100 my-1"></div>

{{-- Turn on notifications --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-gray-600 text-lg">🔔</span>
    <div>
        <div class="font-medium text-gray-800">Turn on notifications</div>
        <div class="text-xs text-gray-500">Get notified about this post</div>
    </div>
</div>

{{-- Copy link --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors" onclick="copyLink({{ $post->id }})">
    <span class="text-gray-600 text-lg">🔗</span>
    <div>
        <div class="font-medium text-gray-800">Copy link</div>
    </div>
</div>

<div class="border-t border-gray-100 my-1"></div>

{{-- Snooze user --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-gray-600 text-lg">😴</span>
    <div>
        <div class="font-medium text-gray-800">Snooze {{ $post->user->name }}</div>
        <div class="text-xs text-gray-500">Temporarily stop seeing posts for 30 days</div>
    </div>
</div>

{{-- Hide all from user --}}
<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-gray-600 text-lg">🚫</span>
    <div>
        <div class="font-medium text-gray-800">Hide all from {{ $post->user->name }}</div>
        <div class="text-xs text-gray-500">Stop seeing posts from this person</div>
    </div>
</div>

{{-- Block user --}}
<div class="px-4 py-3 hover:bg-red-50 cursor-pointer flex items-center gap-3 transition-colors">
    <span class="text-red-600 text-lg">⛔</span>
    <div>
        <div class="font-medium text-red-600">Block {{ $post->user->name }}</div>
        <div class="text-xs text-gray-500">You won't be able to see or contact each other</div>
    </div>
</div>