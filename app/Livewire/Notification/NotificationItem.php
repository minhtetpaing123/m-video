<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;
use App\Models\Comment;

class NotificationItem extends Component
{
    public Notification $notification;
    public $quickReplyText = '';
    public bool $isFollowing = false;

    public function mount(Notification $notification): void
    {
        $this->notification = $notification;
        
        // Follow Status ကို စစ်ဆေးခြင်း
        $senderId = $this->notification->from_user_id ?? $this->notification->data['sender_id'] ?? null;
        if ($senderId && auth()->check()) {
            $sender = User::find($senderId);
            if ($sender) {
                $this->isFollowing = auth()->user()->isFollowing($sender);
            }
        }
    }

    public function openNotification()
    {
        return $this->markAsRead();
    }

    public function markAsRead()
    {
        if (!$this->notification->is_read) {
            $this->notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            $this->dispatch('notificationCountUpdated');
        }

        if ($this->notification->action_url && !$this->notification->post_id) {
            return redirect()->to($this->notification->action_url);
        }

        $postId = $this->notification->post_id;
        $commentId = $this->notification->comment_id;

        if ($postId) {
            $params = [
                'open_post' => $postId,
            ];

            if ($commentId) {
                $params['comment_id'] = $commentId;
            }

            $hash = $commentId ? '#comment-' . $commentId : '#post-' . $postId;

            return redirect()->to(route('dashboard', $params) . $hash);
        }

        return redirect()->to('/dashboard');
    }

    // Direct Action အလိုက် Read အဖြစ် ပြောင်းပေးရန်အတွက် သီးသန့် helper method
    private function setAsReadWithoutRedirect(): void
    {
        if (!$this->notification->is_read) {
            $this->notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            $this->dispatch('notificationCountUpdated');
        }
    }

    // 🎯 Direct Action: Accept Friend Request (Fix: Settings Linked)
    public function acceptFriendRequest()
    {
        $senderId = $this->notification->from_user_id ?? $this->notification->data['sender_id'] ?? null;
        if ($senderId) {
            $sender = User::find($senderId);
            if ($sender && method_exists(auth()->user(), 'acceptFriendRequest')) {
                auth()->user()->acceptFriendRequest($sender);

                // 🟢 Sender ဘက်က Friend Request / Interaction Noti ဖွင့်ထားမှသာ DB ထဲ ပစ်ထည့်မည်
                if ($sender->notify_friend_requests ?? true) {
                    $newNoti = Notification::create([
                        'user_id' => $sender->id,
                        'from_user_id' => auth()->id(),
                        'type' => 'friend_request_accepted',
                        'content_snippet' => null,
                        'is_read' => false,
                        'group_count' => 1,
                    ]);

                    // 📡 Broadcast Event ခေါ်ယူခြင်း
                    $this->broadcastNotification($newNoti, $sender);
                }

                $this->dispatch('show-toast', type: 'success', message: 'Friend request accepted!');
            }
        }
        $this->setAsReadWithoutRedirect();
        $this->deleteNotification();
    }

    // 🎯 Direct Action: Decline / Delete Friend Request
    public function declineFriendRequest()
    {
        $this->deleteNotification();
        $this->dispatch('show-toast', type: 'info', message: 'Friend request removed.');
    }

    // 🎯 Direct Action: Toggle Follow / Follow Back (Fix: Settings Linked)
    public function toggleFollow()
    {
        $senderId = $this->notification->from_user_id ?? $this->notification->data['sender_id'] ?? null;
        if ($senderId) {
            $targetUser = User::find($senderId);
            if ($targetUser && method_exists(auth()->user(), 'toggleFollow')) {
                auth()->user()->toggleFollow($targetUser);
                $this->isFollowing = auth()->user()->isFollowing($targetUser);

                if ($this->isFollowing) {
                    // 🟢 Target User က Follow Noti ဖွင့်ထားမှသာ DB ထဲ ထည့်မည်
                    if ($targetUser->notify_follows ?? true) {
                        $newNoti = Notification::create([
                            'user_id' => $targetUser->id,
                            'from_user_id' => auth()->id(),
                            'type' => 'follow',
                            'content_snippet' => null,
                            'is_read' => false,
                            'group_count' => 1,
                        ]);

                        // 📡 Broadcast Event ခေါ်ယူခြင်း
                        $this->broadcastNotification($newNoti, $targetUser);
                    }

                    $this->dispatch('show-toast', type: 'success', message: 'Followed successfully!');
                } else {
                    $this->dispatch('show-toast', type: 'info', message: 'Unfollowed successfully.');
                }
            }
        }
        $this->setAsReadWithoutRedirect();
    }

