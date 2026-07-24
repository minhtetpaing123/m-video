@props([
    'message' => null,
    'type' => 'success',
    'undo' => false,
    'postId' => null,
    'postTitle' => null
])

@if($message)
    <div x-data="{
        show: true,
        progress: 100,
        timer: null,
        interval: null,
        postId: '{{ $postId }}',
        
        init() {
            this.startProgress();
            
            this.timer = setTimeout(() => {
                if (this.show) {
                    this.close();
                    if (this.postId && {{ $undo ? 'true' : 'false' }}) {
                        Livewire.dispatch('force-delete', { data: { postId: this.postId } });
                    }
                }
            }, 5000);
        },
        
        startProgress() {
            this.progress = 100;
            this.interval = setInterval(() => {
                if (this.progress > 0) {
                    this.progress -= 0.5;
                } else {
                    this.clearTimers();
                }
            }, 25);
        },
        
        clearTimers() {
            if (this.interval) clearInterval(this.interval);
            if (this.timer) clearTimeout(this.timer);
        },
        
        close() {
            this.show = false;
            this.clearTimers();
            Livewire.dispatch('clear-notification');
        },
        
        undoDelete() {
            if (!this.postId) return;
            // ⚡ ပိုမိုသေချာစေရန် object format ဖြင့် လှမ်းပို့ပေးခြင်း
            Livewire.dispatch('undo-delete', { data: { postId: this.postId } });
            this.close();
        }
    }" 
         x-show="show"
         x-init="init()"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-[9999] max-w-sm w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <div class="flex items-center p-4">
            {{-- Icon --}}
            <div class="flex-shrink-0">
                @if($type === 'success')
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                @elseif($type === 'error')
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Message --}}
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $message }}
                    @if($undo && $postTitle)
                        <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">
                            "{{ Str::limit($postTitle, 25) }}"
                        </span>
                    @endif
                </p>
            </div>

            {{-- ⚡ Undo Button --}}
            @if($undo)
                <button @click="undoDelete()" 
                        type="button"
                        class="ml-2 flex-shrink-0 px-3 py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition">
                    Undo
                </button>
            @endif

            {{-- Close Button --}}
            <button @click="close()" type="button" class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Progress Bar --}}
        <div class="h-1 bg-gray-200 dark:bg-gray-700">
            <div x-bind:style="`width: ${progress}%`"
                 class="h-full transition-all duration-100 ease-linear {{ $type === 'success' ? 'bg-green-500' : ($type === 'error' ? 'bg-red-500' : 'bg-blue-500') }}">
            </div>
        </div>
    </div>
@endif
