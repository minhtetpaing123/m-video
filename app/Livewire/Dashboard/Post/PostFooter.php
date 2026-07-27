<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;

class PostFooter extends Component
{
    public Post $post;
    public $isLiked = false;
    public $userReaction = null; // ✅ User ပေးထားသည့် reaction အမျိုးအစားကို မှတ်ရန်
    public $likesCount = 0;
    public $commentsCount = 0;
    public $sharesCount = 0;
    public $viewsCount = 0; // ✅ View Count property ထည့်သွင်းခြင်း
    
    // ✅ Comment Box ပွင့်/ပိတ် ထိန်းချုပ်ရန် state
    public $showComments = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->likesCount = $post->likes_count ?? 0;
        $this->commentsCount = $post->comments_count ?? 0;
        $this->sharesCount = $post->shares_count ?? 0;
        $this->viewsCount = $post->views_count ?? 0; // ✅ Database မှ views_count ကို ယူမည်[span_0](start_span)[span_0](end_span)

        if (auth()->check()) {
            $existingReaction = $this->post->reactions()->where('user_id', auth()->id())->first();
            if ($existingReaction) {
                $this->isLiked = true;
                $this->userReaction = $existingReaction->type; // ✅ database ထဲက type ကို ယူမည်[span_1](start_span)[span_1](end_span)
            } else {
                $this->isLiked = false;
                $this->userReaction = null;
            }
        }
    }

    public function toggleLike()
    {
        // ပုံမှန် Like ခလုတ်ကို နှိပ်လျှင် 'like' ဖြင့် လုပ်ဆောင်ရန်[span_2](start_span)[span_2](end_span)
        $this->react('like');
    }

    // ✅ Emoji Reactions များအတွက် Method အသစ်[span_3](start_span)[span_3](end_span)
    public function react($type)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();
        $existingReaction = $this->post->reactions()->where('user_id', $userId)->first();

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // တူညီသော reaction ကို ထပ်နှိပ်လျှင် ဖျက်မည် (Unlike)[span_4](start_span)[span_4](end_span)
                $existingReaction->delete();
                $this->isLiked = false;
                $this->userReaction = null;
                $this->likesCount = max(0, $this->likesCount - 1);
            } else {
                // Reaction အမျိုးအစား ပြောင်းလဲလျှင် Update လုပ်မည်[span_5](start_span)[span_5](end_span)
                $existingReaction->update(['type' => $type]);
                $this->isLiked = true;
                $this->userReaction = $type;
            }
        } else {
            // အသစ်ထည့်မည်[span_6](start_span)[span_6](end_span)
            $this->post->reactions()->create([
                'user_id' => $userId,
                'type' => $type
            ]);
            $this->isLiked = true;
            $this->userReaction = $type;
            $this->likesCount++;
        }

        $this->post->update(['likes_count' => $this->likesCount]);
    }

    // ✅ Comment Button နှိပ်လိုက်ရင် ပွင့်/ပိတ် လုပ်ပေးမည့် Method[span_7](start_span)[span_7](end_span)
    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function sharePost()
    {
        $this->sharesCount++;
        $this->post->update(['shares_count' => $this->sharesCount]);

        $this->dispatch('notify', [
            'message' => 'Post shared successfully! 🔗',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.post.post-footer');
    }
}