    // 🎯 Direct Action: Quick Reply (Fix: User Settings & DND Hours Linked)
    public function sendQuickReply()
    {
        if (trim($this->quickReplyText) === '') {
            $this->dispatch('show-toast', type: 'error', message: 'Reply text cannot be empty!');
            return;
        }

        if ($this->notification->post_id) {
            try {
                $parentCommentId = $this->notification->comment_id ?? null;

                $newComment = Comment::create([
                    'post_id' => $this->notification->post_id,
                    'user_id' => auth()->id(),
                    'content' => $this->quickReplyText,
                    'parent_id' => $parentCommentId,
                    'comment_id' => $parentCommentId,
                ]);

                $targetUserId = $this->notification->from_user_id ?? $this->notification->data['sender_id'] ?? null;

                if ($targetUserId && $targetUserId !== auth()->id()) {
                    $targetUser = User::find($targetUserId);

                    // 🟢 Target User ရဲ့ Replies/Comments Noti Switch ဖွင့်ထားမှသာ Notification ဖန်တီးမည်
                    if ($targetUser && ($targetUser->notify_replies ?? true)) {

                        $groupKey = 'post_reply_' . $this->notification->post_id;

                        // မဖတ်ရသေးသော Grouped Notification ရှိမရှိ စစ်ဆေးခြင်း
                        $existingNotification = Notification::where('user_id', $targetUserId)
                            ->where('group_key', $groupKey)
                            ->where('is_read', false)
                            ->first();

                        if ($existingNotification) {
                            $existingNotification->update([
                                'from_user_id' => auth()->id(),
                                'comment_id' => $parentCommentId,
                                'reply_id' => $newComment->id,
                                'content_snippet' => $this->quickReplyText,
                                'group_count' => ($existingNotification->group_count ?? 1) + 1,
                                'updated_at' => now(),
                            ]);

                            $newNoti = $existingNotification;
                        } else {
                            $newNoti = Notification::create([
                                'user_id' => $targetUserId,
                                'from_user_id' => auth()->id(),
                                'post_id' => $this->notification->post_id,
                                'comment_id' => $parentCommentId,
                                'reply_id' => $newComment->id,
                                'type' => 'reply',
                                'content_snippet' => $this->quickReplyText,
                                'group_key' => $groupKey,
                                'group_count' => 1,
                                'is_read' => false,
                            ]);
                        }

                        // ⚡ Real-time Notification (Sound & Settings စစ်ဆေးပြီး Broadcast ပို့မည်)
                        $this->broadcastNotification($newNoti, $targetUser);
                    }
                }

                $this->quickReplyText = '';
                $this->setAsReadWithoutRedirect();

                $this->dispatch('show-toast', type: 'success', message: 'Reply sent successfully!');
            } catch (\Exception $e) {
                $this->dispatch('show-toast', type: 'error', message: 'Failed to send reply. Please try again.');
            }
        }
    }

    /**
     * ⚡ User Preferences & Quiet Hours (DND) စစ်ဆေးပြီး Real-time Broadcast ပြုလုပ်ပေးသည့် Helper Method
     */
    private function broadcastNotification(Notification $notification, ?User $targetUser = null): void
    {
        $shouldPlaySound = true;

        if ($targetUser) {
            // ၁။ User က Sound ပိတ်ထားပါက အသံ မမြည်စေရ
            if (!($targetUser->notify_sound ?? true)) {
                $shouldPlaySound = false;
            }

            // ၂။ Quiet Hours (DND - ညဘက်အိပ်ချိန်) ဖွင့်ထားပြီး လက်ရှိအချိန်သည် DND အချိန်အတွင်း ရောက်နေပါက အသံ မမြည်စေရ
            if ($shouldPlaySound && ($targetUser->quiet_hours_enabled ?? false)) {
                $start = $targetUser->quiet_hours_start;
                $end = $targetUser->quiet_hours_end;

                if ($start && $end) {
                    $now = now()->format('H:i');
                    // ဥပမာ - 22:00 မှ 07:00 အထိ စစ်ဆေးခြင်း
                    if ($start > $end) {
                        if ($now >= $start || $now <= $end) {
                            $shouldPlaySound = false;
                        }
                    } else {
                        if ($now >= $start && $now <= $end) {
                            $shouldPlaySound = false;
                        }
                    }
                }
            }
        }

        // Broadcast ပစ်ပေးခြင်း
        if (class_exists(\App\Events\NotificationSent::class)) {
            broadcast(new \App\Events\NotificationSent($notification, $shouldPlaySound))->toOthers();
        } elseif (class_exists(\App\Events\NewNotification::class)) {
            broadcast(new \App\Events\NewNotification($notification, $shouldPlaySound))->toOthers();
        } elseif (class_exists(\App\Events\NotificationCreated::class)) {
            broadcast(new \App\Events\NotificationCreated($notification, $shouldPlaySound))->toOthers();
        }
    }

    public function deleteNotification(): void
    {
        if ($this->notification) {
            $this->notification->delete();
            $this->dispatch('refreshNotifications');
            $this->dispatch('notificationCountUpdated');
            $this->dispatch('show-toast', type: 'info', message: 'Notification deleted.');
        }
    }

    public function render()
    {
        return view('livewire.notification.notification-item');
    }
}
