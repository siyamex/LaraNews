<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Front;
use App\Http\Controllers\Install\InstallerController;
use Illuminate\Support\Facades\Route;

// ── Installation Wizard (blocked after first install) ─────────────────────────
Route::prefix('install')->name('install.')->middleware(\App\Http\Middleware\EnsureNotInstalled::class)->group(function () {
    Route::get('/',            [InstallerController::class, 'index'])->name('index');
    Route::get('/requirements',[InstallerController::class, 'requirements'])->name('requirements');
    Route::get('/database',    [InstallerController::class, 'database'])->name('database');
    Route::post('/database/test',  [InstallerController::class, 'testDatabase'])->name('database.test');
    Route::post('/database',   [InstallerController::class, 'setupDatabase'])->name('database.setup');
    Route::get('/site',        [InstallerController::class, 'site'])->name('site');
    Route::post('/site',       [InstallerController::class, 'setupSite'])->name('site.setup');
    Route::get('/admin',       [InstallerController::class, 'admin'])->name('admin');
    Route::post('/admin',      [InstallerController::class, 'runInstall'])->name('run');
    Route::get('/complete',    [InstallerController::class, 'complete'])->name('complete');
});

// Root redirect to default locale
Route::get('/', fn() => redirect()->route('home', ['locale' => app()->getLocale()]));

