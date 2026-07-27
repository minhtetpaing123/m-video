<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;

class CommentSection extends Component
{
    public Post $post;
    public $commentText = '';
    public $replyText = '';
    public $replyingToCommentId = null;
    public $perPage = 5;

    // ✅ Edit Feature အတွက် Variable များ
    public $editingCommentId = null;
    public $editText = '';

    protected $rules = [
        'commentText' => 'required|min:1|max:1000',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    // Main Comment တင်ရန်
    public function addComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate(['commentText' => 'required|min:1|max:1000']);

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => null,
            'content' => $this->commentText,
        ]);

        $this->post->increment('comments_count');
        $this->commentText = '';
    }

    // Reply Box ပွင့်/ပိတ် လုပ်ရန်
    public function toggleReply($commentId)
    {
        if ($this->replyingToCommentId === $commentId) {
            $this->replyingToCommentId = null;
        } else {
            $this->replyingToCommentId = $commentId;
            $this->replyText = '';
        }
    }

    // Reply တင်ရန်
    public function addReply($parentId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (empty(trim($this->replyText))) return;

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'content' => $this->replyText,
        ]);

        $this->post->increment('comments_count');
        $this->replyText = '';
        $this->replyingToCommentId = null;
    }

    // ✅ Comment ပြင်ရန် (Edit Mode)
    public function editComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === auth()->id()) {
            $this->editingCommentId = $commentId;
            $this->editText = $comment->content;
        }
    }

    // ✅ Edit ထားသည်ကို သိမ်းဆည်းရန် (Update)
    public function updateComment($commentId)
    {
        $this->validate(['editText' => 'required|min:1|max:1000']);

        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === auth()->id()) {
            $comment->update([
                'content' => $this->editText,
            ]);
        }

        $this->editingCommentId = null;
        $this->editText = '';
    }

    // ✅ Edit Mode ပယ်ဖျက်ရန်
    public function cancelEdit()
    {
        $this->editingCommentId = null;
        $this->editText = '';
    }

    // ✅ Comment ဖျက်ရန် (Delete)
    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        
        // Comment ပိုင်ရှင် (သို့) Post ပိုင်ရှင်ဖြစ်ပါက ဖျက်ခွင့်ပေးမည်
        if ($comment && ($comment->user_id === auth()->id() || $this->post->user_id === auth()->id())) {
            
            // Sub-replies များရှိပါက ထို Count များကိုပါ တွက်ချက်လျှော့ချမည်
            $repliesCount = $comment->replies()->count();
            $comment->delete();

            // Comment Count ကို decrement လုပ်မည်
            $this->post->decrement('comments_count', 1 + $repliesCount);
        }
    }

    // Comment တွေ ပိုမို ကြည့်ရန်
    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->take($this->perPage)
            ->get();

        $totalParentComments = $this->post->comments()->whereNull('parent_id')->count();

        return view('livewire.dashboard.post.comment-section', [
            'comments' => $comments,
            'hasMore' => $totalParentComments > $this->perPage
        ]);
    }
}
