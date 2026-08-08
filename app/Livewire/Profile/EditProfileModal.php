<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Services\BunnyStorageService;
use Illuminate\Support\Facades\Hash;

class EditProfileModal extends Component
{
    use WithFileUploads;

    public string $activeTab = 'menu'; // Options: 'menu', 'edit-profile', 'change-password'

    // Profile Fields
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:50')]
    public ?string $username = '';

    #[Validate('nullable|string|max:500')]
    public ?string $bio = '';

    public $newAvatar;
    public $newCover;

    // Password Fields
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        /** @var User $user */
        $user = auth()->user();

        $this->name = $user->name ?? '';
        $this->username = $user->username ?? '';
        $this->bio = $user->bio ?? '';
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    public function saveProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $bunny = app(BunnyStorageService::class);

        // Avatar Upload
        if ($this->newAvatar) {
            $this->validate(['newAvatar' => 'image|max:5120']);
            $timestamp = now()->timestamp;
            $extension = $this->newAvatar->getClientOriginalExtension();
            $path = "avatars/avatar_{$user->id}_{$timestamp}.{$extension}";

            $result = $bunny->upload(file_get_contents($this->newAvatar->getRealPath()), $path);

            if ($result['success']) {
                if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                    $bunny->delete($user->avatar);
                }
                $user->avatar = $path;
            }
        }

        // Cover Upload
        if ($this->newCover) {
            $this->validate(['newCover' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webm,video/mp4,video/webm,video/quicktime|max:20480']);
            $timestamp = now()->timestamp;
            $extension = $this->newCover->getClientOriginalExtension();
            $path = "covers/cover_{$user->id}_{$timestamp}.{$extension}";

            $result = $bunny->upload(file_get_contents($this->newCover->getRealPath()), $path);

            if ($result['success']) {
                if ($user->cover && !str_starts_with($user->cover, 'http')) {
                    $bunny->delete($user->cover);
                }
                $user->cover = $path;
            }
        }

        $user->name = $this->name;
        $user->username = $this->username;
        $user->bio = $this->bio;
        $user->save();

        session()->flash('message', 'Profile updated successfully!');
        $this->activeTab = 'menu';
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|same:new_password_confirmation',
        ], [
            'new_password.same' => 'New password confirmation does not match.',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password does not match our records.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Password changed successfully!');
        $this->activeTab = 'menu';
    }

    public function render()
    {
        return view('livewire.profile.edit-profile-modal');
    }
}
