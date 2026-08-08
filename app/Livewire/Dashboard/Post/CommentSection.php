<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use App\Events\NotificationSent;
use Illuminate\Support\Str;

class CommentSection extends Component
{
    public Post $post;
    public $commentText = '';
    public $replyText = '';
    public $editText = '';
    public $replyingToCommentId = null;
    public $editingCommentId = null;
    public $perPage = 5;
    public $hasMore = false;

    /**
     * Parent (PostFooter) ထံသို့ Modal ပိတ်ရန် Event ပေးပို့မည်
     */
    public function closeSection()
    {
        $this->dispatch('closeCommentsModal', postId: $this->post->id);
    }

    public function addComment()
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->validate(['commentText' => 'required|string|max:1000']);

        $comment = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'content' => $this->commentText,
        ]);

        if ($this->post->user_id !== auth()->id()) {
            $notification = Notification::create([
                'user_id' => $this->post->user_id,
                'from_user_id' => auth()->id(),
                'post_id' => $this->post->id,
                'comment_id' => $comment->id,
                'type' => 'comment',
                'content_snippet' => Str::limit($this->commentText, 50),
                'is_read' => false,
            ]);

            // 🟢 Post ပိုင်ရှင်ဆီ Reverb Broadcasting ဖြင့် တိုက်ရိုက် Notification ပို့မည်
            NotificationSent::dispatch($notification);
        }

        $this->commentText = '';
        $this->dispatch('refreshPostFooter');
    }

    public function toggleReply($commentId)
    {
        $this->replyingToCommentId = ($this->replyingToCommentId === $commentId) ? null : $commentId;
        $this->replyText = '';
    }

    public function addReply($parentCommentId)
    {
        if (!auth()->check()) return redirect()->route('login');

        $this->validate(['replyText' => 'required|string|max:1000']);

        $parentComment = Comment::find($parentCommentId);

        $reply = Comment::create([
            'post_id' => $this->post->id,
            'parent_id' => $parentCommentId,
            'user_id' => auth()->id(),
            'content' => $this->replyText,
        ]);

        if ($parentComment && $parentComment->user_id !== auth()->id()) {
            $notification = Notification::create([
                'user_id' => $parentComment->user_id,
                'from_user_id' => auth()->id(),
                'post_id' => $this->post->id,
                'comment_id' => $reply->id,
                'type' => 'comment_reply',
                'content_snippet' => Str::limit($this->replyText, 50),
                'is_read' => false,
            ]);

            // 🟢 Comment ပိုင်ရှင်ဆီ Reverb Broadcasting ဖြင့် တိုက်ရိုက် Notification ပို့မည်
            NotificationSent::dispatch($notification);
        }

        $this->replyText = '';
        $this->replyingToCommentId = null;
        $this->dispatch('refreshPostFooter');
    }

    public function editComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === auth()->id()) {
            $this->editingCommentId = $commentId;
            $this->editText = $comment->content;
        }
    }

    public function updateComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === auth()->id()) {
            $comment->update(['content' => $this->editText]);
            $this->cancelEdit();
        }
    }

    public function cancelEdit()
    {
        $this->editingCommentId = null;
        $this->editText = '';
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && ($comment->user_id === auth()->id() || $this->post->user_id === auth()->id())) {
            $comment->delete();
            $this->dispatch('refreshPostFooter');
        }
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }

    public function render()
    {
        $allComments = Comment::where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->take($this->perPage + 1)
            ->get();

        $this->hasMore = $allComments->count() > $this->perPage;
        $comments = $allComments->take($this->perPage);

        return view('livewire.dashboard.post.comment-section', [
            'comments' => $comments
        ]);
    }
}
