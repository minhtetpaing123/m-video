{{-- File Path: resources/views/livewire/profile/profile-tabs.blade.php --}}
{{-- Purpose: Profile Tab မ်ားကို ပြသပေးပြီး နှိပ်လိုက်ပါက Active Tab Highlight ပြောင်းလဲပေးသော Blade View --}}

<div class="flex border-t border-gray-800 px-2 text-xs font-semibold text-gray-400">
    <button wire:click="selectTab('posts')" 
        class="py-3 px-4 transition {{ $activeTab === 'posts' ? 'text-blue-500 border-b-2 border-blue-500 font-bold' : 'hover:text-gray-200' }}">
        Posts
    </button>
    <button wire:click="selectTab('videos')" 
        class="py-3 px-4 transition {{ $activeTab === 'videos' ? 'text-blue-500 border-b-2 border-blue-500 font-bold' : 'hover:text-gray-200' }}">
        Videos
    </button>
    <button wire:click="selectTab('about')" 
        class="py-3 px-4 transition {{ $activeTab === 'about' ? 'text-blue-500 border-b-2 border-blue-500 font-bold' : 'hover:text-gray-200' }}">
        About
    </button>
    <button wire:click="selectTab('photos')" 
        class="py-3 px-4 transition {{ $activeTab === 'photos' ? 'text-blue-500 border-b-2 border-blue-500 font-bold' : 'hover:text-gray-200' }}">
        Photos
    </button>
</div>
