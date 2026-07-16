<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostCrudController;
use App\Http\Controllers\Post\PostInteractionController;
use App\Http\Controllers\Post\PostMenuController;
use App\Http\Controllers\Post\MediaUploadController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/video/download/{post}', [App\Http\Controllers\VideoController::class, 'downloadPage'])->name('video.download.page');
Route::get('/video/download/{post}/file', [App\Http\Controllers\VideoController::class, 'downloadFile'])->name('video.download.file');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts/{post}/info', [App\Http\Controllers\DescriptionController::class, 'show'])->name('posts.description');
Route::get('/category/{category}', [HomeController::class, 'index'])->name('category.filter');
Route::get('/18plus', [HomeController::class, 'index'])->name('category.18plus');

// Video Streaming - Redirect to Bunny CDN
Route::get('/video/{path}', [VideoController::class, 'stream'])
    ->where('path', '.*')
    ->name('video.stream');

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// ============================================
// AUTHENTICATED ROUTES (Web - CSRF Required)
// ============================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('user.posts');
    
    // ============================================
    // POST ROUTES - MediaUploadController (Web - CSRF Protected)
    // ============================================
    Route::post('/post/store', [MediaUploadController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostCrudController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostCrudController::class, 'destroy'])->name('posts.destroy');
    
    // Media Upload Routes (Web)
    Route::post('/media/upload/image', [MediaUploadController::class, 'uploadImage'])->name('media.upload.image');
    Route::post('/media/upload/video', [MediaUploadController::class, 'uploadVideo'])->name('media.upload.video');
    Route::post('/media/upload/url', [MediaUploadController::class, 'processUrl'])->name('media.upload.url');
    Route::delete('/media/delete', [MediaUploadController::class, 'deleteFile'])->name('media.delete');
    
    // Post Interactions
    Route::post('/posts/{post}/react', [PostInteractionController::class, 'react'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [PostInteractionController::class, 'getReactions'])->name('posts.reactions');
    Route::post('/posts/{post}/comments', [PostInteractionController::class, 'addComment'])->name('posts.comment');
    
    // Post Menu Features
    Route::patch('/posts/{post}/privacy', [PostMenuController::class, 'updatePrivacy'])->name('posts.privacy');
    Route::post('/posts/{post}/pin', [PostMenuController::class, 'togglePin'])->name('posts.pin');
    Route::post('/posts/{post}/save', [PostMenuController::class, 'save'])->name('posts.save');
    Route::post('/posts/{post}/hide', [PostMenuController::class, 'hide'])->name('posts.hide');
    Route::post('/posts/{post}/report', [PostMenuController::class, 'report'])->name('posts.report');
    Route::post('/user/{user}/block', [PostMenuController::class, 'blockUser'])->name('user.block');
    
    // Comment Routes
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    
    // Notification Routes
    Route::get('/noti', [NotificationController::class, 'index'])->name('noti');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// API ROUTES (No CSRF Required - For curl/Postman)
// ============================================

Route::prefix('api')->group(function () {
    
    // ============================================
    // PUBLIC API ROUTES (No auth required)
    // ============================================
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::get('/categories', function () {
        return response()->json([
            'categories' => App\Models\Post::getCategories()
        ]);
    });
    
    // ============================================
    // AUTHENTICATED API ROUTES (CSRF Exempt)
    // ============================================
    Route::middleware(['auth'])->group(function () {
        
        // Video Upload API (NO CSRF)
        Route::post('/post/store', [MediaUploadController::class, 'store']);
        Route::post('/post/upload-image', [MediaUploadController::class, 'uploadImage']);
        Route::post('/post/upload-video', [MediaUploadController::class, 'uploadVideo']);
        Route::post('/post/process-url', [MediaUploadController::class, 'processUrl']);
        Route::delete('/post/delete-file', [MediaUploadController::class, 'deleteFile']);
        
        // Post Interactions
        Route::post('/posts/{post}/like', [ReactionController::class, 'store']);
        Route::get('/posts/{post}/reactions', [ReactionController::class, 'index']);
        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        
        // Video CRUD
        Route::delete('/videos/{id}', [VideoController::class, 'destroy']);
        Route::get('/videos/bunny-files', [VideoController::class, 'listBunnyFiles']);
    });
});

// ============================================
// AUTH ROUTES
// ============================================

require __DIR__.'/auth.php';