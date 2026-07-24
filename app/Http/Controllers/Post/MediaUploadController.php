<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\BunnyStorageService;
use App\Jobs\ProcessVideoJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaUploadController extends Controller
{
    protected $bunny;

    public function __construct(BunnyStorageService $bunny)
    {
        $this->bunny = $bunny;
    }

    /**
     * Store a new post with text, image, or video (AJAX)
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== 🚀 Post Store Request (AJAX) ===');
            Log::info('📁 All files in request:', array_keys($request->allFiles()));
            
            // ============================================
            // VALIDATION
            // ============================================
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:100',
                'content' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'video' => 'nullable|mimes:mp4,mov,avi,wmv,flv,3gp,mkv|max:102400',
                'video_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'privacy' => 'required|in:public,friends,onlyme',
                'category' => 'required|string|max:50',
                'is_mature' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                Log::error('❌ Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // ============================================
            // CREATE NEW POST
            // ============================================
            $post = new Post();
            $post->user_id = Auth::id();
            $post->title = $request->title;
            $post->content = $request->content ?? '';
            $post->description = $request->description;
            $post->privacy = $request->privacy;
            $post->category = $request->category;
            $post->is_mature = $request->has('is_mature') ? true : false;
            $post->likes_count = 0;
            $post->comments_count = 0;
            $post->shares_count = 0;
            $post->video_status = 'pending';

            // ============================================
            // HANDLE IMAGE UPLOAD
            // ============================================
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "images/{$filename}";

                $result = $this->bunny->upload(
                    $file->getContent(),
                    $path,
                    $file->getMimeType()
                );

                if ($result['success']) {
                    $post->image = $path;
                    Log::info('✅ Image uploaded to Bunny:', ['path' => $path]);
                } else {
                    Log::error('❌ Image upload failed:', ['error' => $result['error']]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Image upload failed: ' . $result['error']
                    ], 500);
                }
            }

            // ============================================
            // HANDLE VIDEO UPLOAD + THUMBNAIL
            // ============================================
            if ($request->hasFile('video') && $request->file('video')->isValid()) {
                $file = $request->file('video');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "videos/{$filename}";

                Log::info('📤 Uploading video to Bunny:', ['path' => $path, 'size' => $file->getSize()]);

                $result = $this->bunny->upload(
                    $file->getContent(),
                    $path,
                    $file->getMimeType()
                );

                if ($result['success']) {
                    $post->video_path = $path;
                    $post->video_cdn_url = $result['cdn_url'];
                    $post->video_original = $file->getClientOriginalName();
                    $post->video_size = $file->getSize();
                    $post->video_status = 'uploaded';
                    
                    Log::info('✅ Video uploaded to Bunny successfully:', [
                        'path' => $path,
                        'cdn_url' => $result['cdn_url']
                    ]);

                    // ============================================
                    // ✅ HANDLE VIDEO THUMBNAIL (User selected)
                    // ============================================
                    if ($request->hasFile('video_thumbnail') && $request->file('video_thumbnail')->isValid()) {
                        $thumbFile = $request->file('video_thumbnail');
                        $thumbFilename = time() . '_thumb_' . Str::random(10) . '.' . $thumbFile->getClientOriginalExtension();
                        $thumbPath = "thumbnails/{$thumbFilename}";

                        Log::info('📸 User selected thumbnail:', [
                            'name' => $thumbFile->getClientOriginalName(),
                            'size' => $thumbFile->getSize(),
                            'mime' => $thumbFile->getMimeType()
                        ]);

                        $thumbResult = $this->bunny->upload(
                            $thumbFile->getContent(),
                            $thumbPath,
                            $thumbFile->getMimeType()
                        );

                        if ($thumbResult['success']) {
                            $post->video_thumbnail = $thumbPath;
                            Log::info('✅✅✅ User thumbnail uploaded to Bunny:', [
                                'path' => $thumbPath,
                                'cdn_url' => $thumbResult['cdn_url']
                            ]);
                        } else {
                            Log::error('❌ Thumbnail upload failed:', ['error' => $thumbResult['error']]);
                        }
                    } else {
                        Log::info('ℹ️ No user thumbnail selected, will use auto thumbnail from ProcessVideoJob');
                    }
                } else {
                    Log::error('❌ Video upload failed:', ['error' => $result['error']]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Video upload failed: ' . $result['error']
                    ], 500);
                }
            }

            // ============================================
            // 💾 SAVE POST TO DATABASE
            // ============================================
            $post->save();

            Log::info('✅ Post saved successfully to database:', [
                'post_id' => $post->id,
                'title' => $post->title,
                'video_path' => $post->video_path,
                'video_thumbnail' => $post->video_thumbnail,
                'video_status' => $post->video_status,
                'image_path' => $post->image,
            ]);

            // ============================================
            // DISPATCH BACKGROUND JOB FOR VIDEO PROCESSING
            // ============================================
            if ($post->video_path) {
                ProcessVideoJob::dispatch($post);
                Log::info('📤 Video processing job dispatched for post: ' . $post->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'post_id' => $post->id,
                'has_video' => !is_null($post->video_path),
                'has_image' => !is_null($post->image),
                'has_thumbnail' => !is_null($post->video_thumbnail),
                'video_status' => $post->video_status,
                'redirect_url' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error creating post: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image only (for AJAX preview)
     */
    public function uploadImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "temp/images/{$filename}";

                $result = $this->bunny->upload(
                    $file->getContent(),
                    $path,
                    $file->getMimeType()
                );

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'path' => $path,
                        'url' => $result['cdn_url'],
                        'filename' => $filename,
                        'message' => 'Image uploaded to Bunny successfully'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload to Bunny: ' . $result['error']
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file found'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload video only (for AJAX preview)
     */
    public function uploadVideo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'video' => 'required|mimes:mp4,mov,avi,wmv,flv,3gp,mkv|max:102400',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('video') && $request->file('video')->isValid()) {
                $file = $request->file('video');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = "temp/videos/{$filename}";

                $result = $this->bunny->upload(
                    $file->getContent(),
                    $path,
                    $file->getMimeType()
                );

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'path' => $path,
                        'url' => $result['cdn_url'],
                        'filename' => $filename,
                        'message' => 'Video uploaded to Bunny successfully'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload to Bunny: ' . $result['error']
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'No video file found'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Video upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete temporary file from Bunny
     */
    public function deleteFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'path' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid path'
                ], 422);
            }

            $path = $request->path;
            $path = str_replace('storage/', '', $path);
            
            $result = $this->bunny->delete($path);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted from Bunny successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found on Bunny'
            ], 404);

        } catch (\Exception $e) {
            Log::error('File delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }
}