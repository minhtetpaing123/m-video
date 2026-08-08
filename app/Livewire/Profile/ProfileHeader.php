<?php
// File Path: app/Livewire/Profile/ProfileHeader.php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Notification;
use App\Services\BunnyStorageService;

class ProfileHeader extends Component
{
    use WithFileUploads;

    public User $user; 
    public $newAvatar;
    public $newCover;
    public $isFollowing = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->checkFollowStatus();
    }

    public function checkFollowStatus()
    {
        if (auth()->check()) {
            $this->isFollowing = auth()->user()->isFollowing($this->user);
        }
    }

    /**
     * Follow / Unfollow logic with Unfollow Notification
     */
    public function toggleFollow()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUser = auth()->user();

        if ($currentUser->id === $this->user->id) {
            return;
        }

        if ($this->isFollowing) {
            // Unfollow Logic
            $currentUser->following()->detach($this->user->id);
            $this->isFollowing = false;

            // 🟢 Unfollow Noti
            Notification::updateOrCreate(
                [
                    'user_id'      => $this->user->id,
                    'from_user_id' => $currentUser->id,
                    'type'         => 'unfollow',
                ],
                [
                    'title'           => 'Unfollowed',
                    'content_snippet' => $currentUser->name . ' unfollowed you.',
                    'action_url'      => route('profile.show', $currentUser->id),
                    'image_url'       => $currentUser->avatar_url ?? null,
                    'is_read'         => false,
                    'created_at'      => now(),
                ]
            );
        } else {
            // Follow Logic
            $currentUser->following()->attach($this->user->id);
            $this->isFollowing = true;

            // 🟢 Follow Noti with action_type = 'follow'
            Notification::updateOrCreate(
                [
                    'user_id'      => $this->user->id,
                    'from_user_id' => $currentUser->id,
                    'type'         => 'follow',
                ],
                [
                    'title'           => 'New Follower',
                    'content_snippet' => $currentUser->name . ' started following you.',
                    'action_url'      => route('profile.show', $currentUser->id),
                    'image_url'       => $currentUser->avatar_url ?? null,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'action_type'     => 'follow',
                    'action_data'     => json_encode([
                        'user_id' => $currentUser->id
                    ]),
                ]
            );
        }

        $this->dispatch('notificationCountUpdated');
        $this->user->refresh();
    }

    public function updatedNewAvatar()
    {
        $this->validate([
            'newAvatar' => 'required|image|max:5120',
        ]);

        try {
            $bunny = app(BunnyStorageService::class);
            $timestamp = now()->timestamp;
            $extension = $this->newAvatar->getClientOriginalExtension();
            $path = "avatars/avatar_{$this->user->id}_{$timestamp}.{$extension}";

            $result = $bunny->upload(file_get_contents($this->newAvatar->getRealPath()), $path);

            if ($result['success']) {
                if ($this->user->avatar && !str_starts_with($this->user->avatar, 'http')) {
                    $bunny->delete($this->user->avatar);
                }

                $this->user->avatar = $path;
                $this->user->save();
                session()->flash('message', 'Profile picture updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->reset('newAvatar');
    }

    public function updatedNewCover()
    {
        $this->validate([
            'newCover' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webm,video/mp4,video/quicktime|max:20480',
        ]);

        try {
            $bunny = app(BunnyStorageService::class);
            $timestamp = now()->timestamp;
            $extension = $this->newCover->getClientOriginalExtension();
            $path = "covers/cover_{$this->user->id}_{$timestamp}.{$extension}";

            $result = $bunny->upload(file_get_contents($this->newCover->getRealPath()), $path);

            if ($result['success']) {
                if ($this->user->cover && !str_starts_with($this->user->cover, 'http')) {
                    $bunny->delete($this->user->cover);
                }

                $this->user->cover = $path;
                $this->user->save();
                session()->flash('message', 'Cover updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->reset('newCover');
    }

    #[Computed]
    public function avatarUrl()
    {
        if (!$this->user->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&background=1877f2&color=ffffff';
        }

        if (str_starts_with($this->user->avatar, 'http://') || str_starts_with($this->user->avatar, 'https://')) {
            return $this->user->avatar;
        }

        $cdnUrl = env('BUNNY_CDN_URL') ?? config('bunny.cdn_url');
        
        if ($cdnUrl) {
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->user->avatar, '/');
        }

        return asset('storage/' . $this->user->avatar);
    }

    #[Computed]
    public function coverUrl()
    {
        if (!$this->user->cover) {
            return null;
        }

        if (str_starts_with($this->user->cover, 'http://') || str_starts_with($this->user->cover, 'https://')) {
            return $this->user->cover;
        }

        $cdnUrl = env('BUNNY_CDN_URL') ?? config('bunny.cdn_url');

        if ($cdnUrl) {
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->user->cover, '/');
        }

        return asset('storage/' . $this->user->cover);
    }

    #[Computed]
    public function followersCount()
    {
        return $this->user->followers()->count();
    }

    #[Computed]
    public function followingCount()
    {
        return $this->user->following()->count();
    }

    public function render()
    {
        return view('livewire.profile.profile-header');
    }
}
