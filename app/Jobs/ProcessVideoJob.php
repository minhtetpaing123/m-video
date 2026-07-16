<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\BunnyStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $bunny;
    protected $ffmpegPath = '/data/data/com.termux/files/usr/bin/ffmpeg';

    public $tries = 3;
    public $backoff = 10;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle(BunnyStorageService $bunny)
    {
        $this->bunny = $bunny;

        try {
            if (!$this->post->video_path) {
                Log::warning('No video path found for post: ' . $this->post->id);
                return;
            }

            Log::info('Processing video from Bunny for post: ' . $this->post->id);

            $this->post->update(['video_status' => 'processing']);

            // ============================================
            // DOWNLOAD VIDEO FROM BUNNY
            // ============================================
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempVideoPath = $tempDir . '/video_' . $this->post->id . '_' . time() . '.mp4';
            
            $downloadResult = $this->bunny->download($this->post->video_path);
            
            if (!$downloadResult['success']) {
                throw new \Exception('Failed to download video from Bunny: ' . $downloadResult['error']);
            }

            file_put_contents($tempVideoPath, $downloadResult['content']);
            Log::info('Video downloaded from Bunny: ' . $this->post->video_path);

            // ============================================
            // GET VIDEO DURATION
            // ============================================
            $duration = $this->getVideoDuration($tempVideoPath);
            Log::info('Video duration: ' . $duration . ' seconds');

            // ============================================
            // GENERATE THUMBNAIL
            // ============================================
            $thumbnailUploaded = false;
            if (!$this->post->video_thumbnail) {
                $thumbnailResult = $this->generateThumbnail($tempVideoPath);
                if ($thumbnailResult) {
                    $thumbPath = "thumbnails/thumb_{$this->post->id}_" . time() . '.jpg';
                    $thumbContent = file_get_contents($thumbnailResult);
                    
                    $uploadResult = $this->bunny->upload($thumbContent, $thumbPath, 'image/jpeg');
                    
                    if ($uploadResult['success']) {
                        $this->post->video_thumbnail = $thumbPath;
                        $this->post->video_thumbnail_url = $uploadResult['cdn_url'];
                        Log::info('Auto thumbnail uploaded to Bunny for post: ' . $this->post->id);
                        $thumbnailUploaded = true;
                    }
                    
                    @unlink($thumbnailResult);
                }
            }

            // ============================================
            // ADD WATERMARK (12px) AND RE-UPLOAD
            // ============================================
            $watermarkedPath = $tempDir . '/watermarked_' . $this->post->id . '_' . time() . '.mp4';
            $watermarkAdded = $this->addWatermark($tempVideoPath, $watermarkedPath);
            
            if ($watermarkAdded && file_exists($watermarkedPath) && filesize($watermarkedPath) > 0) {
                // Upload watermarked video to Bunny
                $watermarkedContent = file_get_contents($watermarkedPath);
                $watermarkedFileName = 'watermarked_' . $this->post->id . '_' . time() . '.mp4';
                $watermarkedPathBunny = "videos/{$watermarkedFileName}";
                
                $uploadResult = $this->bunny->upload($watermarkedContent, $watermarkedPathBunny, 'video/mp4');
                
                if ($uploadResult['success']) {
                    // Update post with watermarked video URL
                    $this->post->video_cdn_url = $uploadResult['cdn_url'];
                    $this->post->video_path = $watermarkedPathBunny;
                    Log::info('Watermarked video uploaded to Bunny: ' . $watermarkedPathBunny);
                }
                
                @unlink($watermarkedPath);
            } else {
                Log::warning('Watermark addition failed, using original video');
            }

            // ============================================
            // UPDATE POST STATUS WITH DURATION
            // ============================================
            $this->post->video_duration = $duration;
            $this->post->video_status = 'completed';
            $this->post->save();
            
            // Clean up temp video
            @unlink($tempVideoPath);
            
            Log::info('Video processing completed for post: ' . $this->post->id, [
                'duration' => $duration,
                'thumbnail' => $thumbnailUploaded ? 'uploaded' : 'skipped',
                'watermark' => $watermarkAdded ? 'added (12px)' : 'skipped'
            ]);

        } catch (\Exception $e) {
            Log::error('Video processing failed: ' . $e->getMessage());
            $this->post->update(['video_status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Add watermark to video using FFmpeg (12px, Top Left Corner)
     */
    private function addWatermark($inputPath, $outputPath)
    {
        try {
            if (!file_exists($inputPath)) {
                Log::error('Input video not found for watermark: ' . $inputPath);
                return false;
            }

            $watermarkText = 'M-VIDEO';
            
            // Font Size 12px, Top Left Corner (x=10, y=10)
            $command = $this->ffmpegPath . ' -i "' . $inputPath . '" ' .
                       '-vf "drawtext=fontfile=/data/data/com.termux/files/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf:text=' . $watermarkText . ':fontcolor=white:fontsize=12:box=1:boxcolor=black@0.4:boxborderw=3:x=10:y=10" ' .
                       '-c:a copy "' . $outputPath . '" 2>&1';
            
            Log::info('FFmpeg watermark command: ' . $command);
            shell_exec($command);
            
            if (file_exists($outputPath) && filesize($outputPath) > 0) {
                Log::info('Watermark added successfully (12px): ' . $outputPath);
                return true;
            }
            
            return false;

        } catch (\Exception $e) {
            Log::error('Watermark addition failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate video thumbnail using FFmpeg
     */
    private function generateThumbnail($videoPath)
    {
        try {
            if (!file_exists($videoPath)) {
                Log::error('Video file not found for thumbnail: ' . $videoPath);
                return null;
            }

            $thumbnailPath = storage_path('app/temp/thumb_' . $this->post->id . '_' . time() . '.jpg');
            $thumbnailDir = dirname($thumbnailPath);
            
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Try 1 second
            $command = $this->ffmpegPath . ' -i "' . $videoPath . '" -ss 00:00:01 -vframes 1 -vf "scale=640:360" -q:v 2 "' . $thumbnailPath . '" 2>&1';
            
            Log::info('FFmpeg thumbnail command: ' . $command);
            shell_exec($command);
            
            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 0) {
                Log::info('Thumbnail generated: ' . $thumbnailPath);
                return $thumbnailPath;
            }
            
            // Try 5 seconds if 1 second fails
            $command = $this->ffmpegPath . ' -i "' . $videoPath . '" -ss 00:00:05 -vframes 1 -vf "scale=640:360" -q:v 2 "' . $thumbnailPath . '" 2>&1';
            shell_exec($command);
            
            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 0) {
                Log::info('Thumbnail generated (5s): ' . $thumbnailPath);
                return $thumbnailPath;
            }
            
            Log::error('Thumbnail generation failed, file not created');
            return null;

        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get video duration using FFmpeg
     */
    private function getVideoDuration($videoPath)
    {
        try {
            if (!file_exists($videoPath)) {
                Log::error('Video file not found for duration: ' . $videoPath);
                return null;
            }

            // Using FFmpeg command
            $command = $this->ffmpegPath . ' -i "' . $videoPath . '" 2>&1 | grep -oP "Duration: \K[0-9:]+"';
            $output = shell_exec($command);
            
            if ($output && preg_match('/(\d+):(\d+):(\d+)/', $output, $matches)) {
                $hours = (int) $matches[1];
                $minutes = (int) $matches[2];
                $seconds = (int) $matches[3];
                return ($hours * 3600) + ($minutes * 60) + $seconds;
            }
            
            return null;

        } catch (\Exception $e) {
            Log::error('Duration extraction failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate multiple video qualities using FFmpeg
     */
    private function generateVideoQualities($videoPath)
    {
        try {
            if (!file_exists($videoPath)) {
                Log::error('Video file not found for quality generation: ' . $videoPath);
                return null;
            }

            $qualities = [
                '720' => ['scale' => '1280:720', 'bitrate' => '2000k'],
                '480' => ['scale' => '854:480', 'bitrate' => '1000k'],
                '360' => ['scale' => '640:360', 'bitrate' => '600k'],
            ];

            $generated = [];
            $tempDir = storage_path('app/temp');

            foreach ($qualities as $key => $quality) {
                $tempPath = $tempDir . '/quality_' . $key . '_' . $this->post->id . '_' . time() . '.mp4';
                
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                $command = $this->ffmpegPath . ' -i "' . $videoPath . '" ' .
                           '-vf "scale=' . $quality['scale'] . '" ' .
                           '-c:v libx264 -crf 23 -preset medium ' .
                           '-b:v ' . $quality['bitrate'] . ' ' .
                           '-c:a aac -b:a 128k ' .
                           '-movflags +faststart ' .
                           '"' . $tempPath . '" 2>&1';

                Log::info('FFmpeg quality command for ' . $key . 'p: ' . $command);
                $output = shell_exec($command);

                if (file_exists($tempPath) && filesize($tempPath) > 0) {
                    $generated[$key] = $tempPath;
                    Log::info('Quality generated:', ['label' => $key . 'p', 'path' => $tempPath]);
                } else {
                    Log::error('Quality generation failed:', ['label' => $key . 'p', 'output' => $output]);
                }
            }

            return !empty($generated) ? $generated : null;

        } catch (\Exception $e) {
            Log::error('Video quality generation failed: ' . $e->getMessage());
            return null;
        }
    }
}