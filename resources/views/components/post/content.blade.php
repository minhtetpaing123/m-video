<!-- resources/views/components/post/content.blade.php -->
@props([
    'content',
    'seeMore' => false,
])

@if($content)
    <div class="px-3 pb-1 text-gray-800 text-sm">
        <p class="whitespace-pre-line">
            {{ $content }}
            @if($seeMore)
                <span class="text-gray-500">... See more</span>
            @endif
        </p>
    </div>
@endif