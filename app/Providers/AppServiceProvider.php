<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cloudflare Tunnel သို့မဟုတ် Proxy ကနေ HTTPS နာမည်နဲ့ Request ဝင်လာပါက
        // Asset/Route လင့်ခ်များအားလုံးကို HTTPS အလိုအလျောက် Force ပြောင်းပေးခြင်း
        if (request()->hasHeader('X-Forwarded-Proto') && request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
