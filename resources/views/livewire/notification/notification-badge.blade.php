<div class="relative w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center inline-flex">

    {{-- Bell Icon --}}
    <svg viewBox="0 0 28 28" class="w-6 h-6 sm:w-7 sm:h-7 fill-current">
        <path d="M7.847 23.488c1.36 0 3.596-.125 6.62-.682 3.348-.623 5.016-2.718 5.437-5.568.093-.636.14-1.287.14-1.943v-1.901c0-.92.092-2.089.703-3.06.607-.972 1.756-1.79 3.695-1.49.349.053.701.12 1.054.203.434.102.834.297 1.171.572.337.274.6.62.762 1.014.163.393.22.822.163 1.246-.056.424-.226.83-.494 1.177a2.878 2.878 0 0 1-1.174.861 9.187 9.187 0 0 1-.349 1.386 9.223 9.223 0 0 1-.678 1.64c-.411.765-.941 1.456-1.575 2.05a8.842 8.842 0 0 0-2.045 1.509 8.25 8.25 0 0 1-1.376 1.044c-.42.254-.86.466-1.314.635-.656.237-1.369.366-2.126.366H7.847c-1.36 0-2.606-.487-3.564-1.294a5.206 5.206 0 0 1-1.508-2.236 5.566 5.566 0 0 1-.197-2.734c.119-.878.483-1.701 1.051-2.376a4.6 4.6 0 0 1 2.057-1.378c.665-.226 1.387-.35 2.135-.35h8.282c.115 0 .23.003.343.01a.5.5 0 0 0 .1-.984c-.119-.012-.239-.019-.359-.02h-8.282c-.866 0-1.707.135-2.49.385a5.63 5.63 0 0 0-2.518 1.691 5.708 5.708 0 0 0-1.307 2.963c-.111.821-.054 1.66.165 2.463a6.206 6.206 0 0 0 1.8 2.715c1.127 1.03 2.6 1.597 4.194 1.597z"/>
    </svg>

    {{-- Unread Counter Badge --}}
    @if($unreadCount > 0)
        <span class="absolute -top-1 -right-1.5 bg-[#e74c3c] text-white text-[8px] sm:text-[10px] font-bold min-w-[16px] sm:min-w-[18px] h-4 sm:h-[18px] rounded-full flex items-center justify-center px-1 border-2 border-[#1a1a2e] animate-pulse">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    @endif

</div>

{{-- Service Worker Compatible Notification Script --}}
<script>
    document.addEventListener('livewire:init', () => {
        // ၁။ Browser Permission မူလတောင်းဆိုခြင်း
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // ၂။ Reverb မဂ္ဂဇင်း Noti ရောက်ရှိလာချိန် မိုဘိုင်း Service Worker မှတစ်ဆင့် Pop-up တင်ခြင်း
        Livewire.on('noti-received', (eventData) => {
            const data = Array.isArray(eventData) ? eventData[0] : eventData;

            // အသံမြည်စေခြင်း
            let sound = document.getElementById('notificationSound');
            if (sound) {
                sound.currentTime = 0;
                sound.play().catch(e => console.log('Audio autoplay blocked by browser policy:', e));
            }

            // Android / Mobile Compatible Pop-up Display
            if ('Notification' in window && Notification.permission === 'granted') {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.ready.then((registration) => {
                        // Android Chrome/Kiwi တွင် တိုက်ရိုက် အလုပ်လုပ်သော showNotification ကို သုံးခြင်း
                        registration.showNotification(data.title || 'အကြောင်းကြားစာ', {
                            body: data.message || 'နိုတီဖီကေးရှင်း အသစ် ရောက်ရှိလာပါသည်။',
                            icon: data.icon || '/favicon.ico',
                            vibrate: [200, 100, 200],
                            data: {
                                url: data.url || '/noti'
                            }
                        });
                    }).catch(err => {
                        console.error('ServiceWorker Notification error:', err);
                    });
                }
            }
        });
    });
</script>
