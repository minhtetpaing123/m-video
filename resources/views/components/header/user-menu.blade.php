<!-- resources/views/components/header/user-menu.blade.php -->
<div class="flex items-center gap-2">
    {{-- Theme Toggle Button --}}
    <button onclick="toggleTheme()" 
            class="relative w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all duration-300 transform hover:scale-110 active:scale-95">
        <span id="themeIcon" class="text-lg transition-transform duration-500 hover:rotate-180">🌙</span>
    </button>

    {{-- Mobile Search Toggle --}}
    <button id="searchToggleMobile" 
            class="md:hidden w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all duration-300 hover:scale-110 active:scale-95">
        <x-icons.search-icon class="w-5 h-5 transition-transform duration-300 hover:scale-110" />
    </button>

    {{-- Create Post Button (Auth Only) --}}
    @auth
        <button onclick="openCreatePostModal()" 
                class="relative w-9 h-9 rounded-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
        </button>
    @endauth

    {{-- User Menu --}}
    <div class="relative" id="userMenuContainer">
        <button id="userMenuButton" 
                onclick="toggleUserDropdown()"
                class="relative w-9 h-9 rounded-full flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-500 text-white font-semibold text-sm hover:ring-2 hover:ring-purple-400/50 transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50">
            @auth
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            @else
                <x-icons.user-icon class="w-5 h-5 transition-transform duration-300 hover:scale-110" />
            @endauth
            
            @auth
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-gray-900 animate-pulse"></span>
            @endauth
        </button>

        {{-- Dropdown Menu --}}
        <div id="userDropdown" 
             class="absolute right-0 mt-2 w-56 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-2 z-50 hidden origin-top-right">

            @auth
                {{-- Auth User Menu --}}
                <div class="px-4 py-3 border-b border-gray-800">
                    <p class="text-white font-medium">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-gray-400 text-sm">{{ auth()->user()->email ?? '' }}</p>
                </div>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200 hover:pl-6 group">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Dashboard
                    <span class="ml-auto text-xs text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">→</span>
                </a>
                
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-gray-300 hover:text-white hover:bg-gray-800/50 transition-all duration-200 hover:pl-6 group">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    Profile
                    <span class="ml-auto text-xs text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">→</span>
                </a>
                
                <div class="border-t border-gray-800 my-1"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all duration-200 hover:pl-6 group">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 group-hover:translate-x-1" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        Logout
                        <span class="ml-auto text-xs text-red-600/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">→</span>
                    </button>
                </form>
            @else
                {{-- Guest User Menu --}}
                <div class="px-4 py-3">
                    <p class="text-gray-400 text-sm text-center">Welcome to M-VIDEO</p>
                </div>
                
                <div class="px-4 py-2 space-y-2">
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-all duration-300 hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" 
                       class="block w-full text-center bg-gray-800 hover:bg-gray-700 text-white font-medium py-2.5 rounded-lg transition-all duration-300 hover:scale-[1.02] active:scale-95">
                        Create Account
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

<style>
/* ============================================ */
/* ANIMATIONS */
/* ============================================ */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}

#userDropdown.open {
    display: block !important;
    animation: slideDown 0.2s ease-out forwards;
}

/* Responsive */
@media (max-width: 640px) {
    .w-9 {
        width: 36px;
        height: 36px;
    }
}
</style>

<script>
// ============================================
// USER DROPDOWN TOGGLE
// ============================================
let isDropdownOpen = false;

function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    if (!dropdown) return;
    
    isDropdownOpen = !isDropdownOpen;
    
    if (isDropdownOpen) {
        dropdown.classList.add('open');
        dropdown.style.display = 'block';
    } else {
        dropdown.classList.remove('open');
        dropdown.style.display = 'none';
    }
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    const container = document.getElementById('userMenuContainer');
    const dropdown = document.getElementById('userDropdown');
    
    if (container && dropdown) {
        if (!container.contains(e.target)) {
            dropdown.classList.remove('open');
            dropdown.style.display = 'none';
            isDropdownOpen = false;
        }
    }
});

// ============================================
// THEME TOGGLE
// ============================================
if (typeof toggleTheme === 'undefined') {
    window.toggleTheme = function() {
        const html = document.documentElement;
        const currentTheme = html.classList.contains('light') ? 'light' : 'dark';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        html.classList.remove(currentTheme);
        html.classList.add(newTheme);
        localStorage.setItem('theme', newTheme);
        
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.style.transition = 'transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
            icon.style.transform = 'rotate(180deg)';
            setTimeout(() => {
                icon.textContent = newTheme === 'light' ? '🌙' : '☀️';
                icon.style.transform = 'rotate(0deg)';
            }, 300);
        }
        
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: newTheme }
        }));
    };
}

// Update theme icon on load
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const theme = html.classList.contains('light') ? 'light' : 'dark';
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.textContent = theme === 'light' ? '🌙' : '☀️';
    }
});

// Listen for theme changes
document.addEventListener('themeChanged', function(e) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.textContent = e.detail.theme === 'light' ? '🌙' : '☀️';
    }
});

// ============================================
// CREATE POST MODAL
// ============================================
function openCreatePostModal() {
    const modal = document.getElementById('createPostModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}
</script>