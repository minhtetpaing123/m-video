<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function stream($path)
    {
        $videoPath = storage_path('app/public/posts/videos/' . $path);
        
        if (!file_exists($videoPath)) {
            abort(404);
        }
        
        return response()->file($videoPath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Expires' => gmdate('D, d M Y H:i:s T', strtotime('+1 year')),
        ]);
    }
}