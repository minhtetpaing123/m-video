<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;
use App\Models\Notification;

class PostCard extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        // Post Card ကို render မလုပ်မီ views_count ကို 1 တိုးမည်
        $this->post->increment('views_count');
    }

    // Reaction (like, love, haha, wow, sad, angry) နှိပ်သည့်အခါ ခေါ်ယူမည့် Function
    public function toggleReaction($reactionType = 'like')
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ၁။ Reaction Database Logic (မိတ်ဆွေ၏ Reaction Table structure အတိုင်း အလုပ်လုပ်ပါမည်)
        // တကယ်လို့ Reaction Model ရှိပါက အသုံးပြုနိုင်ရန် Standard Dynamic Type သတ်မှတ်ပေးခြင်း
        $notiType = 'reaction_' . strtolower($reactionType);

        // ၂။ မိမိကိုယ်တိုင် ပေးသော Reaction မဟုတ်ပါက Noti DB ထဲသို့ ထည့်မည်/ပြင်မည်
        if ($this->post->user_id !== auth()->id()) {
            Notification::updateOrCreate(
                [
                    'user_id'      => $this->post->user_id, // Post ပိုင်ရှင်
                    'from_user_id' => auth()->id(),            // Reaction ပေးသူ
                    'post_id'      => $this->post->id,
                ],
                [
                    'type'         => $notiType,            // 'reaction_haha', 'reaction_wow', 'reaction_love' စသည်ဖြင့်
                    'action_url'   => route('posts.show', $this->post->id),
                    'is_read'      => false,
                ]
            );
        }

        $this->dispatch('notify', [
            'message' => 'Reaction updated!',
            'type' => 'success'
        ]);
    }

    // Share Button နှိပ်သည့်အခါ ခေါ်ယူမည့် Function
    public function sharePost()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // ၁။ Share Count ၁ တိုးမည်
        $this->post->increment('shares_count');

        // ၂။ မိမိ Post မဟုတ်ပါက Post ပိုင်ရှင်ထံ Noti ပို့မည်
        if ($this->post->user_id !== auth()->id()) {
            Notification::create([
                'user_id'      => $this->post->user_id, // Post ပိုင်ရှင်
                'from_user_id' => auth()->id(),            // Share ပြုလုပ်သူ
                'post_id'      => $this->post->id,
                'type'         => 'share',              // Noti Type ကို 'share' ဟု သိမ်းမည်
                'action_url'   => route('posts.show', $this->post->id),
                'is_read'      => false,
            ]);
        }

        $this->dispatch('notify', [
            'message' => 'Post shared successfully! 🚀',
            'type' => 'success'
        ]);
    }

    public function deletePost()
    {
        // Post ကို soft delete လုပ်ပြီး Main component သို့ notification dispatch ပေးမည်
        $postId = $this->post->id;
        $this->post->delete();

        $this->dispatch('post-deleted', postId: $postId);
        $this->dispatch('notify', [
            'message' => 'Post deleted successfully! 🗑️',
            'type' => 'info',
            'undo' => true,
            'postId' => $postId
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.post-card');
    }
}
