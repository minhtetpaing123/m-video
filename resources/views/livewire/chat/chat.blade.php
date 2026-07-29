<div class="fixed inset-0 w-full flex flex-col bg-white dark:bg-[#121212] text-gray-900 dark:text-white max-w-2xl mx-auto overflow-hidden" 
     style="height: 100svh; height: 100dvh;"
     x-data="{ hasText: false }">
    
    {{-- 1. HEADER SECTION --}}
    <livewire:chat.chat-header :user="$user" />

    {{-- 2. MESSAGES AREA SECTION --}}
    <livewire:chat.chat-messages :user="$user" />

    {{-- 3. INPUT BAR SECTION --}}
    <livewire:chat.chat-input :user="$user" />

    {{-- 4.🔥 Voice Call Component --}}
    <livewire:chat.voice-call-modal />
  
</div>

{{-- Dynamic Auto Scroll & Sound Script --}}
<script>
    // 🔊 Audio Object တည်ဆောက်ခြင်း
    const sendAudio = new Audio('/sounds/send.mp3');

    // 🔊 စာပို့လိုက်သည်နှင့် အသံဖွင့်မည့် Event Listener
    window.addEventListener('play-send-sound', () => {
        sendAudio.currentTime = 0;
        sendAudio.play().catch(error => {
            console.log("Audio play blocked:", error);
        });
    });

    function scrollToBottom() {
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            requestAnimationFrame(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        }
    }

    function handleViewportShift() {
        if (window.scrollY !== 0) {
            window.scrollTo(0, 0);
        }
        scrollToBottom();
    }

    // Window scroll မဖြစ်အောင် ကာကွယ်ခြင်း
    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            window.scrollTo(0, 0);
        }
    });

    // ၁။ Page load တက်ချိန်
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(scrollToBottom, 100);
    });

    // ၂။ Livewire Page Navigate ဖြစ်ချိန်
    document.addEventListener('livewire:navigated', () => {
        setTimeout(scrollToBottom, 100);
    });

    // ၃။ စာပို့ပြီးချိန်/Message တက်လာချိန် (Livewire Event မှတဆင့် Auto Scroll လုပ်မည်)
    window.addEventListener('scroll-to-bottom', () => {
        setTimeout(scrollToBottom, 50);
        setTimeout(scrollToBottom, 150);
    });

    // ၄။ Visual Viewport ပြောင်းလဲချိန် (Keyboard ပွင့်လာချိန်)
    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', handleViewportShift);
        window.visualViewport.addEventListener('scroll', handleViewportShift);
    }
</script>
