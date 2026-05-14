<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(Request $request, string $locale, string $username)
    {
        $author = User::where('username', $username)->firstOrFail();

        $posts = Post::published()
            ->where('user_id', $author->id)
            ->with(['translations' => fn($q) => $q->where('locale', $locale), 'category.translations'])
            ->latest('published_at')
            ->paginate(12);

        $seo = [
            'title'       => $author->name . ' — ' . config('app.name'),
            'description' => $author->bio,
            'og_type'     => 'profile',
        ];

        return view('front.author.show', compact('author', 'posts', 'seo', 'locale'));
    }
}