// Language switcher
Route::post('/language/{locale}', [Front\LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'dv|en');

// Locale-prefixed frontend routes
Route::prefix('{locale}')
    ->where(['locale' => 'dv|en'])
    ->middleware('setlocale')
    ->group(function () {

        Route::get('/', [Front\HomeController::class, 'index'])->name('home');

        // News
        Route::prefix('news')->group(function () {
            Route::get('/', [Front\NewsController::class, 'index'])->name('news.index');
            Route::get('/{slug}', [Front\NewsController::class, 'show'])->name('news.show');
        });

        // Category
        Route::get('/category/{slug}', [Front\CategoryController::class, 'show'])->name('category.show');
        Route::get('/tag/{slug}', [Front\TagController::class, 'show'])->name('tag.show');
        Route::get('/author/{username}', [Front\AuthorController::class, 'show'])->name('author.show');
        Route::get('/search', [Front\SearchController::class, 'index'])->name('search');
        Route::get('/live-search', [Front\SearchController::class, 'suggest'])->name('search.live');
        Route::get('/page/{slug}', [Front\PageController::class, 'show'])->name('page.show');

        // Newsletter
        Route::post('/newsletter/subscribe', [Front\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
        Route::get('/newsletter/confirm/{token}', [Front\NewsletterController::class, 'confirm'])->name('newsletter.confirm');
        Route::get('/newsletter/unsubscribe/{token}', [Front\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

        // Breaking news ticker (AJAX)
        Route::get('/breaking-news', [Front\NewsController::class, 'breaking'])->name('breaking.news');

        // Poll voting
        Route::post('/poll/{poll}/vote', [Front\PollController::class, 'vote'])->name('poll.vote');

        // Comments & Reactions
        Route::post('/comments', [Front\CommentController::class, 'store'])->name('comment.store');
        Route::post('/react/{post}', [Front\ReactionController::class, 'toggle'])->middleware('auth')->name('reaction.toggle');
        Route::post('/ads/{ad}/click', [Front\AdController::class, 'click'])->name('ad.click');

        // Authenticated actions
        Route::middleware('auth')->group(function () {
            Route::post('/bookmark/{post}', [Front\BookmarkController::class, 'toggle'])->name('bookmark.toggle');
            Route::post('/follow/{user}', [Front\FollowController::class, 'toggle'])->name('follow.toggle');
            Route::get('/bookmarks', [Front\BookmarkController::class, 'index'])->name('bookmarks');
            Route::get('/reading-history', [Front\ReadingHistoryController::class, 'index'])->name('reading-history');
            Route::get('/dashboard', fn(string $locale) => view('front.author.dashboard', compact('locale')))->name('author.dashboard');
        });

        // Membership
        Route::prefix('membership')->group(function () {
            Route::get('/', [Front\MembershipController::class, 'plans'])->name('membership.plans');
            Route::middleware('auth')->group(function () {
                Route::post('/subscribe/{plan}', [Front\MembershipController::class, 'subscribe'])->name('membership.subscribe');
                Route::get('/success', [Front\MembershipController::class, 'success'])->name('membership.success');
                Route::post('/cancel', [Front\MembershipController::class, 'cancelSubscription'])->name('membership.cancel-subscription');
            });
        });

        // Privacy & GDPR
        Route::prefix('privacy')->group(function () {
            Route::get('/', [Front\PrivacyController::class, 'settings'])->name('privacy.settings');
            Route::post('/consent', [Front\PrivacyController::class, 'updateConsent'])->name('privacy.consent');
            Route::middleware('auth')->group(function () {
                Route::get('/export', [Front\PrivacyController::class, 'exportData'])->name('privacy.export');
                Route::post('/delete', [Front\PrivacyController::class, 'requestDeletion'])->name('privacy.delete');
            });
        });
    });

// Social Auth
Route::prefix('auth')->group(function () {
    Route::get('{provider}', [Auth\SocialAuthController::class, 'redirect'])->name('social.auth');
    Route::get('{provider}/callback', [Auth\SocialAuthController::class, 'callback'])->name('social.callback');
});

// SEO / Feeds
Route::get('/sitemap.xml', [Front\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-news.xml', [Front\SitemapController::class, 'googleNews'])->name('sitemap.news');
Route::get('/feed/{locale?}', [Front\FeedController::class, 'rss'])->name('feed')->defaults('locale', 'dv');
Route::get('/manifest.json', [Front\PwaController::class, 'manifest'])->name('pwa.manifest');
Route::get('/sw.js', [Front\PwaController::class, 'serviceWorker'])->name('pwa.sw');

// Payment webhooks (no CSRF)
Route::post('/webhooks/stripe', [Front\PaymentController::class, 'stripeWebhook'])
    ->name('webhooks.stripe')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'can:access-admin'])
    ->group(function () {

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Posts
        Route::resource('posts', Admin\PostController::class);
        Route::post('posts/{post}/publish', [Admin\PostController::class, 'publish'])->name('posts.publish');
        Route::post('posts/{post}/clone', [Admin\PostController::class, 'clone'])->name('posts.clone');
        Route::post('posts/{post}/breaking', [Admin\PostController::class, 'toggleBreaking'])->name('posts.breaking');
        Route::get('posts/{post}/revisions', [Admin\PostController::class, 'revisions'])->name('posts.revisions');
        Route::post('posts/ai-generate', [Admin\AiController::class, 'generateArticle'])->name('posts.ai-generate');

        // Categories & Tags
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('tags', Admin\TagController::class);

        // Media
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [Admin\MediaController::class, 'index'])->name('index');
            Route::post('/upload', [Admin\MediaController::class, 'upload'])->name('upload');
            Route::delete('/{id}', [Admin\MediaController::class, 'destroy'])->name('destroy');
        });

        // Pages & Menus
        Route::resource('pages', Admin\PageController::class);
        Route::resource('menus', Admin\MenuController::class);
        Route::post('menus/{menu}/reorder', [Admin\MenuController::class, 'reorder'])->name('menus.reorder');

        // Comments
        Route::get('comments', [Admin\CommentController::class, 'index'])->name('comments.index');
        Route::post('comments/{comment}/approve', [Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::delete('comments/{comment}', [Admin\CommentController::class, 'destroy'])->name('comments.destroy');

        // Users & Roles
        Route::middleware('can:manage-users')->group(function () {
            Route::resource('users', Admin\UserController::class);
            Route::post('users/{user}/assign-role', [Admin\UserController::class, 'assignRole'])->name('users.assign-role');
            Route::resource('roles', Admin\RoleController::class);
        });

        // Ads
        Route::resource('ad-zones', Admin\AdZoneController::class);
        Route::resource('ads', Admin\AdController::class);

        // Membership
        Route::middleware('can:manage-memberships')->group(function () {
            Route::resource('membership-plans', Admin\MembershipPlanController::class);
            Route::resource('subscriptions', Admin\SubscriptionController::class)->only(['index', 'show', 'destroy']);
            Route::resource('coupons', Admin\CouponController::class);
        });

        // Polls & Quizzes
        Route::resource('polls', Admin\PollController::class);
        Route::resource('quizzes', Admin\QuizController::class);

        // RSS
        Route::resource('rss-sources', Admin\RssSourceController::class);
        Route::post('rss-sources/{source}/import', [Admin\RssSourceController::class, 'importNow'])->name('rss-sources.import');

        // Newsletter
        Route::prefix('newsletter')->name('newsletter.')->group(function () {
            Route::resource('lists', Admin\NewsletterListController::class);
            Route::get('subscribers', [Admin\NewsletterController::class, 'subscribers'])->name('subscribers');
            Route::get('export', [Admin\NewsletterController::class, 'export'])->name('export');
            Route::resource('campaigns', Admin\NewsletterCampaignController::class);
            Route::post('campaigns/{campaign}/send', [Admin\NewsletterCampaignController::class, 'send'])->name('campaigns.send');
        });

        // Themes
        Route::get('themes', [Admin\ThemeController::class, 'index'])->name('themes.index');
        Route::post('themes/{theme}/activate', [Admin\ThemeController::class, 'activate'])->name('themes.activate');
        Route::post('themes/{theme}/duplicate', [Admin\ThemeController::class, 'duplicate'])->name('themes.duplicate');
        Route::get('themes/{theme}/customize', [Admin\ThemeController::class, 'customize'])->name('themes.customize');
        Route::put('themes/{theme}/settings', [Admin\ThemeController::class, 'updateSettings'])->name('themes.settings');

        // Analytics
        Route::get('analytics', [Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/posts', [Admin\AnalyticsController::class, 'posts'])->name('analytics.posts');
        Route::get('analytics/revenue', [Admin\AnalyticsController::class, 'revenue'])->name('analytics.revenue');

        // AI
        Route::prefix('ai')->name('ai.')->group(function () {
            Route::get('/', [Admin\AiController::class, 'index'])->name('index');
            Route::post('generate-article', [Admin\AiController::class, 'generateArticle'])->name('generate-article');
            Route::post('translate/{post}', [Admin\AiController::class, 'translate'])->name('translate');
        });

        // SEO
        Route::get('seo', [Admin\SeoController::class, 'index'])->name('seo.index');
        Route::post('seo/generate-sitemaps', [Admin\SeoController::class, 'generateSitemaps'])->name('seo.generate-sitemaps');

        // Settings
        Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/{group}', [Admin\SettingsController::class, 'update'])->name('settings.update');

        // Logs
        Route::get('logs', [Admin\LogController::class, 'index'])->name('logs.index');

        // Font Manager
        Route::prefix('fonts')->name('fonts.')->group(function () {
            Route::get('/', [Admin\FontController::class, 'index'])->name('index');
            Route::post('/upload', [Admin\FontController::class, 'upload'])->name('upload');
            Route::post('/set-active', [Admin\FontController::class, 'setActive'])->name('set-active');
            Route::delete('/{slug}', [Admin\FontController::class, 'destroy'])->name('destroy');
        });

        // Server Monitor & Cache Controls
        Route::get('server', fn() => view('admin.server.index'))->name('server.index');

        // Bulk Post Actions
        Route::post('posts/bulk', [Admin\PostController::class, 'bulkAction'])->name('posts.bulk');
    });
