<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadingHistoryController extends Controller
{
    public function index(Request $request, string $locale)
    {
        $history = auth()->user()->readingHistory()
            ->with(['post.translations', 'post.category.translations'])
            ->latest()
            ->paginate(20);

        return view('front.user.reading-history', compact('history', 'locale'));
    }
}
