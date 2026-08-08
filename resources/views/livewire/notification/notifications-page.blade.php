<div x-data="{ 
        showClearConfirm: false, 
        showMarkAllConfirm: false, 
        showDeleteSelectedConfirm: false,
        selectMode: false 
     }" 
     class="min-h-screen bg-white dark:bg-[#18191a] text-gray-900 dark:text-gray-100 pb-28 pt-1 transition-colors duration-200">

    <div class="max-w-xl mx-auto px-2 sm:px-4">
        

        <!-- Fixed Header & Filter Bar Container -->
        <div class="sticky top-0 z-40 bg-white/95 dark:bg-[#18191a]/95 backdrop-blur-md pb-2 pt-1 -mx-2 px-2 sm:-mx-4 sm:px-4 border-b border-gray-100 dark:border-gray-800/60">
            
            <!-- Header Title & Settings Gear Icon -->
            <div class="flex items-center justify-between px-2 py-1.5">
                <h1 class="text-2xl font-bold text-black dark:text-white tracking-tight">Notifications</h1>
                
                <!-- ⚙️ Notification Preferences / Settings Link -->
                <a href="{{ route('settings.notifications') }}" 
                   wire:navigate 
                   class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#242526] rounded-full transition active:scale-95" 
                   title="Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>

            <!-- Filter & Action Buttons Bar -->
            <div class="flex items-center gap-2 px-2 pt-1 overflow-x-auto no-scrollbar">
                {{-- All Filter --}}
                <button wire:click="setFilter('all')" 
                        class="px-3.5 py-1.5 font-semibold text-xs rounded-full transition whitespace-nowrap active:scale-95 {{ $filter === 'all' ? 'bg-[#e7f3ff] dark:bg-[#263951] text-[#1877f2] dark:text-[#4599ff]' : 'bg-gray-100 dark:bg-[#242526] hover:bg-gray-200 dark:hover:bg-[#3a3b3c] text-gray-800 dark:text-gray-200' }}">
                    All
                </button>

                {{-- Unread Filter --}}
                <button wire:click="setFilter('unread')" 
                        class="px-3.5 py-1.5 font-semibold text-xs rounded-full transition whitespace-nowrap active:scale-95 {{ $filter === 'unread' ? 'bg-[#e7f3ff] dark:bg-[#263951] text-[#1877f2] dark:text-[#4599ff]' : 'bg-gray-100 dark:bg-[#242526] hover:bg-gray-200 dark:hover:bg-[#3a3b3c] text-gray-800 dark:text-gray-200' }}">
                    Unread
                </button>

                {{-- Mark all as read Button --}}
                @if(auth()->user()->unreadNotificationsCount() > 0)
                    <button @click="showMarkAllConfirm = true" 
                            type="button"
                            class="px-3.5 py-1.5 font-semibold text-xs text-[#1877f2] dark:text-[#4599ff] bg-[#e7f3ff]/60 dark:bg-[#263951]/60 hover:bg-[#e7f3ff] dark:hover:bg-[#263951] rounded-full transition whitespace-nowrap active:scale-95">
                        Mark all as read
                    </button>
                @endif

                {{-- Clear all Button --}}
                @if($notifications->total() > 0)
                    <button @click="showClearConfirm = true" 
                            type="button"
                            class="px-3.5 py-1.5 font-semibold text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-950/80 rounded-full transition whitespace-nowrap active:scale-95">
                        Clear all
                    </button>

                    {{-- 🟢 Select Mode Toggle Button (Select / Cancel) --}}
                    <button @click="selectMode = !selectMode; if(!selectMode) { $wire.set('selectedNotifications', []); $wire.set('selectAll', false); }" 
                            type="button"
                            class="px-3.5 py-1.5 font-semibold text-xs rounded-full transition whitespace-nowrap active:scale-95"
                            :class="selectMode ? 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-900' : 'bg-gray-100 dark:bg-[#242526] text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-[#3a3b3c]'">
                        <span x-text="selectMode ? 'Cancel' : 'Select'"></span>
                    </button>
                @endif

                {{-- 🟢 Selected လုပ်ထားချိန် ပေါ်လာမည့် Delete Selected Button --}}
                @if(count($selectedNotifications) > 0)
                    <button @click="showDeleteSelectedConfirm = true" 
                            type="button"
                            class="px-3.5 py-1.5 font-semibold text-xs text-white bg-red-600 hover:bg-red-700 rounded-full transition whitespace-nowrap active:scale-95 shadow-sm animate-bounce">
                        Delete ({{ count($selectedNotifications) }})
                    </button>
                @endif
            </div>

        </div>

        <!-- Section Title & Select All Checkbox -->
        <div class="px-2 py-2 mt-1 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-gray-200">
                {{ $filter === 'unread' ? 'Unread Notifications' : 'Earlier' }}
            </h2>

            {{-- 🟢 Select Mode ဖွင့်ထားမှသာ Select All Checkbox ကို ပြမည် --}}
            <template x-if="selectMode && {{ $notifications->total() }} > 0">
                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-400 select-none">
                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 dark:border-gray-700 text-[#1877f2] focus:ring-0 w-4 h-4">
                    <span>Select All</span>
                </label>
            </template>
        </div>

        <!-- Notification List Loop -->
        <div class="divide-y divide-gray-100 dark:divide-gray-800/40">
            @forelse($notifications as $notification)
                <div class="flex items-center gap-2 group">
                    {{-- 🟢 Select Mode ဖွင့်ထားမှသာ Checkbox ပေါ်မည် --}}
                    <div x-show="selectMode" x-transition class="pl-2">
                        <input type="checkbox" 
                               wire:model.live="selectedNotifications" 
                               value="{{ $notification->id }}" 
                               class="rounded border-gray-300 dark:border-gray-700 text-[#1877f2] focus:ring-0 w-4 h-4 cursor-pointer">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <livewire:notification.notification-item 
                            :notification="$notification" 
                            :key="$notification->id . '-' . $notification->is_read" 
                        />
                    </div>
                </div>
            @empty
                <div class="text-center py-20 px-4">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-[#242526] flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-xs">နိုတီဖီကေးရှင်း ဘာမှမရှိသေးပါ။</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasMorePages())
            <div class="p-3" x-intersect="$wire.loadMore()">
                <button wire:click="loadMore" 
                        class="w-full py-2 bg-[#e4e6eb] dark:bg-[#242526] hover:bg-[#d8dadf] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl transition text-center">
                    See previous notifications
                </button>
            </div>
        @endif

    </div>

    <!-- 🟢 Delete Selected Confirmation Modal -->
    <template x-teleport="body">
        <div x-show="showDeleteSelectedConfirm" 
             x-transition
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @click.self="showDeleteSelectedConfirm = false">
            <div class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-2xl p-5 text-center shadow-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-red-100 dark:bg-red-950/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Delete Selected Notifications?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">
                    ရွေးချယ်ထားသော နိုတီဖီကေးရှင်း ({{ count($selectedNotifications) }}) ခုကို ဖျက်ပစ်ရန် သေချာပါသလား။
                </p>
                <div class="flex items-center gap-2">
                    <button @click="showDeleteSelectedConfirm = false" type="button" class="flex-1 py-2 bg-gray-100 dark:bg-[#3a3b3c] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button wire:click="deleteSelected" @click="showDeleteSelectedConfirm = false; selectMode = false" type="button" class="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-md">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Mark All As Read Modal -->
    <template x-teleport="body">
        <div x-show="showMarkAllConfirm" 
             x-transition
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @click.self="showMarkAllConfirm = false">
            <div class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-2xl p-5 text-center shadow-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-blue-100 dark:bg-blue-950/50 flex items-center justify-center text-[#1877f2] dark:text-[#4599ff]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Mark All as Read?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">
                    မဖတ်ရသေးသော နိုတီဖီကေးရှင်း အားလုံးကို ဖတ်ပြီးသားအဖြစ် သတ်မှတ်ရန် သေချာပါသလား။
                </p>
                <div class="flex items-center gap-2">
                    <button @click="showMarkAllConfirm = false" type="button" class="flex-1 py-2 bg-gray-100 dark:bg-[#3a3b3c] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button wire:click="markAllAsRead" @click="showMarkAllConfirm = false" type="button" class="flex-1 py-2 bg-[#1877f2] hover:bg-[#166fe5] text-white font-semibold text-xs rounded-xl shadow-md">
                        Mark all as read
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Clear All Modal -->
    <template x-teleport="body">
        <div x-show="showClearConfirm" 
             x-transition
             class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
             style="display: none;"
             @click.self="showClearConfirm = false">
            <div class="w-full max-w-sm bg-white dark:bg-[#242526] rounded-2xl p-5 text-center shadow-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-red-100 dark:bg-red-950/50 flex items-center justify-center text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Clear All Notifications?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-4">
                    နိုတီဖီကေးရှင်း အားလုံးကို ဖျက်ပစ်ရန် သေချာပါသလား။ ပြန်လည်ရယူနိုင်မည် မဟုတ်ပါ။
                </p>
                <div class="flex items-center gap-2">
                    <button @click="showClearConfirm = false" type="button" class="flex-1 py-2 bg-gray-100 dark:bg-[#3a3b3c] text-gray-800 dark:text-gray-200 font-semibold text-xs rounded-xl">
                        Cancel
                    </button>
                    <button wire:click="clearAllNotifications" @click="showClearConfirm = false" type="button" class="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl shadow-md">
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </template>

    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-[#18191a] border-t border-gray-200 dark:border-gray-800">
        <livewire:layout.nav />
    </div>

    <!-- Echo Sound Controller Script -->
    <script>
    (function() {
        function triggerNotiAudio() {
            const audio = document.getElementById('notiPageSound') || document.getElementById('notificationSound');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(e => console.log('Autoplay block:', e));
            }
        }

        function handleEvent(e) {
            // Broadcast Event မှ Sound Play မလွှတ်ပါက သို့မဟုတ် User က Sound ပိတ်ထားပါက အသံ မမြည်စေရ
            const shouldPlaySound = e && typeof e.shouldPlaySound !== 'undefined' ? e.shouldPlaySound : true;
            
            if (shouldPlaySound) {
                triggerNotiAudio();
            }

            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('refreshNotifications');
            }
        }

        function initEchoListener() {
            const userId = "{{ auth()->id() }}";
            if (!userId || typeof Echo === 'undefined') return;

            Echo.private(`App.Models.User.${userId}`)
                .stopListening('.NotificationSent')
                .stopListening('NotificationSent')
                .listen('.NotificationSent', handleEvent)
                .listen('NotificationSent', handleEvent);
        }

        document.addEventListener('DOMContentLoaded', initEchoListener);
        document.addEventListener('livewire:navigated', initEchoListener);
        document.addEventListener('livewire:initialized', initEchoListener);
        initEchoListener();
    })();
    </script>
          <!-- 🔊 Noti Sound Component -->
    <livewire:notification.noti-sound />
</div>
