<div class="flex justify-center my-2" wire:key="call-log-{{ $callLog->id }}">
    <div class="bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-xs px-3.5 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
        <span>Voice Call Ended • {{ $callLog->duration ?? '00:00' }}</span>
    </div>
</div>
