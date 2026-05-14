<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $group    = $request->get('group', 'general');
        $settings = Setting::where('group', $group)->orderBy('key')->get();

        $groups = ['general', 'seo', 'social', 'email', 'payment', 'ads', 'api', 'widgets', 'security'];

        return view('admin.settings.index', compact('settings', 'group', 'groups'));
    }

    public function update(Request $request)
    {
        $group = $request->get('group', 'general');

        foreach ($request->except(['_token', 'group']) as $key => $value) {
            Setting::set($key, $value, $group);
        }

        Cache::forget('settings_' . $group);

        return redirect()->route('admin.settings.index', ['group' => $group])
            ->with('success', 'Settings saved.');
    }
}
