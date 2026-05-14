<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index() { return view('admin.roles.index', ['roles' => Role::withCount('users')->get()]); }
    public function create() { return view('admin.roles.create', ['permissions' => Permission::all()]); }
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        if ($request->permissions) $role->givePermissionTo($request->permissions);
        return redirect()->route('admin.roles.index');
    }
    public function edit(Role $role) { return view('admin.roles.edit', ['role' => $role, 'permissions' => Permission::all()]); }
    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);
        return redirect()->route('admin.roles.index');
    }
    public function destroy(Role $role) { $role->delete(); return redirect()->route('admin.roles.index'); }
}
