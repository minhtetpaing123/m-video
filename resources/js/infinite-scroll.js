// resources/js/infinite-scroll.js

let scrollObserver = null;
let isFetchingNextPage = false;

export function initInfiniteScroll() {
    const sentinel = document.getElementById('scroll-sentinel');
    if (!sentinel) return;

    // မူလရှိပြီးသား Observer ကို Disconnect လုပ်မည်
    if (scrollObserver) {
        scrollObserver.disconnect();
    }

    scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isFetchingNextPage) {
                isFetchingNextPage = true;

                // Sentinel နဲ့ အနီးဆုံး Livewire Component ကို ရှာပြီး loadMore() ခေါ်ယူခြင်း
                const wireElement = sentinel.closest('[wire\\:id]');
                
                if (wireElement && window.Livewire) {
                    const componentId = wireElement.getAttribute('wire:id');
                    const component = window.Livewire.find(componentId);

                    if (component) {
                        component.call('loadMore').then(() => {
                            isFetchingNextPage = false;
                        }).catch(() => {
                            isFetchingNextPage = false;
                        });
                    } else {
                        isFetchingNextPage = false;
                    }
                } else {
                    isFetchingNextPage = false;
                }
            }
        });
    }, {
        root: null,
        rootMargin: '1000px', // Screen အောက်မရောက်မီ 1000px အလို ကြို Fetch လုပ်မည်
        threshold: 0.1
    });

    scrollObserver.observe(sentinel);
}

// Livewire Page Load ချိန်နှင့် SPA (wire:navigate) ကူးချိန်များတွင် ပုံမှန်အလုပ်လုပ်စေရန် Listener ထည့်ခြင်း
document.addEventListener('livewire:initialized', initInfiniteScroll);
document.addEventListener('livewire:navigated', initInfiniteScroll);
