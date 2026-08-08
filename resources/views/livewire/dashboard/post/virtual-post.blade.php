@props(['postId'])

<div 
    x-data="{ 
        isVisible: true,
        height: 0
    }"
    x-init="
        height = $el.offsetHeight || 500;
    "
    x-intersect:enter.margin.600px="isVisible = true"
    x-intersect:leave.margin.600px="
        height = $el.offsetHeight || height; 
        isVisible = false;
    "
    :style="!isVisible ? `min-height: ${height}px` : ''"
    wire:key="virtual-post-{{ $postId }}"
    id="post-{{ $postId }}"
>
    {{-- စခရင်ပေါ်တွင် မြင်ရချိန်မှသာ HTML Render လုပ်မည် (Facebook Virtual DOM Technique) --}}
    <template x-if="isVisible">
        <div>
            {{ $slot }}
        </div>
    </template>
</div>
