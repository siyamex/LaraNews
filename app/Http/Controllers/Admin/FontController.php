<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FontController extends Controller
{
    public function index()
    {
        $fonts = $this->getFonts();
        return view('admin.fonts.index', compact('fonts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'font_file' => 'required|file|mimes:ttf,otf,woff,woff2|max:5120',
            'font_name' => 'required|string|max:100',
            'font_weight' => 'required|in:100,200,300,400,500,600,700,800,900',
            'font_style' => 'required|in:normal,italic',
        ]);

        $file     = $request->file('font_file');
        $ext      = $file->getClientOriginalExtension();
        $name     = Str::slug($request->font_name);
        $filename = "fonts/{$name}-{$request->font_weight}-{$request->font_style}.{$ext}";

        Storage::disk('public')->putFileAs('fonts', $file, basename($filename));

        $fonts   = $this->getFonts();
        $fonts[] = [
            'name'    => $request->font_name,
            'slug'    => $name,
            'file'    => $filename,
            'weight'  => $request->font_weight,
            'style'   => $request->font_style,
            'format'  => $this->format($ext),
            'uploaded_at' => now()->toIso8601String(),
        ];

        Setting::updateOrCreate(
            ['key' => 'custom_fonts', 'group' => 'fonts'],
            ['value' => json_encode($fonts)]
        );

        return redirect()->route('admin.fonts.index')->with('success', 'Font uploaded successfully.');
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'heading_font' => 'nullable|string',
            'body_font'    => 'nullable|string',
        ]);

        Setting::updateOrCreate(['key' => 'heading_font', 'group' => 'fonts'], ['value' => $request->heading_font]);
        Setting::updateOrCreate(['key' => 'body_font',    'group' => 'fonts'], ['value' => $request->body_font]);

        return redirect()->route('admin.fonts.index')->with('success', 'Active fonts updated.');
    }

    public function destroy(string $slug)
    {
        $fonts = collect($this->getFonts());
        $font  = $fonts->firstWhere('slug', $slug);

        if ($font) {
            Storage::disk('public')->delete($font['file']);
            $remaining = $fonts->filter(fn($f) => $f['slug'] !== $slug)->values()->toArray();
            Setting::updateOrCreate(
                ['key' => 'custom_fonts', 'group' => 'fonts'],
                ['value' => json_encode($remaining)]
            );
        }

        return redirect()->route('admin.fonts.index')->with('success', 'Font deleted.');
    }

    private function getFonts(): array
    {
        $raw = Setting::where('key', 'custom_fonts')->value('value');
        return $raw ? json_decode($raw, true) : [];
    }

    private function format(string $ext): string
    {
        return match($ext) {
            'ttf'   => 'truetype',
            'otf'   => 'opentype',
            'woff'  => 'woff',
            'woff2' => 'woff2',
            default => $ext,
        };
    }
}
