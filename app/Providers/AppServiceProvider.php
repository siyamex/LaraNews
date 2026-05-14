<?php

namespace App\Providers;

use App\Events\PostPublished;
use App\Listeners\NotifyFollowersOnPublish;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Comment;
use App\Models\MembershipPlan;
use App\Models\Post;
use App\Models\User;
use App\Policies\AdPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\MembershipPlanPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class         => PostPolicy::class,
        Category::class     => CategoryPolicy::class,
        Comment::class      => CommentPolicy::class,
        User::class         => UserPolicy::class,
        Ad::class           => AdPolicy::class,
        MembershipPlan::class => MembershipPlanPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

        // Observers
        User::observe(UserObserver::class);

        // Events → Listeners
        Event::listen(PostPublished::class, NotifyFollowersOnPublish::class);

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Super-admin gate bypass
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        // API rate limiters
        RateLimiter::for('api-public', function (Request $request) {
            // 120 requests per minute per IP for public endpoints
            return Limit::perMinute(120)->by($request->ip())->response(function () {
                return response()->json(['message' => 'Too many requests. Please slow down.'], 429);
            });
        });

        RateLimiter::for('api-auth', function (Request $request) {
            // 300 requests per minute per user for authenticated endpoints
            return $request->user()
                ? Limit::perMinute(300)->by($request->user()->id)
                : Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('api-search', function (Request $request) {
            // 30 searches per minute per IP (heavier queries)
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                return response()->json(['message' => 'Too many search requests. Please wait.'], 429);
            });
        });

        RateLimiter::for('api-write', function (Request $request) {
            // 20 writes per minute per user (comments, bookmarks)
            return $request->user()
                ? Limit::perMinute(20)->by($request->user()->id)
                : Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api-auth-login', function (Request $request) {
            // 10 login/register attempts per minute per IP
            return Limit::perMinute(10)->by($request->ip())->response(function () {
                return response()->json(['message' => 'Too many login attempts. Please try again later.'], 429);
            });
        });

        // Blade directives
        Blade::directive('rtl', function () {
            return "<?php if(app()->getLocale() === 'dv'): ?>";
        });
        Blade::directive('endrtl', function () {
            return '<?php endif; ?>';
        });
        Blade::directive('ltr', function () {
            return "<?php if(app()->getLocale() !== 'dv'): ?>";
        });
        Blade::directive('endltr', function () {
            return '<?php endif; ?>';
        });
    }
}
