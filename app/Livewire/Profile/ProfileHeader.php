<?php
// File Path: app/Livewire/Profile/ProfileHeader.php
// Purpose: Cover နှင့် Avatar များအား Bunny CDN URL မှန်ကန်စွာ ထုတ်ယူပြသပေးသော Livewire Component Class

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Services\BunnyStorageService;

class ProfileHeader extends Component
{
    use WithFileUploads;

    public User $user; 
    public $newAvatar;
    public $newCover;
    public $isFollowing = false; // ✅ Follow အခြေအနေ ထိန်းရန်

    public function mount(User $user)
    {
        $this->user = $user;
        $this->checkFollowStatus(); // ✅ စတင်ချိန်တွင် Follow ပြီးသားလား စစ်ဆေးရန်
    }

    /**
     * ✅ Follow Status စစ်ဆေးသည့် Method
     */
    public function checkFollowStatus()
    {
        if (auth()->check()) {
            $this->isFollowing = auth()->user()->following()->where('following_id', $this->user->id)->exists();
        }
    }

    /**
     * ✅ Follow / Unfollow ပြုလုပ်သည့် Method
     */
    public function toggleFollow()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->id() === $this->user->id) {
            return;
        }

        if ($this->isFollowing) {
            auth()->user()->following()->detach($this->user->id);
            $this->isFollowing = false;
        } else {
            auth()->user()->following()->attach($this->user->id);
            $this->isFollowing = true;
        }
    }

    /**
     * Avatar Upload (Image / GIF)
     */
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
                // ✅ ပုံအသစ် တင်အောင်မြင်ပါက Bunny Storage ပေါ်မှ Avatar ပုံဟောင်းအား Auto ဖျက်ထုတ်ခြင်း
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

    /**
     * Cover Upload (Image, GIF, Video - mp4, webm, mov)
     */
    public function updatedNewCover()
    {
        $this->validate([
            'newCover' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm|max:20480',
        ]);

        try {
            $bunny = app(BunnyStorageService::class);
            $timestamp = now()->timestamp;
            $extension = $this->newCover->getClientOriginalExtension();
            $path = "covers/cover_{$this->user->id}_{$timestamp}.{$extension}";

            $result = $bunny->upload(file_get_contents($this->newCover->getRealPath()), $path);

            if ($result['success']) {
                // ✅ Cover အသစ် တင်အောင်မြင်ပါက Bunny Storage ပေါ်မှ Cover ပုံ/ဗီဒီယိုဟောင်းအား Auto ဖျက်ထုတ်ခြင်း
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

        // env သို့မဟုတ် config မှ CDN URL ရယူခြင်း
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

        // env သို့မဟုတ် config မှ CDN URL ရယူခြင်း
        $cdnUrl = env('BUNNY_CDN_URL') ?? config('bunny.cdn_url');

        if ($cdnUrl) {
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->user->cover, '/');
        }

        return asset('storage/' . $this->user->cover);
    }

    // ✅ ဒီနေရာမှာ Follower & Following Count Computed Properties ထည့်ပါ
    #[Computed]
    public function followersCount()
    {
        return $this->user->followers_count;
    }

    #[Computed]
    public function followingCount()
    {
        return $this->user->following_count;
    }

    public function render()
    {
        return view('livewire.profile.profile-header');
    }
}