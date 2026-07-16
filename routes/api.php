<?php

use App\Http\Controllers\Post\MediaUploadController;
use App\Http\Controllers\VideoController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}', [VideoController::class, 'show']);

// Get categories list (public)
Route::get('/categories', function () {
    return response()->json([
        'categories' => Post::getCategories()
    ]);
});

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Post/Media routes
    Route::post('/post/store', [MediaUploadController::class, 'store']);
    Route::post('/post/upload-image', [MediaUploadController::class, 'uploadImage']);
    Route::post('/post/upload-video', [MediaUploadController::class, 'uploadVideo']);
    Route::post('/post/process-url', [MediaUploadController::class, 'processUrl']);
    Route::delete('/post/delete-file', [MediaUploadController::class, 'deleteFile']);
    
    // Video CRUD
    Route::post('/videos/upload', [VideoController::class, 'upload']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);
    Route::get('/videos/bunny-files', [VideoController::class, 'listBunnyFiles']);
});

// ============================================
// WEB ROUTES (for redirects)
// ============================================
Route::post('/post/store', [MediaUploadController::class, 'store'])->name('post.store');