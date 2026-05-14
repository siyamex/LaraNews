<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Auth (strict rate limit — login/register)
    Route::middleware('throttle:api-auth-login')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
    });

    // Public content (120/min per IP)
    Route::middleware('throttle:api-public')->group(function () {
        Route::get('/posts',                 [PostController::class, 'index']);
        Route::get('/posts/trending',        [PostController::class, 'trending']);
        Route::get('/posts/breaking',        [PostController::class, 'breaking']);
        Route::get('/posts/{slug}',          [PostController::class, 'show']);
        Route::get('/posts/{slug}/comments', [CommentController::class, 'index']);
        Route::get('/categories',            [CategoryController::class, 'index']);
    });

    // Search (30/min per IP — heavier queries)
    Route::middleware('throttle:api-search')->group(function () {
        Route::get('/search', SearchController::class);
    });

    // Newsletter
    Route::middleware('throttle:api-write')->group(function () {
        Route::post('/newsletter/subscribe',   [NewsletterController::class, 'subscribe']);
        Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);
    });

    // Authenticated routes (300/min per user)
    Route::middleware(['auth:sanctum', 'throttle:api-auth'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);

        // Profile
        Route::get('/profile',                 [UserController::class, 'profile']);
        Route::patch('/profile',               [UserController::class, 'updateProfile']);
        Route::post('/profile/password',       [UserController::class, 'changePassword']);
        Route::get('/notifications',           [UserController::class, 'notifications']);
        Route::post('/notifications/read-all', [UserController::class, 'markNotificationsRead']);

        // Bookmarks (write rate limit)
        Route::get('/bookmarks',            [BookmarkController::class, 'index']);
        Route::middleware('throttle:api-write')->group(function () {
            Route::post('/bookmarks',           [BookmarkController::class, 'store']);
            Route::delete('/bookmarks/{post}',  [BookmarkController::class, 'destroy']);
        });

        // Comments (write rate limit)
        Route::middleware('throttle:api-write')->group(function () {
            Route::post('/posts/{slug}/comments',  [CommentController::class, 'store']);
            Route::delete('/comments/{comment}',   [CommentController::class, 'destroy']);
        });
    });

    // API Documentation
    Route::get('/docs', function () {
        return response()->json([
            'name'    => config('app.name') . ' REST API',
            'version' => 'v1',
            'base_url'=> url('/api/v1'),
            'auth'    => [
                'type'        => 'Bearer Token (Laravel Sanctum)',
                'obtain_token'=> 'POST /api/v1/login',
            ],
            'rate_limits' => [
                'public'     => '120 requests/minute per IP',
                'search'     => '30 requests/minute per IP',
                'auth_login' => '10 requests/minute per IP',
                'write'      => '20 requests/minute per user',
                'authenticated' => '300 requests/minute per user',
            ],
            'endpoints' => [
                ['method' => 'POST', 'path' => '/register',                  'auth' => false, 'desc' => 'Register new user'],
                ['method' => 'POST', 'path' => '/login',                     'auth' => false, 'desc' => 'Login and receive token'],
                ['method' => 'POST', 'path' => '/logout',                    'auth' => true,  'desc' => 'Revoke current token'],
                ['method' => 'GET',  'path' => '/me',                        'auth' => true,  'desc' => 'Current user info'],
                ['method' => 'GET',  'path' => '/posts',                     'auth' => false, 'desc' => 'List posts (paginated). Params: locale, category, tag, search, page'],
                ['method' => 'GET',  'path' => '/posts/trending',            'auth' => false, 'desc' => 'Trending posts'],
                ['method' => 'GET',  'path' => '/posts/breaking',            'auth' => false, 'desc' => 'Breaking news posts'],
                ['method' => 'GET',  'path' => '/posts/{slug}',              'auth' => false, 'desc' => 'Single post with translations'],
                ['method' => 'GET',  'path' => '/posts/{slug}/comments',     'auth' => false, 'desc' => 'Comments for a post'],
                ['method' => 'POST', 'path' => '/posts/{slug}/comments',     'auth' => true,  'desc' => 'Create a comment'],
                ['method' => 'DELETE','path'=> '/comments/{id}',             'auth' => true,  'desc' => 'Delete own comment'],
                ['method' => 'GET',  'path' => '/categories',                'auth' => false, 'desc' => 'List categories'],
                ['method' => 'GET',  'path' => '/search',                    'auth' => false, 'desc' => 'Search posts. Params: q, locale'],
                ['method' => 'GET',  'path' => '/profile',                   'auth' => true,  'desc' => 'Get own profile'],
                ['method' => 'PATCH','path' => '/profile',                   'auth' => true,  'desc' => 'Update own profile'],
                ['method' => 'POST', 'path' => '/profile/password',          'auth' => true,  'desc' => 'Change password'],
                ['method' => 'GET',  'path' => '/notifications',             'auth' => true,  'desc' => 'List notifications'],
                ['method' => 'POST', 'path' => '/notifications/read-all',    'auth' => true,  'desc' => 'Mark all notifications read'],
                ['method' => 'GET',  'path' => '/bookmarks',                 'auth' => true,  'desc' => 'List bookmarks'],
                ['method' => 'POST', 'path' => '/bookmarks',                 'auth' => true,  'desc' => 'Add bookmark'],
                ['method' => 'DELETE','path'=> '/bookmarks/{postId}',        'auth' => true,  'desc' => 'Remove bookmark'],
                ['method' => 'POST', 'path' => '/newsletter/subscribe',      'auth' => false, 'desc' => 'Subscribe to newsletter'],
                ['method' => 'POST', 'path' => '/newsletter/unsubscribe',    'auth' => false, 'desc' => 'Unsubscribe from newsletter'],
            ],
        ]);
    })->name('api.docs');
});
