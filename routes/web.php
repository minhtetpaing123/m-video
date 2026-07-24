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
use Illuminate\Http\Request; // ✅ ထည့်

// ============================================
// ✅ LIVEWIRE (Namespace နှင့် Class Name အမှန်များ)
// ============================================
use App\Livewire\Post\CreatePost;
use App\Livewire\Post\Feed as VideoShow;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Profile\Show as ProfileShow;
use App\Livewire\Profile\Settings as ProfileSettings;
use App\Livewire\Post\Edit as PostEdit;
use App\Livewire\Home\Home as LivewireHome;
use App\Livewire\Post\Download as PostDownload;
use App\Livewire\Post\Description as PostDescription;
use App\Livewire\Search\Search as SearchLivewire;
use App\Livewire\Category\Filter as CategoryFilter;
use App\Livewire\Category\EighteenPlus as EighteenPlusLivewire;
use App\Livewire\Auth\Login as LoginLivewire;
use App\Livewire\Settings\Setting as SettingsLivewire; // ✅ အသစ်ထည့်

// ============================================
// ✅ PUBLIC ROUTES (အပေါ်ဆုံးမှာ)
// ============================================
Route::get('/', LivewireHome::class)->name('home');

// ✅ LOGIN - Livewire Route
Route::get('/login', LoginLivewire::class)->name('login');

// ✅ SEARCH - Livewire Route
Route::get('/search', SearchLivewire::class)->name('search');

// ✅ VIDEO DOWNLOAD - Livewire Route
Route::get('/video/download/{post}', PostDownload::class)->name('video.download.page');

// ✅ VIDEO DOWNLOAD FILE - Controller Route (ဒီဟာက file download လုပ်ဖို့ပါ)
Route::get('/video/download/{post}/file', [App\Http\Controllers\VideoController::class, 'downloadFile'])->name('video.download.file');

// ✅ POST DESCRIPTION - Livewire Route
Route::get('/post/{post}/info', PostDescription::class)->name('posts.description');

// ✅ CATEGORY FILTER - Livewire Route
Route::get('/category/{category}', CategoryFilter::class)->name('category.filter');

// ✅ 18+ - Livewire Route
Route::get('/18plus', EighteenPlusLivewire::class)->name('category.18plus');

// ✅ SETTINGS - Livewire Route
Route::get('/settings', SettingsLivewire::class)->name('settings');

// ✅ THEME UPDATE - AJAX Route (အသစ်ထည့်)
Route::post('/settings/theme', function (Request $request) {
    $theme = $request->input('theme');
    session()->put('theme', $theme);
    return response()->json(['success' => true]);
})->name('settings.theme');

// Video Streaming - Redirect to Bunny CDN
Route::get('/video/{path}', [VideoController::class, 'stream'])
    ->where('path', '.*')
    ->name('video.stream');

// ============================================
// ✅ PUBLIC LIVEWIRE ROUTES
// ============================================
Route::get('/posts/{post}', VideoShow::class)->name('posts.show');
Route::get('/profile/{user}', ProfileShow::class)->name('profile.show');

// ============================================
// ✅ LIVEWIRE AUTH ROUTES
// ============================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ============================================
    // ✅ PROFILE SETTINGS (Livewire)
    // ============================================
    Route::get('/profile/settings', ProfileSettings::class)->name('profile.settings');
    
    // ============================================
    // ✅ DASHBOARD & CREATE & EDIT
    // ============================================
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    
    // ✅ Livewire Create Post Route
    Route::get('/post/create', CreatePost::class)->name('post.create.post');

    Route::get('/posts/{post}/edit', PostEdit::class)->name('posts.edit');
    
    // ============================================
    // ✅ USER POSTS
    // ============================================
    Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('user.posts');
    
    // ============================================
    // ✅ POST ROUTES (CRUD)
    // ============================================
    // ❌ POST Store Route ကိုဖယ်လိုက်ပါ (Livewire ကိုသုံးတော့မယ်)
    // Route::post('/post/store', [MediaUploadController::class, 'store'])->name('posts.store');
    
    Route::put('/posts/{post}', [PostCrudController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostCrudController::class, 'destroy'])->name('posts.destroy');
    
    // Media Upload Routes (AJAX Preview အတွက်)
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
});

// ============================================
// API ROUTES
// ============================================
Route::prefix('api')->group(function () {
    
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::get('/categories', function () {
        return response()->json([
            'categories' => App\Models\Post::getCategories()
        ]);
    });
    
    Route::middleware(['auth'])->group(function () {
        // ❌ API Post Store Route ကိုလည်းဖယ်ပါ
        // Route::post('/post/store', [MediaUploadController::class, 'store']);
        Route::post('/post/upload-image', [MediaUploadController::class, 'uploadImage']);
        Route::post('/post/upload-video', [MediaUploadController::class, 'uploadVideo']);
        Route::post('/post/process-url', [MediaUploadController::class, 'processUrl']);
        Route::delete('/post/delete-file', [MediaUploadController::class, 'deleteFile']);
        
        Route::post('/posts/{post}/like', [ReactionController::class, 'store']);
        Route::get('/posts/{post}/reactions', [ReactionController::class, 'index']);
        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        
        Route::delete('/videos/{id}', [VideoController::class, 'destroy']);
        Route::get('/videos/bunny-files', [VideoController::class, 'listBunnyFiles']);
    });
});

// ============================================
// AUTH ROUTES
// ============================================
require __DIR__.'/auth.php';