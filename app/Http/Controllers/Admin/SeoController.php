<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'seo')->get()->keyBy('key');
        return view('admin.seo.index', compact('settings'));
    }

    public function generateSitemaps()
    {
        \Illuminate\Support\Facades\Artisan::call('sitemap:generate');
        return response()->json(['message' => 'Sitemaps regenerated.']);
    }
}
