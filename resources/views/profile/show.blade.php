{{-- resources/views/profile/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-card-light {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        .avatar-ring {
            background: linear-gradient(135deg, #f093fb, #f5576c, #4facfe);
            padding: 3px;
            border-radius: 50%;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.12);
        }
        
        .video-card:hover .video-overlay {
            opacity: 1;
        }
        
        .video-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .glow-text {
            text-shadow: 0 0 40px rgba(79, 172, 254, 0.3);
        }
        
        .nav-blur {
            background: rgba(15, 12, 41, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #1a1a2e;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #f093fb, #4facfe);
            border-radius: 10px;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-white">

    {{-- Navbar --}}
    <nav class="nav-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                        🎬 M-Video
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden sm:block">Premium</span>
                </a>
                <div class="flex items-center gap-4">
                    @if(auth()->check() && auth()->id() == $user->id)
                        <a href="{{ route('profile.edit') }}" 
                           class="text-sm text-gray-300 hover:text-white transition flex items-center gap-2 bg-white/5 hover:bg-white/10 px-4 py-2 rounded-full border border-white/10">
                            <i class="fas fa-sliders-h"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition text-sm">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        {{-- Profile Header --}}
        <div class="glass-card rounded-3xl p-8 md:p-10 mb-8 relative overflow-hidden">
            {{-- Background Glow --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-8">
                {{-- Avatar --}}
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('default-avatar.png') }}" 
                         alt="Avatar"
                         class="w-28 h-28 rounded-full object-cover border-2 border-white/10">
                </div>

                {{-- User Info --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-extrabold glow-text">{{ $user->name }}</h1>
                    <p class="text-gray-400 text-sm mt-1">@ {{ $user->username ?? 'user' }}</p>
                    <p class="text-gray-300 mt-2 max-w-md mx-auto md:mx-0">{{ $user->bio ?? 'No bio yet' }}</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-3 text-sm text-gray-400">
                        <span><i class="fas fa-video text-blue-400 mr-1"></i> {{ count($videos) }} Videos</span>
                        <span><i class="fas fa-calendar-alt text-purple-400 mr-1"></i> Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="glass-card-light rounded-2xl p-5 text-center stat-card transition cursor-pointer">
                <div class="text-3xl font-extrabold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                    {{ count($videos) }}
                </div>
                <div class="text-gray-400 text-sm mt-1">Total Videos</div>
            </div>
            <div class="glass-card-light rounded-2xl p-5 text-center stat-card transition cursor-pointer">
                <div class="text-3xl font-extrabold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    {{ count($videos) > 0 ? count($videos) * 10 : 0 }}
                </div>
                <div class="text-gray-400 text-sm mt-1">Total Views</div>
            </div>
            <div class="glass-card-light rounded-2xl p-5 text-center stat-card transition cursor-pointer">
                <div class="text-3xl font-extrabold bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">
                    {{ count($videos) > 0 ? 10 : 0 }}
                </div>
                <div class="text-gray-400 text-sm mt-1">Avg Views</div>
            </div>
            <div class="glass-card-light rounded-2xl p-5 text-center stat-card transition cursor-pointer">
                <div class="text-3xl font-extrabold bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">
                    {{ count($videos) > 0 ? count($videos) : 0 }}
                </div>
                <div class="text-gray-400 text-sm mt-1">Uploads</div>
            </div>
        </div>

        {{-- Videos Section --}}
        <div class="glass-card rounded-3xl p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-film text-blue-400"></i> My Videos
                </h2>
                <span class="text-sm text-gray-400">{{ count($videos) }} videos</span>
            </div>
            
            @if(count($videos) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($videos as $video)
                        <div class="group glass-card-light rounded-2xl overflow-hidden transition hover:scale-[1.02] duration-300 cursor-pointer">
                            <div class="relative">
                                <img src="{{ $video->thumbnail_path ? asset('storage/'.$video->thumbnail_path) : asset('default-thumb.jpg') }}" 
                                     alt="{{ $video->title }}"
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 video-overlay bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end justify-between p-3">
                                    <span class="text-xs font-medium bg-white/20 backdrop-blur px-2 py-1 rounded-full">
                                        <i class="fas fa-play text-blue-400 mr-1"></i> Watch
                                    </span>
                                    <span class="text-xs font-medium bg-black/50 backdrop-blur px-2 py-1 rounded-full">
                                        {{ $video->duration ?? '0:00' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold text-sm truncate">{{ $video->title }}</h3>
                                <div class="flex items-center justify-between mt-1 text-xs text-gray-400">
                                    <span><i class="fas fa-eye mr-1"></i> {{ $video->views ?? 0 }}</span>
                                    <span>{{ $video->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $videos->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4 opacity-20">🎬</div>
                    <p class="text-gray-400 text-lg">No videos uploaded yet</p>
                    <p class="text-gray-500 text-sm mt-1">Start sharing your content with the world</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>