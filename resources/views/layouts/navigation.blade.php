{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="bg-black/95 backdrop-blur-md border-b border-white/5 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-white">M-<span class="text-blue-500">VIDEO</span></span>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider hidden sm:block">Premium</span>
                </a>
            </div>
            
            {{-- Right Side --}}
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition text-sm hidden md:block">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-white transition text-sm hidden md:block">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white transition text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition text-sm">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>