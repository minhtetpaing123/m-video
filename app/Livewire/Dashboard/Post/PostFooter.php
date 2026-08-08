<?php

namespace App\Livewire\Dashboard\Post;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;
use App\Models\Notification;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PostFooter extends Component
{
    public Post $post;
    public bool $isLiked = false;
    public ?string $userReaction = null;
    public int $likesCount = 0;
    public int $commentsCount = 0;
    public int $sharesCount = 0;
    public int $viewsCount = 0;
    public array $topReactions = [];
    
    public bool $showComments = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        $this->commentsCount = $post->comments_count ?? $post->comments()->count();
        
        // Database တွင် shares table ရှိပါက Unique User Count ကိုယူမည်၊ မဟုတ်ပါက post ရဲ့ shares_count ကိုယူမည်
        if (Schema::hasTable('shares')) {
            $this->sharesCount = DB::table('shares')->where('post_id', $post->id)->distinct('user_id')->count('user_id');
        } else {
            $this->sharesCount = $post->shares_count ?? 0;
        }

        $this->viewsCount = $post->views_count ?? 0;

        $this->refreshReactionData();
    }

    protected function refreshReactionData()
    {
        // Total Likes Count
        $this->likesCount = $this->post->reactions()->count();

        // Get Top 2 Most Popular Reaction Types
        $this->topReactions = $this->post->reactions()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(2)
            ->pluck('type')
            ->toArray();

        // Check Active User's Reaction
        if (auth()->check()) {
            $existingReaction = $this->post->reactions()->where('user_id', auth()->id())->first();
            if ($existingReaction) {
                $this->isLiked = true;
                $this->userReaction = $existingReaction->type;
            } else {
                $this->isLiked = false;
                $this->userReaction = null;
            }
        } else {
            $this->isLiked = false;
            $this->userReaction = null;
        }
    }

    #[On('toggleComments')]
    #[On('closeCommentsModal')]
    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    #[On('openCommentsFromNoti')]
    public function openCommentsFromNoti()
    {
        $this->showComments = true;
    }

    public function setReaction($type = 'like')
    {
        $this->react($type);
    }

    public function toggleLike()
    {
        $type = $this->userReaction ?? 'like';
        $this->react($type);
    }

    public function react($type)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $allowedTypes = ['like', 'love', 'care', 'haha', 'wow', 'sad', 'angry'];
        if (!in_array($type, $allowedTypes)) {
            $type = 'like';
        }

        $userId = auth()->id();
        $existingReaction = $this->post->reactions()->where('user_id', $userId)->first();

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // Remove Reaction
                $existingReaction->delete();

                Notification::where('user_id', $this->post->user_id)
                    ->where('from_user_id', $userId)
                    ->where('post_id', $this->post->id)
                    ->where('type', 'reaction')
                    ->delete();
            } else {
                // Update Reaction Type
                $existingReaction->update(['type' => $type]);
                $this->sendNotification('reaction', $type);
            }
        } else {
            // New Reaction
            $this->post->reactions()->create([
                'user_id' => $userId,
                'post_id' => $this->post->id,
                'comment_id' => null,
                'type' => $type
            ]);

            $this->sendNotification('reaction', $type);
        }

        // Database logic sync & reload component state
        if (Schema::hasColumn('posts', 'likes_count')) {
            $this->post->update(['likes_count' => $this->post->reactions()->count()]);
        }

        $this->refreshReactionData();
    }

    protected function sendNotification($actionType = 'reaction', $reactionType = null)
    {
        if ($this->post->user_id === auth()->id()) {
            return;
        }

        $notification = Notification::updateOrCreate(
            [
                'user_id'      => $this->post->user_id,
                'from_user_id' => auth()->id(),
                'post_id'      => $this->post->id,
                'type'         => $actionType,
            ],
            [
                'reaction_type' => $reactionType,
                'action_url'    => route('posts.show', $this->post->id),
                'is_read'       => false,
            ]
        );

        // 🟢 Post ပိုင်ရှင်ဆီ Reverb Broadcasting ဖြင့် တိုက်ရိုက် Notification ပို့မည်
        NotificationSent::dispatch($notification);
    }

    public function sharePost()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();

        // shares table ရှိလျှင် User တစ်ယောက်လျှင် ၁ ခါသာ Record ဆောက်မည်
        if (Schema::hasTable('shares')) {
            $alreadyShared = DB::table('shares')
                ->where('post_id', $this->post->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$alreadyShared) {
                DB::table('shares')->insert([
                    'post_id' => $this->post->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Unique Share Count ကို ပြန်တွက်မည်
                $this->sharesCount = DB::table('shares')->where('post_id', $this->post->id)->distinct('user_id')->count('user_id');

                if (Schema::hasColumn('posts', 'shares_count')) {
                    $this->post->update(['shares_count' => $this->sharesCount]);
                }

                $this->sendNotification('share');
            }
        } else {
            // shares table မရှိသေးပါကလည်း User Session / Cache ဖြင့် ၁ ခါပဲ တိုးအောင် ထိန်းထားမည်
            $sessionKey = 'shared_post_' . $this->post->id . '_' . $userId;
            
            if (!session()->has($sessionKey)) {
                session()->put($sessionKey, true);
                
                $this->sharesCount++;

                if (Schema::hasColumn('posts', 'shares_count')) {
                    $this->post->update(['shares_count' => $this->sharesCount]);
                }

                $this->sendNotification('share');
            }
        }

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
