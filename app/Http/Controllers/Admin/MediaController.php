<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFolder;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct(private readonly MediaService $mediaService) {}

    public function index(Request $request)
    {
        $folder   = $request->folder_id ? MediaFolder::findOrFail($request->folder_id) : null;
        $folders  = MediaFolder::where('parent_id', $request->folder_id)->orderBy('name')->get();
        $files    = Storage::disk('public')->files($folder?->path ?? 'uploads');

        return view('admin.media.index', compact('folders', 'files', 'folder'));
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,mp4,mp3']);

        $path    = $request->folder_path ?? 'uploads';
        $result  = $this->mediaService->uploadImage($request->file('file'), $path);

        return response()->json(['path' => $result['original'], 'sizes' => $result]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['path' => 'required|string']);

        Storage::disk('public')->delete($request->path);

        // Also delete variants
        foreach (['thumbnail', 'small', 'medium', 'large', 'og', 'webp'] as $suffix) {
            $variantPath = str_replace('.', "_{$suffix}.", $request->path);
            Storage::disk('public')->delete($variantPath);
        }

        return response()->json(['message' => 'Deleted.']);
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $parent  = $request->parent_id ? MediaFolder::findOrFail($request->parent_id) : null;
        $slug    = \Illuminate\Support\Str::slug($request->name);
        $path    = ($parent?->path ?? 'uploads') . '/' . $slug;

        Storage::disk('public')->makeDirectory($path);

        MediaFolder::create([
            'parent_id' => $request->parent_id,
            'name'      => $request->name,
            'slug'      => $slug,
            'path'      => $path,
        ]);

        return response()->json(['message' => 'Folder created.']);
    }
}
