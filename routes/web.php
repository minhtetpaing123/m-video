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
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('home');
});

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    
    // User Posts
    Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('user.posts');
    
    // Post CRUD
    Route::post('/posts', [MediaUploadController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::put('/posts/{post}', [PostCrudController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostCrudController::class, 'destroy'])->name('posts.destroy');
    
    // Media Upload Routes
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
    
    // ===== Notification Routes =====
    Route::get('/noti', [NotificationController::class, 'index'])->name('noti');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/posts/{post}/like', [ReactionController::class, 'store']);
    Route::get('/posts/{post}/reactions', [ReactionController::class, 'index']);
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
});
Route::get('/debug-notifications', function() {
    $notifications = App\Models\Notification::with('fromUser')->latest()->get();
    
    $html = '<h1>Notifications Debug</h1>';
    $html .= '<table border="1" cellpadding="8">';
    $html .= '<tr><th>ID</th><th>Type</th><th>Post ID</th><th>From User</th><th>Data</th><th>Post URL</th></tr>';
    
    foreach($notifications as $n) {
        $postUrl = $n->post_id ? url("/posts/{$n->post_id}") : 'No post ID';
        $html .= "<tr>";
        $html .= "<td>{$n->id}</td>";
        $html .= "<td>{$n->type}</td>";
        $html .= "<td>{$n->post_id}</td>";
        $html .= "<td>{$n->fromUser->name}</td>";
        $html .= "<td>" . print_r($n->data, true) . "</td>";
        $html .= "<td><a href='{$postUrl}' target='_blank'>Go</a></td>";
        $html .= "</tr>";
    }
    
    $html .= '</table>';
    
    return $html;
});
Route::get('/check-posts', function() {
    $postIds = [104, 61, 85, 105, 106];
    $results = [];
    
    foreach($postIds as $id) {
        $post = App\Models\Post::find($id);
        $results[] = [
            'post_id' => $id,
            'exists' => $post ? 'Yes' : 'No',
            'url' => $post ? url("/posts/{$id}") : null
        ];
    }
    
    return $results;
});
require __DIR__.'/auth.php';