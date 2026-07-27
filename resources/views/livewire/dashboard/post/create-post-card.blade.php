<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 mb-4 border border-gray-200/60 dark:border-gray-700/50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
            {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
        </div>
        <button wire:click="$dispatch('open-create-post-modal')"
                class="flex-1 bg-gray-100 dark:bg-gray-700/60 hover:bg-gray-200/80 dark:hover:bg-gray-750 rounded-full px-5 py-2.5 text-gray-500 dark:text-gray-400 text-sm cursor-pointer transition-all duration-200 font-medium text-left">
            What's on your mind, {{ auth()->user() ? auth()->user()->name : 'User' }}?
        </button>
    </div>
</div>
