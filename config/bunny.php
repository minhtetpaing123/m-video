<?php

return [
    'api_key' => env('BUNNY_API_KEY'),
    'storage_zone' => env('BUNNY_STORAGE_ZONE', 'm-video'),
    'cdn_url' => env('BUNNY_CDN_URL', 'https://m-video.b-cdn.net'),
    
    // ✅ Optimization
    'video_quality' => 'auto',
    'use_hls' => env('BUNNY_USE_HLS', false),
    'cache_control' => 'public, max-age=31536000',
    'region' => env('BUNNY_REGION', 'sg'),
    
    // CDN Settings
    'cdn_pull_zone' => env('BUNNY_CDN_PULL_ZONE', 'm-video'),
    'storage_region' => env('BUNNY_STORAGE_REGION', 'sg'),
];