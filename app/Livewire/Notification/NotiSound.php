<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Livewire\Attributes\On;

class NotiSound extends Component
{
    public bool $notifySound = true;
    public bool $quietHoursEnabled = false;
    public string $quietHoursStart = '22:00';
    public string $quietHoursEnd = '07:00';

    public function mount(): void
    {
        $this->loadUserSettings();
    }

    public function loadUserSettings(): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->notifySound = (bool) ($user->notify_sound ?? true);
            $this->quietHoursEnabled = (bool) ($user->quiet_hours_enabled ?? false);
            $this->quietHoursStart = $user->quiet_hours_start ?? '22:00';
            $this->quietHoursEnd = $user->quiet_hours_end ?? '07:00';
        }
    }

    // 🟢 Settings Page မှ Checkbox / Toggle ပြောင်းလိုက်လျှင် Real-time Update လုပ်ပေးမည်
    #[On('sound-preference-updated')]
    public function updateSoundPreference(?bool $enabled = null): void
    {
        $this->loadUserSettings();
        if ($enabled !== null) {
            $this->notifySound = $enabled;
        }
    }

    // 🟢 Quiet Hours (DND) ကြားရောက်နေသလား စစ်ဆေးပေးမည့် Helper
    public function isQuietHours(): bool
    {
        if (!$this->quietHoursEnabled) {
            return false;
        }

        $now = now()->format('H:i');
        $start = $this->quietHoursStart;
        $end = $this->quietHoursEnd;

        if ($start <= $end) {
            return $now >= $start && $now <= $end;
        } else {
            // ည ၁၀ နာရီမှ နံနက် ၇ နာရီကဲ့သို့ သန်းခေါင်ကျော် အချိန်များအတွက်
            return $now >= $start || $now <= $end;
        }
    }

    public function render()
    {
        return view('livewire.notification.noti-sound', [
            'isMuted' => !$this->notifySound || $this->isQuietHours(),
        ]);
    }
}
