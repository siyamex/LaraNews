<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index() { return view('admin.menus.index', ['menus' => Menu::with('allItems')->get()]); }
    public function create() { return view('admin.menus.create'); }
    public function store(Request $request)
    {
        $menu = Menu::create(['name' => $request->name, 'location' => $request->location, 'locale' => $request->locale ?? 'dv', 'is_active' => true]);
        return redirect()->route('admin.menus.index')->with('success', 'Menu created.');
    }
    public function edit(Menu $menu) { $menu->load('allItems'); return view('admin.menus.edit', compact('menu')); }
    public function update(Request $request, Menu $menu) { $menu->update($request->only('name', 'location')); return redirect()->route('admin.menus.index'); }
    public function destroy(Menu $menu) { $menu->delete(); return redirect()->route('admin.menus.index'); }
    public function reorder(Request $request, Menu $menu)
    {
        foreach ($request->items ?? [] as $order => $item) {
            MenuItem::where('id', $item['id'])->update(['sort_order' => $order, 'parent_id' => $item['parent_id'] ?? null]);
        }
        return response()->json(['message' => 'Reordered.']);
    }
}
