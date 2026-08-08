<?php

namespace App\Livewire\Friend;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FriendPost extends Component
{
    public function render()
    {
        $currentUserId = Auth::id();

        // 1. မိမိ Follow လုပ်ထားသော User ID များကို follows table ထဲမှ ဆွဲထုတ်မည်
        // (follower_id က မိမိ ID ဖြစ်ပြီး user_id က မိမိ follow လုပ်ထားသူ၏ ID ဖြစ်ပါသည်)
        $followingIds = DB::table('follows')
            ->where('follower_id', $currentUserId)
            ->pluck('user_id')
            ->toArray();

        // 2. မိမိ Follow လုပ်ထားသော သူများ၏ Posts များကို ဆွဲထုတ်မည်
        $posts = Post::with('user')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        return view('livewire.friend.friend-post', [
            'posts' => $posts
        ]);
    }
}
