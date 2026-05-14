<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\TagTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::with('translations')->withCount('posts')->orderByDesc('posts_count')->paginate(30);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:100',
            'name_dv' => 'nullable|string|max:100',
        ]);

        $tag = Tag::create(['slug' => Str::slug($request->name_en)]);

        foreach (['dv', 'en'] as $locale) {
            if ($name = $request->input("name_{$locale}")) {
                $tag->translations()->create(['locale' => $locale, 'name' => $name, 'slug' => Str::slug($name)]);
            }
        }

        return redirect()->route('admin.tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        $tag->load('translations');
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name_en' => 'required|string|max:100',
            'name_dv' => 'nullable|string|max:100',
        ]);

        $tag->update(['slug' => Str::slug($request->name_en)]);

        foreach (['dv', 'en'] as $locale) {
            if ($name = $request->input("name_{$locale}")) {
                $tag->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['name' => $name, 'slug' => Str::slug($name)]
                );
            }
        }

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted.');
    }
}
