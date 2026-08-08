<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class NotificationSettings extends Component
{
    // Livewire v4: Channels
    public bool $notify_sound = true;
    public bool $notify_in_app = true;
    public bool $notify_email = true;
    public bool $notify_push = true;

    // Livewire v4: Social Interactions
    public bool $notify_comments = true;
    public bool $notify_replies = true;
    public bool $notify_likes = true;
    public bool $notify_mentions = true;
    public bool $notify_follows = true;
    public bool $notify_friend_requests = true;
    public bool $notify_messages = true;

    // Livewire v4: Quiet Hours (DND)
    public bool $quiet_hours_enabled = false;
    public string $quiet_hours_start = '22:00';
    public string $quiet_hours_end = '07:00';

    public function mount(): void
    {
        $user = auth()->user();

        $this->notify_sound = (bool) ($user->notify_sound ?? true);
        $this->notify_in_app = (bool) ($user->notify_in_app ?? true);
        $this->notify_email = (bool) ($user->notify_email ?? true);
        $this->notify_push = (bool) ($user->notify_push ?? true);

        $this->notify_comments = (bool) ($user->notify_comments ?? true);
        $this->notify_replies = (bool) ($user->notify_replies ?? true);
        $this->notify_likes = (bool) ($user->notify_likes ?? true);
        $this->notify_mentions = (bool) ($user->notify_mentions ?? true);
        $this->notify_follows = (bool) ($user->notify_follows ?? true);
        $this->notify_friend_requests = (bool) ($user->notify_friend_requests ?? true);
        $this->notify_messages = (bool) ($user->notify_messages ?? true);

        $this->quiet_hours_enabled = (bool) ($user->quiet_hours_enabled ?? false);
        $this->quiet_hours_start = $user->quiet_hours_start ?? '22:00';
        $this->quiet_hours_end = $user->quiet_hours_end ?? '07:00';
    }

    // Livewire v4 Lifecycle Hook for Real-time Auto Save
    public function updated($propertyName): void
    {
        auth()->user()->update([
            $propertyName => $this->{$propertyName},
        ]);

        // 🔊 Sound Setting ပြောင်းပါက NotiSound Component သို့ Real-time Dispatch လုပ်ပေးမည်
        if ($propertyName === 'notify_sound') {
            $this->dispatch('sound-preference-updated', enabled: (bool) $this->notify_sound);
        }

        $this->dispatch('show-toast', type: 'success', message: 'Setting updated successfully!');
    }

    public function render()
    {
        return view('livewire.settings.notification-settings');
    }
}
