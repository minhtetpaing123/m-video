<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // 🔥 Cache ထဲမှာ ၂ မိနစ်စာ Online Status ကို မှတ်ထားမည်
            $expiresAt = now()->addMinutes(2);
            Cache::put('user-is-online-' . $user->id, true, $expiresAt);

            // မူရင်းအတိုင်း last_seen ကို update လုပ်မည်
            if (method_exists($user, 'updateLastSeen')) {
                $user->updateLastSeen();
            } else {
                $user->update(['last_seen' => now()]);
            }
        }

        return $next($request);
    }
}
