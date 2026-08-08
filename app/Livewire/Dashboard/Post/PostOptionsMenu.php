<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostOptionsMenu extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function savePost()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Database saved_posts table ထဲသို့ Toggle (Save / Unsave) ပြုလုပ်ခြင်း
        if ($user->savedPosts()->where('post_id', $this->post->id)->exists()) {
            $user->savedPosts()->detach($this->post->id);
        } else {
            $user->savedPosts()->attach($this->post->id);
        }

        // BookmarkButton component နှင့် SavedPosts page ကို Auto Sync လုပ်ပေးမည်
        $this->dispatch('bookmark-updated');
    }

    public function editPost()
    {
        // CreatePostModal ထံသို့ Post ID ပါဝင်သော event ကို Dispatch လုပ်၍ Edit Mode ဖွင့်ခိုင်းခြင်း
        $this->dispatch('open-create-post-modal', postId: $this->post->id);
    }

    public function deletePost()
    {
        // Soft delete logic with toast notification (မူလအတိုင်း ထိန်းသိမ်းထားပါသည်)
        $this->dispatch('notify', [
            'message' => 'Post deleted! 🗑️',
            'type' => 'info',
            'undo' => true,
            'postId' => $this->post->id
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.post-options-menu');
    }
}
