<div class="fixed bottom-24 right-4 z-50 flex flex-col items-end gap-2">
    {{-- Toggle Button (ဒီခလုတ်ကို နှိပ်မှသာ ပွင့်မည်) --}}
    <button wire:click="toggleFilter" 
            type="button"
            class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center hover:scale-110">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
        </svg>
    </button>

    {{-- Category List Menu (Server side တွင် showFilter = false ဖြစ်နေ၍ Page reload လုပ်ချိန် HTML လုံးဝ ပါမလာပါ) --}}
    @if($showFilter)
        <div class="flex flex-col gap-2 max-h-[400px] overflow-y-auto pr-2 w-48"
             style="scrollbar-width: thin; scrollbar-color: #3e4042 transparent;">
            
            <a href="{{ route('dashboard') }}" 
               class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-full shadow-lg shadow-black/30 transition-all duration-300 hover:scale-105 hover:shadow-blue-500/20 border border-gray-700 text-center">
                🏠 All
            </a>

            @foreach($categories as $key => $label)
                <button wire:click="filterCategory('{{ $key }}')" 
                        type="button"
                        class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-full shadow-lg shadow-black/30 transition-all duration-300 hover:scale-105 hover:shadow-blue-500/20 border border-gray-700 text-center width-full">
                    {{ $label }}
                </button>
            @endforeach

            <a href="{{ route('category.18plus') }}" 
               class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-full shadow-lg shadow-red-500/30 transition-all duration-300 hover:scale-105 hover:shadow-red-500/50 border border-red-500/30 text-center">
                🔞 18+
            </a>
        </div>
    @endif
</div>
