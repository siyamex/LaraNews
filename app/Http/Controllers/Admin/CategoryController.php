<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['translations', 'parent.translations'])
            ->withCount('posts')
            ->orderBy('order')
            ->paginate(30);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::with('translations')->whereNull('parent_id')->active()->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'translations.dv.name' => 'required|string|max:255',
            'translations.en.name' => 'required|string|max:255',
            'color'                => 'nullable|string|max:20',
        ]);

        $category = Category::create([
            'parent_id'  => $request->parent_id,
            'color'      => $request->color,
            'icon'       => $request->icon,
            'cover_image'=> $request->cover_image,
            'is_active'  => $request->boolean('is_active', true),
            'is_featured'=> $request->boolean('is_featured'),
            'order'      => $request->order ?? 0,
        ]);

        foreach (['dv', 'en'] as $locale) {
            if ($name = $request->input("translations.{$locale}.name")) {
                $category->translations()->create([
                    'locale' => $locale,
                    'name'   => $name,
                    'slug'   => Str::slug($name),
                    'description' => $request->input("translations.{$locale}.description"),
                    'meta_title'  => $request->input("translations.{$locale}.meta_title"),
                    'meta_description' => $request->input("translations.{$locale}.meta_description"),
                ]);
            }
        }

        Cache::forget('home_category_sections_dv');
        Cache::forget('home_category_sections_en');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        $category->load('translations');
        $parents = Category::with('translations')->whereNull('parent_id')
            ->where('id', '!=', $category->id)->active()->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'parent_id'   => $request->parent_id,
            'color'       => $request->color,
            'icon'        => $request->icon,
            'cover_image' => $request->cover_image,
            'is_active'   => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'show_in_menu'=> $request->boolean('show_in_menu', true),
            'order'       => $request->order ?? 0,
        ]);

        foreach (['dv', 'en'] as $locale) {
            if ($name = $request->input("translations.{$locale}.name")) {
                $category->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name'        => $name,
                        'slug'        => Str::slug($name),
                        'description' => $request->input("translations.{$locale}.description"),
                        'meta_title'  => $request->input("translations.{$locale}.meta_title"),
                        'meta_description' => $request->input("translations.{$locale}.meta_description"),
                    ]
                );
            }
        }

        Cache::forget('home_category_sections_dv');
        Cache::forget('home_category_sections_en');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count()) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
