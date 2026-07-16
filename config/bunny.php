<?php

return [
    'api_key' => env('BUNNY_API_KEY'),
    'storage_zone' => env('BUNNY_STORAGE_ZONE', 'm-video'),
    'cdn_url' => env('BUNNY_CDN_URL', 'https://m-video.b-cdn.net'),
];