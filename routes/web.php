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
use Illuminate\Http\Request;

// ============================================
// ✅ LIVEWIRE COMPONENTS (Namespace & Class Imports)
// ============================================
use App\Livewire\Post\CreatePost;
use App\Livewire\Post\Feed as VideoShow;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Profile\Show as ProfileShow;
use App\Livewire\Profile\Settings as ProfileSettings;
use App\Livewire\Profile\EditProfileModal;
use App\Livewire\Post\Edit as PostEdit;
use App\Livewire\Home\Home as LivewireHome;
use App\Livewire\Post\Download as PostDownload;
use App\Livewire\Post\Description as PostDescription;
use App\Livewire\Search\Search as SearchLivewire;
use App\Livewire\Category\Filter as CategoryFilter;
use App\Livewire\Category\EighteenPlus as EighteenPlusLivewire;
use App\Livewire\Auth\Login as LoginLivewire;
use App\Livewire\Auth\Register as RegisterLivewire;
use App\Livewire\Auth\ForgotPassword as ForgotPasswordLivewire;
use App\Livewire\Auth\ResetPassword as ResetPasswordLivewire;
use App\Livewire\Settings\Setting as SettingsLivewire;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\ChatList;
use App\Livewire\Notification\NotificationsPage;
use App\Livewire\Settings\NotificationSettings;
use App\Livewire\Dashboard\Post\SavedPosts;
use App\Livewire\Friend\Friend as FriendLivewire;
use App\Livewire\Friend\BlockedList;

// ============================================
// ✅ GUEST ROUTES (Livewire Auth System)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginLivewire::class)->name('login');
    Route::get('/register', RegisterLivewire::class)->name('register');
    Route::get('/forgot-password', ForgotPasswordLivewire::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordLivewire::class)->name('password.reset');

    // AJAX/Form POST Login support
    Route::post('/login', function (Request $request) {
        $loginValue = trim($request->login);
        $password = $request->password;
        
        $user = App\Models\User::where('email', $loginValue)
                    ->orWhere('phone', $loginValue)
                    ->first();
        
        if (!$user || !Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login' => 'Email/Phone or Password is incorrect.',
            ])->onlyInput('login');
        }
        
        Illuminate\Support\Facades\Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();
        
        return redirect()->intended('/');
    })->name('login.post');
});

// ============================================
// ✅ PUBLIC ROUTES
// ============================================
Route::get('/', LivewireHome::class)->name('home');
Route::get('/search', SearchLivewire::class)->name('search');
Route::get('/video/download/{post}', PostDownload::class)->name('video.download.page');
Route::get('/video/download/{post}/file', [VideoController::class, 'downloadFile'])->name('video.download.file');
Route::get('/post/{post}/info', PostDescription::class)->name('posts.description');
Route::get('/category/{category}', CategoryFilter::class)->name('category.filter');
Route::get('/18plus', EighteenPlusLivewire::class)->name('category.18plus');
Route::get('/settings', SettingsLivewire::class)->name('settings');

// Theme Switcher - AJAX
Route::post('/settings/theme', function (Request $request) {
    $theme = $request->input('theme');
    session()->put('theme', $theme);
    return response()->json(['success' => true]);
})->name('settings.theme');

// Video Streaming (Bunny CDN)
Route::get('/video/{path}', [VideoController::class, 'stream'])
    ->where('path', '.*')
    ->name('video.stream');

// Public Video
Route::get('/posts/{post}', VideoShow::class)->name('posts.show');

// ============================================
// ✅ AUTHENTICATED ROUTES
// ============================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Profile Edit & Settings (Must be ABOVE /profile/{user})
    Route::get('/profile/settings', ProfileSettings::class)->name('profile.settings');
    Route::get('/profile/edit', EditProfileModal::class)->name('profile.edit');
    
    Route::get('/settings/notifications', NotificationSettings::class)->name('settings.notifications');
    Route::get('/settings/blocked-users', BlockedList::class)->name('settings.blocked-users');

    // Friends
    Route::get('/friends', FriendLivewire::class)->name('friends');

    // Dashboard & Posts
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/post/create', CreatePost::class)->name('post.create.post');
    Route::get('/posts/{post}/edit', PostEdit::class)->name('posts.edit');
    Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('user.posts');
    Route::get('/saved', SavedPosts::class)->name('saved');
    
    // Post Actions
    Route::put('/posts/{post}', [PostCrudController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostCrudController::class, 'destroy'])->name('posts.destroy');
    
    // Media Uploads
    Route::post('/media/upload/image', [MediaUploadController::class, 'uploadImage'])->name('media.upload.image');
    Route::post('/media/upload/video', [MediaUploadController::class, 'uploadVideo'])->name('media.upload.video');
    Route::post('/media/upload/url', [MediaUploadController::class, 'processUrl'])->name('media.upload.url');
    Route::delete('/media/delete', [MediaUploadController::class, 'deleteFile'])->name('media.delete');
    
    // Interactions & Menus
    Route::post('/posts/{post}/react', [PostInteractionController::class, 'react'])->name('posts.react');
    Route::get('/posts/{post}/reactions', [PostInteractionController::class, 'getReactions'])->name('posts.reactions');
    Route::post('/posts/{post}/comments', [PostInteractionController::class, 'addComment'])->name('posts.comment');
    
    Route::patch('/posts/{post}/privacy', [PostMenuController::class, 'updatePrivacy'])->name('posts.privacy');
    Route::post('/posts/{post}/pin', [PostMenuController::class, 'togglePin'])->name('posts.pin');
    Route::post('/posts/{post}/save', [PostMenuController::class, 'save'])->name('posts.save');
    Route::post('/posts/{post}/hide', [PostMenuController::class, 'hide'])->name('posts.hide');
    Route::post('/posts/{post}/report', [PostMenuController::class, 'report'])->name('posts.report');
    Route::post('/user/{user}/block', [PostMenuController::class, 'blockUser'])->name('user.block');
    
    // Comments
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    
    // Notifications
    Route::get('/noti', NotificationsPage::class)->name('noti');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Chat Routes
    Route::get('/chat', ChatList::class)->name('chat.index');
    Route::get('/chat/{userId}', Chat::class)->name('chat.show');
    
    // Logout
    Route::post('/logout', function (Request $request) {
        Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// ✅ Dynamic Profile Show Route (Must be placed AFTER /profile/edit)
Route::get('/profile/{user}', ProfileShow::class)->name('profile.show');

// ============================================
// ✅ API ROUTES
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
