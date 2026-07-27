<div>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8"
         style="background: var(--bg-primary);">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold" style="color: var(--text-primary);">M-VIDEO</h1>
                <p class="mt-2 text-sm" style="color: var(--text-muted);">{{ __('Reset your password') }}</p>
            </div>

            @if($message)
                <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 text-green-500 rounded-xl text-sm">
                    {{ $message }}
                </div>
            @endif

            <form wire:submit="sendResetLink" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">
                        {{ __('Email Address') }}
                    </label>
                    <input type="email" wire:model="email" 
                           class="w-full px-4 py-3 rounded-xl border transition-all duration-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none"
                           style="background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color);"
                           placeholder="you@example.com" required>
                    @error('email') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:scale-[1.02] active:scale-95">
                    {{ __('Send Reset Link') }}
                </button>
            </form>

            <p class="text-center text-sm mt-6" style="color: var(--text-muted);">
                <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-400 font-medium transition">
                    ← {{ __('Back to Login') }}
                </a>
            </p>
        </div>
    </div>
</div>