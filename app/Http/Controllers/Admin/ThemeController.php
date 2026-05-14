<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ThemeManager;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = ThemeManager::presets();
        $active = ThemeManager::active();

        return view('admin.themes.index', compact('themes', 'active'));
    }

    public function activate(string $theme)
    {
        if (! array_key_exists($theme, ThemeManager::presets())) {
            return back()->with('error', 'Unknown theme.');
        }

        Setting::set('active_theme', $theme, 'theme');
        Setting::flushGroup('theme');

        return back()->with('success', 'Theme activated successfully.');
    }

    public function customize(string $theme)
    {
        $presets = ThemeManager::presets();

        if (! array_key_exists($theme, $presets)) {
            abort(404);
        }

        $customs = Setting::get('theme_custom_' . $theme, [], 'theme') ?? [];
        $current = array_merge($presets[$theme], $customs);

        return view('admin.themes.customize', compact('theme', 'current', 'presets'));
    }

    public function updateSettings(Request $request, string $theme)
    {
        if (! array_key_exists($theme, ThemeManager::presets())) {
            abort(404);
        }

        $validated = $request->validate([
            'primary'       => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_dark'  => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_hover' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_light' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        Setting::set('theme_custom_' . $theme, $validated, 'theme');
        Setting::flushGroup('theme');

        return back()->with('success', 'Theme customized successfully.');
    }

    public function duplicate(string $theme)
    {
        return back()->with('error', 'Duplication is not supported for preset themes.');
    }
}
