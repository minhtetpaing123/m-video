<div>
    {{-- User Setting အမှန်ဖြစ်ပြီး Mute/DND မဖြစ်နေမှသာ Audio Tag ကို Render လုပ်မည် --}}
    @if(auth()->check() && !$isMuted)
        <audio id="notificationSound" src="{{ asset('sounds/noti.mp3') }}" preload="auto" playsinline></audio>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('play-notification-sound', () => {
                const sound = document.getElementById('notificationSound');
                // DOM ထဲတွင် Audio Tag ရှိမှသာ (Sound On ဖြစ်နေမှသာ) အသံမြည်မည်
                if (sound) {
                    sound.currentTime = 0;
                    sound.play().catch(e => console.log('Audio playback blocked:', e));
                } else {
                    console.log('🔇 Notification Sound is MUTED or in Quiet Hours.');
                }
            });
        });

        // Global Helper
        window.playNotificationSound = function() {
            Livewire.dispatch('play-notification-sound');
        };
    </script>
</div>
