<div>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <h1 class="text-2xl font-bold text-white">⚙️ Settings</h1>
            <p class="text-gray-400 text-sm mt-1">Settings page is working!</p>
            
            <div class="mt-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
                <p class="text-green-400">✅ Settings page is working correctly!</p>
            </div>
            
            <a href="{{ route('profile.show', auth()->user()) }}" 
               class="mt-4 inline-block text-blue-400 hover:underline">
                ← Back to Profile
            </a>
        </div>
    </div>
</div>