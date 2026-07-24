<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        // Session ကနေ locale ကိုဖတ်ပါ
        $locale = Session::get('locale', Session::get('language', 'en'));
        
        // မြန်မာလိုဆိုရင် mm ထားပါ
        if (in_array($locale, ['mm', 'my'])) {
            $locale = 'mm';
        }
        
        // App locale ကို set လုပ်ပါ
        App::setLocale($locale);
        
        return $next($request);
    }
}