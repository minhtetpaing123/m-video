<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class NotificationsPage extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'all';

    public int $perPage = 15;

    public array $selectedNotifications = [];
    public bool $selectAll = false;

    protected $paginationTheme = 'tailwind';

    /**
     * Livewire v4: Dynamic Private Channel Listener များကို ရေးသားသည့် နေရာ
     */
    public function getListeners()
    {
        $userId = auth()->id();

        return [
            'refreshNotifications' => '$refresh',
            'notificationCountUpdated' => '$refresh',
            "echo-private:App.Models.User.{$userId},NotificationSent" => 'handleNewNotification',
            "echo-private:App.Models.User.{$userId},.NotificationSent" => 'handleNewNotification',
        ];
    }

    // Dynamic မဟုတ်သော static event များကို Attribute အဖြစ်လည်း ထားနိုင်ပါသည်
    #[On('refreshNotifications')]
    #[On('notificationCountUpdated')]
    public function refreshPage(): void
    {
        // View ကို Auto Refresh ပြုလုပ်ပေးမည်
    }

    public function handleNewNotification($event = null): void
    {
        $shouldPlaySound = is_array($event) ? ($event['shouldPlaySound'] ?? true) : true;

        if ($shouldPlaySound) {
            $this->dispatch('playNotificationSound');
        }

        $this->dispatch('$refresh');
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $query = auth()->user()->notifications();
            if ($this->filter === 'unread') {
                $query->where('is_read', false);
            }
            $this->selectedNotifications = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function deleteSelected(): void
    {
        if (!empty($this->selectedNotifications)) {
            auth()->user()->notifications()
                ->whereIn('id', $this->selectedNotifications)
                ->delete();

            $this->selectedNotifications = [];
            $this->selectAll = false;

            $this->dispatch('notificationCountUpdated');
            $this->dispatch('refreshNotifications');
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $this->dispatch('notificationCountUpdated');
        $this->dispatch('refreshNotifications');
    }

    public function clearAllNotifications(): void
    {
        auth()->user()->notifications()->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;

        $this->dispatch('notificationCountUpdated');
        $this->dispatch('refreshNotifications');
    }

    public function loadMore(): void
    {
        $this->perPage += 15;
    }

    public function render()
    {
        $query = auth()->user()->notifications();

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'urgent') {
            $query->whereIn('priority', ['high', 'urgent']);
        }

        return view('livewire.notification.notifications-page', [
            'notifications' => $query->latest()->paginate($this->perPage)
        ])->layout('layouts.app');
    }
}
