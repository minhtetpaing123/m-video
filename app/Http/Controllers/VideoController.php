<?php

namespace App\Http\Controllers;

class VideoController extends Controller
{
    public function stream($path)
    {
        // Clean path
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        
        $filePath = storage_path('app/public/' . $path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath) ?: 'video/mp4';
        $fileModified = filemtime($filePath);
        $etag = md5_file($filePath);
        $lastModified = gmdate('D, d M Y H:i:s', $fileModified) . ' GMT';
        $expires = gmdate('D, d M Y H:i:s', strtotime('+10 years')) . ' GMT';
        
        // Check ETag cache
        if (request()->hasHeader('If-None-Match')) {
            $ifNoneMatch = request()->header('If-None-Match');
            if ($ifNoneMatch === $etag) {
                return response('', 304, [
                    'Cache-Control' => 'public, max-age=315360000, immutable, must-revalidate',
                    'ETag' => $etag,
                    'Expires' => $expires,
                ]);
            }
        }
        
        // Check Last-Modified cache
        if (request()->hasHeader('If-Modified-Since')) {
            $ifModifiedSince = request()->header('If-Modified-Since');
            if (strtotime($ifModifiedSince) >= $fileModified) {
                return response('', 304, [
                    'Cache-Control' => 'public, max-age=315360000, immutable, must-revalidate',
                    'Last-Modified' => $lastModified,
                    'ETag' => $etag,
                    'Expires' => $expires,
                ]);
            }
        }
        
        // Handle range request
        if (request()->hasHeader('Range')) {
            return $this->streamPartial($filePath, $mimeType, $fileSize, $etag, $lastModified, $expires);
        }
        
        // Full file response
        $data = file_get_contents($filePath);
        
        return response($data, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Accept-Ranges' => 'bytes',
            // ============================================
            // FORCE CACHE - For Service Worker
            // ============================================
            'Cache-Control' => 'public, max-age=315360000, immutable, must-revalidate',
            'Pragma' => 'public',
            'Expires' => $expires,
            'Last-Modified' => $lastModified,
            'ETag' => $etag,
            // Service Worker အတွက်
            'Service-Worker-Allowed' => '/',
        ]);
    }
    
    private function streamPartial($filePath, $mimeType, $fileSize, $etag, $lastModified, $expires)
    {
        $range = request()->header('Range');
        $range = str_replace('bytes=', '', $range);
        $rangeParts = explode('-', $range);
        
        $start = (int) $rangeParts[0];
        $end = isset($rangeParts[1]) && $rangeParts[1] !== '' 
            ? (int) $rangeParts[1] 
            : $fileSize - 1;
        
        if ($start >= $fileSize || $end >= $fileSize || $start > $end) {
            return response('Requested range not satisfiable', 416, [
                'Content-Range' => "bytes */{$fileSize}",
            ]);
        }
        
        $length = $end - $start + 1;
        
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            abort(500);
        }
        
        fseek($handle, $start);
        $data = fread($handle, $length);
        fclose($handle);
        
        return response($data, 206, [
            'Content-Type' => $mimeType,
            'Content-Length' => $length,
            'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
            'Accept-Ranges' => 'bytes',
            // ============================================
            // FORCE CACHE - For Service Worker
            // ============================================
            'Cache-Control' => 'public, max-age=315360000, immutable, must-revalidate',
            'Pragma' => 'public',
            'Expires' => $expires,
            'Last-Modified' => $lastModified,
            'ETag' => $etag,
            'Service-Worker-Allowed' => '/',
        ]);
    }
}