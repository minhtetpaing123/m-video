<div class="min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8"
     style="background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);">
    
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mx-auto mb-3 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition duration-300 transform hover:scale-105">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 6h18v12H3V6zm1 1v10h16V7H4zm2 2h12v2H6V9zm0 4h8v2H6v-2z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                M-VIDEO
            </h1>
            <p class="mt-1 text-sm text-gray-400">Create your account</p>
        </div>

        {{-- Success Message --}}
        @if(session('status'))
            <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Form --}}
        <form wire:submit="register" class="space-y-4">
            @csrf

            {{-- Full Name with Real-time Duplicate Check --}}
            <div>
                <div class="relative">
                    <input 
                        type="text" 
                        id="name"
                        wire:model.live="name"
                        class="w-full pl-11 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 outline-none transition 
                            @error('name') border-red-500 
                            @elseif(!empty($name) && !$errors->has('name')) border-green-500/50 
                            @endif"
                        placeholder="Full Name"
                        required
                        autofocus
                    >
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    
                    {{-- Real-time Name Validation Icon --}}
                    @if(!empty($name))
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            @if($errors->has('name'))
                                <i class="fas fa-times-circle text-red-400"></i>
                            @else
                                <i class="fas fa-check-circle text-green-400"></i>
                            @endif
                        </div>
                    @endif
                </div>
                
                {{-- Real-time Name Validation Messages --}}
                @if(!empty($name))
                    <div class="mt-1">
                        @if($errors->has('name'))
                            <p class="text-red-400 text-xs flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}
                            </p>
                        @else
                            <p class="text-green-400 text-xs flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Name is available
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Email or Phone Number with Real-time Validation --}}
            <div>
                <div class="relative">
                    <input 
                        type="text" 
                        id="email_or_phone"
                        wire:model.live.debounce.500ms="email"
                        class="w-full pl-11 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 outline-none transition 
                            @error('email') border-red-500 
                            @elseif(!empty($email) && !$errors->has('email')) border-green-500/50 
                            @endif"
                        placeholder="Email or Phone Number"
                        required
                    >
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    
                    {{-- Real-time Validation Icon --}}
                    @if(!empty($email))
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            @if($errors->has('email'))
                                <i class="fas fa-times-circle text-red-400"></i>
                            @else
                                <i class="fas fa-check-circle text-green-400"></i>
                            @endif
                        </div>
                    @endif
                </div>
                
                {{-- Real-time Validation Messages --}}
                @if(!empty($email))
                    <div class="mt-1">
                        @if($errors->has('email'))
                            <p class="text-red-400 text-xs flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}
                            </p>
                        @elseif(filter_var($email, FILTER_VALIDATE_EMAIL) && !$errors->has('email'))
                            <p class="text-green-400 text-xs flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Valid email address
                            </p>
                        @elseif(preg_match('/^[0-9]{10,15}$/', $email))
                            <p class="text-green-400 text-xs flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Valid phone number
                            </p>
                        @else
                            <p class="text-yellow-400 text-xs flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> Enter a valid email or phone number
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Password --}}
            <div>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password"
                        wire:model.live="password"
                        class="w-full pl-11 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 outline-none transition @error('password') border-red-500 @enderror"
                        placeholder="Password (min 6 characters)"
                        required
                        minlength="6"
                    >
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
                
                {{-- Password Strength Indicator --}}
                @if(!empty($password))
                    <div class="mt-1.5">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 
                                    @if(strlen($password) < 4) bg-red-500 w-1/3
                                    @elseif(strlen($password) < 6) bg-yellow-500 w-2/3
                                    @elseif(strlen($password) >= 6 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password)) bg-green-500 w-full
                                    @elseif(strlen($password) >= 6) bg-blue-500 w-4/5
                                    @endif">
                                </div>
                            </div>
                            <span class="text-xs 
                                @if(strlen($password) < 4) text-red-400
                                @elseif(strlen($password) < 6) text-yellow-400
                                @elseif(strlen($password) >= 6 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password)) text-green-400
                                @else text-blue-400
                                @endif">
                                @if(strlen($password) < 4) Weak
                                @elseif(strlen($password) < 6) Fair
                                @elseif(strlen($password) >= 6 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password)) Strong
                                @else Good
                                @endif
                            </span>
                        </div>
                        <div class="flex gap-2 mt-1">
                            <span class="text-[10px] {{ strlen($password) >= 6 ? 'text-green-400' : 'text-gray-500' }}">
                                <i class="fas {{ strlen($password) >= 6 ? 'fa-check-circle' : 'fa-circle' }}"></i> 6+ chars
                            </span>
                            <span class="text-[10px] {{ preg_match('/[A-Z]/', $password) ? 'text-green-400' : 'text-gray-500' }}">
                                <i class="fas {{ preg_match('/[A-Z]/', $password) ? 'fa-check-circle' : 'fa-circle' }}"></i> Uppercase
                            </span>
                            <span class="text-[10px] {{ preg_match('/[0-9]/', $password) ? 'text-green-400' : 'text-gray-500' }}">
                                <i class="fas {{ preg_match('/[0-9]/', $password) ? 'fa-check-circle' : 'fa-circle' }}"></i> Number
                            </span>
                        </div>
                    </div>
                @endif
                
                @error('password')
                    <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password_confirmation"
                        wire:model.live="password_confirmation"
                        class="w-full pl-11 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 outline-none transition 
                            @error('password_confirmation') border-red-500 
                            @elseif(!empty($password_confirmation) && $password === $password_confirmation) border-green-500/50 
                            @endif"
                        placeholder="Confirm Password"
                        required
                    >
                    <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    
                    {{-- Real-time Confirm Password Icon --}}
                    @if(!empty($password_confirmation) && !empty($password))
                        <div class="absolute right-4 top-1/2 -translate-y-1/2">
                            @if($password === $password_confirmation)
                                <i class="fas fa-check-circle text-green-400"></i>
                            @else
                                <i class="fas fa-times-circle text-red-400"></i>
                            @endif
                        </div>
                    @endif
                </div>
                
                {{-- Real-time Password Match Check --}}
                @if(!empty($password_confirmation) && !empty($password))
                    <div class="mt-1">
                        <p class="text-xs flex items-center gap-1.5 
                            @if($password === $password_confirmation) text-green-400 @else text-red-400 @endif">
                            <i class="fas {{ $password === $password_confirmation ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $password === $password_confirmation ? 'Passwords match ✓' : 'Passwords do not match ✗' }}
                        </p>
                    </div>
                @endif
                
                @error('password_confirmation')
                    <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Terms & Conditions Agreement --}}
            <div class="flex items-start gap-2.5 pt-1">
                <input 
                    type="checkbox" 
                    id="agree" 
                    wire:model.live="agree"
                    class="mt-0.5 w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 accent-indigo-500 cursor-pointer @error('agree') border-red-500 @enderror"
                    required
                >
                <label for="agree" class="text-xs text-gray-400 leading-relaxed cursor-pointer">
                    By creating an account, you agree to our 
                    <a href="#" class="text-indigo-400 hover:text-indigo-300 transition">Terms of Service</a> 
                    and 
                    <a href="#" class="text-indigo-400 hover:text-indigo-300 transition">Privacy Policy</a>.
                </label>
            </div>
            @error('agree')
                <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </p>
            @enderror

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition duration-300 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transform hover:scale-[1.02] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="register">
                <span wire:loading.remove wire:target="register">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </span>
                <span wire:loading wire:target="register">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Creating...
                </span>
            </button>

            {{-- Divider --}}
            <div class="flex items-center gap-4 text-gray-500 text-sm">
                <span class="flex-1 h-px bg-white/10"></span>
                <span>or continue with</span>
                <span class="flex-1 h-px bg-white/10"></span>
            </div>

            {{-- Social Buttons --}}
            <div class="flex justify-center gap-3">
                <button type="button" class="w-11 h-11 rounded-xl border border-white/10 bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition transform hover:-translate-y-1">
                    <i class="fab fa-google text-base"></i>
                </button>
                <button type="button" class="w-11 h-11 rounded-xl border border-white/10 bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition transform hover:-translate-y-1">
                    <i class="fab fa-facebook-f text-base"></i>
                </button>
                <button type="button" class="w-11 h-11 rounded-xl border border-white/10 bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white hover:border-white/20 transition transform hover:-translate-y-1">
                    <i class="fab fa-apple text-base"></i>
                </button>
            </div>

            {{-- Login Link --}}
            <p class="text-center text-sm text-gray-400 mt-3">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition" wire:navigate>
                    Sign In
                </a>
            </p>
        </form>
    </div>
</div>

{{-- Password Toggle Script --}}
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>