<div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm space-y-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-4">
        Notification Preferences
    </h2>

    <!-- Section 1: Notification Channels -->
    <div class="space-y-4">
        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Delivery Channels</h3>
        
        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Notification Sounds</p>
                <p class="text-sm text-gray-500">Play sound when a new alert arrives.</p>
            </div>
            <!-- Livewire v4 Syntax: wire:model.live -->
            <input type="checkbox" wire:model.live="notify_sound" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>

        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Email Notifications</p>
                <p class="text-sm text-gray-500">Receive email digests for important activities.</p>
            </div>
            <input type="checkbox" wire:model.live="notify_email" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>
    </div>

    <!-- Section 2: Social Activity Controls -->
    <div class="space-y-4">
        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Social Activities</h3>

        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Comments & Replies</p>
                <p class="text-sm text-gray-500">Alerts when someone comments or replies to your post.</p>
            </div>
            <input type="checkbox" wire:model.live="notify_replies" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>

        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Likes & Reactions</p>
                <p class="text-sm text-gray-500">Alerts when someone reacts to your content.</p>
            </div>
            <input type="checkbox" wire:model.live="notify_likes" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>

        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Followers & Friend Requests</p>
                <p class="text-sm text-gray-500">Alerts for new requests and new followers.</p>
            </div>
            <input type="checkbox" wire:model.live="notify_follows" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>
    </div>

    <!-- Section 3: Quiet Hours (Do Not Disturb) -->
    <div class="space-y-4">
        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-xs">Quiet Hours (DND)</h3>

        <div class="flex items-center justify-between py-2">
            <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Enable Quiet Hours</p>
                <p class="text-sm text-gray-500">Mute sounds during specific hours.</p>
            </div>
            <input type="checkbox" wire:model.live="quiet_hours_enabled" class="w-5 h-5 accent-blue-600 rounded cursor-pointer">
        </div>

        @if($quiet_hours_enabled)
            <div class="flex items-center space-x-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300">From</label>
                    <input type="time" wire:model.live="quiet_hours_start" class="mt-1 p-2 rounded border dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300">To</label>
                    <input type="time" wire:model.live="quiet_hours_end" class="mt-1 p-2 rounded border dark:bg-gray-800 dark:text-white">
                </div>
            </div>
        @endif
    </div>
</div>
