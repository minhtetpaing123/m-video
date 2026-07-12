<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $ffmpegPath = '/data/data/com.termux/files/usr/bin/ffmpeg';

    public $tries = 3;
    public $backoff = 10;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        try {
            $videoPath = $this->post->video;
            
            if (!$videoPath) {
                Log::warning('No video found for post: ' . $this->post->id);
                return;
            }

            $fullPath = storage_path('app/public/' . $videoPath);
            
            if (!file_exists($fullPath)) {
                Log::error('Video file not found: ' . $fullPath);
                return;
            }

            Log::info('Processing video for post: ' . $this->post->id);

            // ============================================
            // GENERATE THUMBNAIL (ONLY IF NO MANUAL THUMBNAIL)
            // ============================================
            if (!$this->post->video_thumbnail) {
                $thumbnailPath = $this->generateThumbnail($videoPath);
                if ($thumbnailPath) {
                    $this->post->video_thumbnail = $thumbnailPath;
                    Log::info('Auto thumbnail generated for post: ' . $this->post->id);
                } else {
                    // Fallback if auto generation fails
                    $fallbackThumbnail = $this->generateFallbackThumbnail($videoPath);
                    if ($fallbackThumbnail) {
                        $this->post->video_thumbnail = $fallbackThumbnail;
                        Log::info('Fallback thumbnail generated for post: ' . $this->post->id);
                    }
                }
            } else {
                Log::info('Manual thumbnail exists, skipping auto generation for post: ' . $this->post->id);
            }

            // ============================================
            // GENERATE VIDEO QUALITIES (720p, 480p, 360p)
            // ============================================
            $baseName = pathinfo(basename($videoPath), PATHINFO_FILENAME);
            $qualities = $this->generateVideoQualities($videoPath, $baseName);
            if ($qualities) {
                if (isset($qualities['720p'])) $this->post->video_720 = $qualities['720p'];
                if (isset($qualities['480p'])) $this->post->video_480 = $qualities['480p'];
                if (isset($qualities['360p'])) $this->post->video_360 = $qualities['360p'];
                Log::info('Video qualities generated for post: ' . $this->post->id);
            }

            $this->post->save();
            
            Log::info('Video processing completed for post: ' . $this->post->id);

        } catch (\Exception $e) {
            Log::error('Video processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate video thumbnail using FFmpeg
     */
    private function generateThumbnail($videoPath)
    {
        try {
            $fullPath = storage_path('app/public/' . $videoPath);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            $thumbnailPath = str_replace('posts/videos/', 'posts/thumbnails/', $videoPath);
            $thumbnailPath = preg_replace('/\.[^.]+$/', '.jpg', $thumbnailPath);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            $thumbnailDir = dirname($thumbnailFullPath);
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Generate thumbnail from 1 second
            $command = $this->ffmpegPath . ' -i "' . $fullPath . '" -ss 00:00:01 -vframes 1 -vf "scale=640:360" -q:v 2 "' . $thumbnailFullPath . '" 2>&1';
            
            shell_exec($command);
            
            if (file_exists($thumbnailFullPath) && filesize($thumbnailFullPath) > 0) {
                return $thumbnailPath;
            }
            
            return null;

        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate fallback thumbnail (colored gradient with play icon)
     * No FFmpeg required!
     */
    private function generateFallbackThumbnail($videoPath)
    {
        try {
            $thumbnailPath = str_replace('posts/videos/', 'posts/thumbnails/', $videoPath);
            $thumbnailPath = preg_replace('/\.[^.]+$/', '.jpg', $thumbnailPath);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            $thumbnailDir = dirname($thumbnailFullPath);
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $width = 640;
            $height = 360;
            $image = imagecreatetruecolor($width, $height);
            
            $colors = [
                ['#667eea', '#764ba2'],
                ['#ff6b6b', '#ff8e53'],
                ['#00b09b', '#96c93d'],
                ['#4776E6', '#8E54E9'],
                ['#FF416C', '#FF4B2B'],
                ['#0f2027', '#2c5364'],
                ['#d53369', '#cbad6d'],
                ['#f7971e', '#ffd200'],
                ['#1a2980', '#26d0ce'],
                ['#8A2387', '#E94057'],
            ];
            
            $colorPair = $colors[array_rand($colors)];
            
            $r1 = hexdec(substr($colorPair[0], 1, 2));
            $g1 = hexdec(substr($colorPair[0], 3, 2));
            $b1 = hexdec(substr($colorPair[0], 5, 2));
            $r2 = hexdec(substr($colorPair[1], 1, 2));
            $g2 = hexdec(substr($colorPair[1], 3, 2));
            $b2 = hexdec(substr($colorPair[1], 5, 2));
            
            for ($i = 0; $i < $height; $i++) {
                $ratio = $i / $height;
                $r = $r1 + ($r2 - $r1) * $ratio;
                $g = $g1 + ($g2 - $g1) * $ratio;
                $b = $b1 + ($b2 - $b1) * $ratio;
                
                $color = imagecolorallocate($image, $r, $g, $b);
                imageline($image, 0, $i, $width, $i, $color);
            }
            
            $centerX = $width / 2;
            $centerY = $height / 2;
            $white = imagecolorallocate($image, 255, 255, 255);
            $shadow = imagecolorallocate($image, 0, 0, 0);
            
            // Shadow
            $points = [
                $centerX - 25 + 2, $centerY - 30 + 2,
                $centerX - 25 + 2, $centerY + 30 + 2,
                $centerX + 30 + 2, $centerY + 2
            ];
            imagefilledpolygon($image, $points, 3, $shadow);
            
            // Main play triangle
            $points = [
                $centerX - 25, $centerY - 30,
                $centerX - 25, $centerY + 30,
                $centerX + 30, $centerY
            ];
            imagefilledpolygon($image, $points, 3, $white);
            
            $text = 'M-VIDEO';
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $fontPath = public_path('fonts/arial.ttf');
            
            if (file_exists($fontPath)) {
                imagettftext($image, 20, 0, 20, $height - 20, $textColor, $fontPath, $text);
            } else {
                imagestring($image, 5, 20, $height - 30, $text, $textColor);
            }
            
            imagejpeg($image, $thumbnailFullPath, 80);
            imagedestroy($image);
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Fallback thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate multiple video qualities using FFmpeg
     */
    private function generateVideoQualities($videoPath, $baseName)
    {
        try {
            $fullPath = storage_path('app/public/' . $videoPath);
            
            if (!file_exists($fullPath)) {
                Log::error('Video file not found for quality generation: ' . $fullPath);
                return null;
            }

            $qualities = [
                '720p' => ['scale' => '1280:720', 'bitrate' => '2000k'],
                '480p' => ['scale' => '854:480', 'bitrate' => '1000k'],
                '360p' => ['scale' => '640:360', 'bitrate' => '600k'],
            ];

            $generated = [];

            foreach ($qualities as $key => $quality) {
                $outputFilename = $baseName . '_' . $key . '.mp4';
                $outputPath = 'posts/videos/' . $outputFilename;
                $outputFullPath = storage_path('app/public/' . $outputPath);

                if (file_exists($outputFullPath)) {
                    $generated[$key] = $outputPath;
                    Log::info('Quality already exists:', ['path' => $outputPath]);
                    continue;
                }

                $command = $this->ffmpegPath . ' -i "' . $fullPath . '" ' .
                           '-vf "scale=' . $quality['scale'] . '" ' .
                           '-c:v libx264 -crf 23 -preset medium ' .
                           '-b:v ' . $quality['bitrate'] . ' ' .
                           '-c:a aac -b:a 128k ' .
                           '-movflags +faststart ' .
                           '"' . $outputFullPath . '" 2>&1';

                Log::info('FFmpeg quality command:', ['command' => $command]);

                $output = shell_exec($command);

                if (file_exists($outputFullPath) && filesize($outputFullPath) > 0) {
                    $generated[$key] = $outputPath;
                    Log::info('Quality generated:', ['path' => $outputPath, 'label' => $key]);
                } else {
                    Log::error('Quality generation failed:', ['label' => $key, 'output' => $output]);
                }
            }

            return !empty($generated) ? $generated : null;

        } catch (\Exception $e) {
            Log::error('Video quality generation failed: ' . $e->getMessage());
            return null;
        }
    }
}