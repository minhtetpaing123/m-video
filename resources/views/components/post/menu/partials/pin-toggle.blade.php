@props(['post'])

{{-- Pin Post Toggle --}}
<div class="px-2 py-1">
    <div class="w-full px-4 py-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition-all duration-200 cursor-pointer"
         onclick="togglePin({{ $post->id }})">
        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
        </div>
        <div class="flex-1">
            <div class="font-semibold text-gray-800">Pin to Profile</div>
            <div class="text-xs text-gray-500">Feature this post on your profile</div>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer pin-checkbox-{{ $post->id }}" {{ $post->is_pinned ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
        </label>
    </div>
</div>