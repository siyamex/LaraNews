<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()
            ->with(['translations', 'children.translations'])
            ->withCount('posts')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return CategoryResource::collection($categories);
    }
}
