<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BunnyStorageService
{
    protected $apiKey;
    protected $storageZone;
    protected $baseUrl;
    protected $cdnUrl;

    public function __construct()
    {
        $this->apiKey = config('bunny.api_key');
        $this->storageZone = config('bunny.storage_zone');
        $this->baseUrl = "https://storage.bunnycdn.com/{$this->storageZone}";
        $this->cdnUrl = config('bunny.cdn_url');
    }

    // ============================================
    // C - CREATE (Upload)
    // ============================================
    public function upload($fileContent, $path, $contentType = null)
    {
        $url = "{$this->baseUrl}/{$path}";
        
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey,
            'Content-Type' => $contentType ?? 'application/octet-stream'
        ])->withBody($fileContent, $contentType ?? 'application/octet-stream')
          ->put($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'path' => $path,
                'cdn_url' => "{$this->cdnUrl}/{$path}",
                'storage_url' => $url
            ];
        }

        Log::error('Bunny Upload Failed', [
            'path' => $path,
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'success' => false,
            'error' => $response->body()
        ];
    }

    // ============================================
    // R - READ (List / Get Info)
    // ============================================
    public function listFiles($directory = '')
    {
        $url = "{$this->baseUrl}/{$directory}";
        
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey
        ])->get($url);

        if ($response->successful()) {
            $files = $response->json();
            return [
                'success' => true,
                'files' => array_map(function($file) {
                    return [
                        'name' => $file['ObjectName'] ?? null,
                        'size' => $file['Size'] ?? 0,
                        'last_modified' => $file['LastModified'] ?? null,
                        'is_directory' => $file['IsDirectory'] ?? false,
                        'cdn_url' => $file['ObjectName'] ? "{$this->cdnUrl}/{$file['ObjectName']}" : null
                    ];
                }, $files ?? [])
            ];
        }

        return ['success' => false, 'error' => $response->body()];
    }

    public function getFileInfo($path)
    {
        $url = "{$this->baseUrl}/{$path}";
        
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey
        ])->head($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'path' => $path,
                'size' => $response->header('content-length'),
                'content_type' => $response->header('content-type'),
                'last_modified' => $response->header('last-modified'),
                'cdn_url' => "{$this->cdnUrl}/{$path}"
            ];
        }

        return ['success' => false, 'error' => $response->body()];
    }

    // ============================================
    // D - DELETE
    // ============================================
    public function delete($path)
    {
        $url = "{$this->baseUrl}/{$path}";
        
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey
        ])->delete($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'path' => $path,
                'message' => 'File deleted successfully'
            ];
        }

        Log::error('Bunny Delete Failed', [
            'path' => $path,
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'success' => false,
            'error' => $response->body()
        ];
    }

    public function download($path)
    {
        $url = "{$this->baseUrl}/{$path}";
        
        $response = Http::withHeaders([
            'AccessKey' => $this->apiKey
        ])->get($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'content' => $response->body(),
                'path' => $path,
                'size' => strlen($response->body())
            ];
        }

        Log::error('Bunny Download Failed', [
            'path' => $path,
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return [
            'success' => false,
            'error' => $response->body()
        ];
    }

    public function deleteMultiple($paths)
    {
        $results = [];
        foreach ($paths as $path) {
            $results[] = $this->delete($path);
        }
        return $results;
    }
}