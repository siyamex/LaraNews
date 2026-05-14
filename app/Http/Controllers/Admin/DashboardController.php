<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_posts' => Post::count(),
                'published_posts' => Post::where('status', 'published')->count(),
                'pending_posts' => Post::where('status', 'pending')->count(),
                'total_users' => User::count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'active_subscriptions' => Subscription::where('status', 'active')->count(),
                'revenue_today' => Payment::whereDate('created_at', today())->where('status', 'completed')->sum('amount'),
                'revenue_month' => Payment::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount'),
                'pending_comments' => Comment::where('status', 'pending')->count(),
                'total_views_today' => \App\Models\PageView::whereDate('created_at', today())->count(),
                'total_views_month' => \App\Models\PageView::whereMonth('created_at', now()->month)->count(),
            ];
        });

        $recentPosts = Post::with(['translations', 'user', 'category.translations'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(8)->get();

        $recentPayments = Payment::with('user', 'subscription.plan')
            ->where('status', 'completed')
            ->latest()
            ->take(8)
            ->get();

        // Views chart (last 14 days)
        $viewsChart = \App\Models\AnalyticsDaily::where('date', '>=', now()->subDays(13)->toDateString())
            ->whereNull('post_id')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => ['date' => $r->date->format('M d'), 'views' => $r->views]);

        // Revenue chart (last 12 months)
        $revenueChart = collect(range(11, 0))->map(fn($i) => [
            'month' => now()->subMonths($i)->format('M'),
            'revenue' => Payment::whereYear('created_at', now()->subMonths($i)->year)
                ->whereMonth('created_at', now()->subMonths($i)->month)
                ->where('status', 'completed')
                ->sum('amount'),
        ]);

        // Top posts
        $topPosts = Post::published()
            ->with(['translations'])
            ->orderByDesc('views_count')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats', 'recentPosts', 'recentUsers', 'recentPayments',
            'viewsChart', 'revenueChart', 'topPosts'
        ));
    }
}
