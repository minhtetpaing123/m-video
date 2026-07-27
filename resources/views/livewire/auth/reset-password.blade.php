<div>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8"
         style="background: var(--bg-primary);">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-2xl shadow-blue-500/30">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v12H4V6zm1 1v10h14V7H5zm2 2h10v2H7V9zm0 4h6v2H7v-2zm8 0h2v2h-2v-2z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold" style="color: var(--text-primary);">M-VIDEO</h1>
                <p class="mt-2 text-sm" style="color: var(--text-muted);">{{ __('Reset your password') }}</p>
            </div>

            @if($message)
                <div class="mb-4 p-3 rounded-xl text-sm"
                     style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;">
                    {{ $message }}
                </div>
            @endif

            <form wire:submit="resetPassword" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">
                        {{ __('Email Address') }}
                    </label>
                    <input type="email" wire:model="email" 
                           class="w-full px-4 py-3 rounded-xl border transition-all duration-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none"
                           style="background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color);"
                           placeholder="you@example.com" readonly>
                    @error('email') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">
                        {{ __('New Password') }}
                    </label>
                    <input type="password" wire:model="password" 
                           class="w-full px-4 py-3 rounded-xl border transition-all duration-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none"
                           style="background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color);"
                           placeholder="••••••••" required>
                    @error('password') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">
                        {{ __('Confirm Password') }}
                    </label>
                    <input type="password" wire:model="password_confirmation" 
                           class="w-full px-4 py-3 rounded-xl border transition-all duration-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none"
                           style="background: var(--bg-secondary); color: var(--text-primary); border-color: var(--border-color);"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:scale-[1.02] active:scale-95">
                    {{ __('Reset Password') }}
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