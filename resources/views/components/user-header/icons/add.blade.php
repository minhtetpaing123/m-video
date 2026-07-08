{{-- resources/views/components/user-header/icons/add.blade.php --}}
<div class="relative" id="add-container">
    {{-- Add Icon --}}
    <div id="add-icon" class="mv-icon-circle mv-add-icon-btn cursor-pointer">
        <svg viewBox="0 0 28 28" width="24" height="24" fill="#050505">
            <path d="M14 3.5c.69 0 1.25.56 1.25 1.25v8h8c.69 0 1.25.56 1.25 1.25s-.56 1.25-1.25 1.25h-8v8c0 .69-.56 1.25-1.25 1.25s-1.25-.56-1.25-1.25v-8h-8c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25h8v-8c0-.69.56-1.25 1.25-1.25z"/>
        </svg>
    </div>

    {{-- Dropdown Menu --}}
    <div id="add-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            📝 Post
        </a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            📸 Story
        </a>
    </div>
</div>

<style>
.hidden { display: none !important; }
</style>

<script>
(function() {
    const container = document.getElementById('add-container');
    const icon = document.getElementById('add-icon');
    const dropdown = document.getElementById('add-dropdown');
    
    if (!icon || !dropdown) return;
    
    icon.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
})();
</script>