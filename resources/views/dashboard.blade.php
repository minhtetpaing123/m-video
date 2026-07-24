{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        {{-- Empty --}}
    </x-slot>

    {{-- ============================================ --}}
    {{-- GLOBAL UPLOAD PROGRESS BAR (Facebook Style) --}}
    {{-- ============================================ --}}
    <div id="globalProgressContainer" style="display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; background: #18191a; padding: 12px 20px; border-bottom: 2px solid #2d88ff; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="color: #e4e6eb; font-size: 14px; font-weight: 500;">
                    <span id="globalProgressText">📤 Uploading video...</span>
                </span>
                <span style="color: #b0b3b8; font-size: 14px; font-weight: 600;">
                    <span id="globalProgressPercent">0%</span>
                </span>
            </div>
            <div style="width: 100%; height: 6px; background: #3e4042; border-radius: 4px; overflow: hidden;">
                <div id="globalProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #2d88ff, #1a7ae6); border-radius: 4px; transition: width 0.3s ease;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                <span style="color: #5a5d61; font-size: 11px;" id="globalProgressSpeed"></span>
                <span style="color: #5a5d61; font-size: 11px;" id="globalProgressSize"></span>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- CREATE POST BUTTON & MODAL --}}
    {{-- ============================================ --}}
    <div class="max-w-4xl mx-auto px-4 py-6">
        
        {{-- Create Post Button --}}
        <div class="bg-[#242526] rounded-xl p-4 mb-6 shadow-lg">
            <button wire:click="$dispatch('open-create-post-modal')" 
                    class="w-full text-left flex items-center gap-3 p-3 bg-[#3e4042] rounded-full hover:bg-[#4e5052] transition-all duration-200">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#667eea] to-[#764ba2] flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                    {{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                </div>
                <span class="text-[#b0b3b8] text-sm">What's on your mind?</span>
            </button>
        </div>
        
        {{-- ✅ Create Post Component (Modal & Progress Bar) --}}
        <livewire:create-post />
        
        {{-- Dashboard Posts List --}}
        <livewire:dashboard.index />
        
    </div>

    {{-- ============================================ --}}
    {{-- JAVASCRIPT FOR GLOBAL PROGRESS BAR --}}
    {{-- ============================================ --}}
    <script>
        // Global functions for progress bar control
        window.showGlobalProgress = function(text, percent) {
            const container = document.getElementById('globalProgressContainer');
            const progressText = document.getElementById('globalProgressText');
            const progressPercent = document.getElementById('globalProgressPercent');
            const progressBar = document.getElementById('globalProgressBar');
            
            if (container) container.style.display = 'block';
            if (progressText) progressText.textContent = text || 'Uploading...';
            if (progressPercent) progressPercent.textContent = (percent || 0) + '%';
            if (progressBar) progressBar.style.width = (percent || 0) + '%';
        };

        window.updateGlobalProgress = function(text, percent) {
            const progressText = document.getElementById('globalProgressText');
            const progressPercent = document.getElementById('globalProgressPercent');
            const progressBar = document.getElementById('globalProgressBar');
            
            if (progressText) progressText.textContent = text || 'Uploading...';
            if (progressPercent) progressPercent.textContent = (percent || 0) + '%';
            if (progressBar) progressBar.style.width = (percent || 0) + '%';
            
            // Speed & Size update (optional)
            const speedEl = document.getElementById('globalProgressSpeed');
            const sizeEl = document.getElementById('globalProgressSize');
            if (speedEl) {
                const speed = Math.floor(Math.random() * 5 + 1);
                speedEl.textContent = speed + ' MB/s';
            }
            if (sizeEl) {
                const size = Math.floor(percent / 10 * 5 + 10);
                sizeEl.textContent = size + ' MB / ' + (size + 50) + ' MB';
            }
        };

        window.hideGlobalProgress = function() {
            const container = document.getElementById('globalProgressContainer');
            if (container) {
                container.style.display = 'none';
            }
            // Reset
            const progressBar = document.getElementById('globalProgressBar');
            const progressPercent = document.getElementById('globalProgressPercent');
            if (progressBar) progressBar.style.width = '0%';
            if (progressPercent) progressPercent.textContent = '0%';
        };

        // Listen for Livewire events
        document.addEventListener('livewire:update', function() {
            // Auto update from Livewire component
            const progressBar = document.getElementById('globalProgressBar');
            if (progressBar) {
                // Check if there's any Livewire progress data
                const livewireData = document.querySelector('[wire\\:id]');
                if (livewireData) {
                    // You can add custom logic here if needed
                }
            }
        });

        // Listen for custom events from CreatePost
        document.addEventListener('show-progress', function(event) {
            const { text, percent } = event.detail;
            window.showGlobalProgress(text, percent);
        });

        document.addEventListener('update-progress', function(event) {
            const { text, percent } = event.detail;
            window.updateGlobalProgress(text, percent);
        });

        document.addEventListener('hide-progress', function() {
            window.hideGlobalProgress();
        });

        console.log('✅ Global Progress Bar initialized');
    </script>
</x-app-layout>