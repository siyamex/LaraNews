<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsDaily;
use App\Models\PageView;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');

        $viewsChart = AnalyticsDaily::where('date', '>=', now()->subDays($period - 1)->toDateString())
            ->whereNull('post_id')
            ->orderBy('date')
            ->get(['date', 'views', 'unique_visitors']);

        $topPosts = Post::published()
            ->with('translations')
            ->orderByDesc('views_count')
            ->take(20)
            ->get(['id', 'views_count', 'published_at']);

        $deviceStats = PageView::where('date', '>=', now()->subDays($period)->toDateString())
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get();

        $countryStats = PageView::where('date', '>=', now()->subDays($period)->toDateString())
            ->select('country_code', DB::raw('count(*) as count'))
            ->groupBy('country_code')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        $refererStats = PageView::where('date', '>=', now()->subDays($period)->toDateString())
            ->whereNotNull('referer')
            ->select('referer', DB::raw('count(*) as count'))
            ->groupBy('referer')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'viewsChart', 'topPosts', 'deviceStats', 'countryStats', 'refererStats', 'period'
        ));
    }

    public function posts(Request $request)
    {
        $posts = Post::published()
            ->with('translations')
            ->orderByDesc('views_count')
            ->paginate(30);
        return view('admin.analytics.posts', compact('posts'));
    }

    public function revenue(Request $request)
    {
        $revenueChart = collect(range(11, 0))->map(fn($i) => [
            'month'   => now()->subMonths($i)->format('M Y'),
            'revenue' => \App\Models\Payment::whereYear('created_at', now()->subMonths($i)->year)
                ->whereMonth('created_at', now()->subMonths($i)->month)
                ->where('status', 'completed')
                ->sum('amount'),
        ]);
        return view('admin.analytics.revenue', compact('revenueChart'));
    }
}
