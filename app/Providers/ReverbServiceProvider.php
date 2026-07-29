<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Reverb\Events\ConnectionClosed;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Events\UserStatusChanged;

class ReverbServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ConnectionClosed::class, function ($event) {
            // Reverb connection တွင် user_id ကို ရှာခြင်း
            $userId = $event->connection->userId ?? $event->connection->user_id ?? null;
            
            \Log::info('Reverb Connection Closed', ['userId' => $userId]);
            
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->is_online = false;
                    $user->last_seen_at = now();
                    $user->save();

                    // အခြား user များကို status ပြောင်းကြောင်း broadcast လုပ်ခြင်း
                    broadcast(new UserStatusChanged($user, false))->toOthers();
                }
            }
        });
    }

    public function register(): void
    {
        //
    }
}
