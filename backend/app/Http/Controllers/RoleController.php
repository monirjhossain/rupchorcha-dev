<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // Show all roles and their permissions
    public function index()
    {
        $roles = User::select('role')->distinct()->pluck('role');
        $permissions = Permission::all();
        $rolePermissions = DB::table('role_permission')->get();
        return view('admin.users.roles', compact('roles', 'permissions', 'rolePermissions'));
    }

    // Assign permissions to a role
    public function update(Request $request)
    {
        $role = $request->input('role');
        $permissions = $request->input('permissions', []);
        DB::table('role_permission')->where('role', $role)->delete();
        foreach ($permissions as $pid) {
            DB::table('role_permission')->insert([
                'role' => $role,
                'permission_id' => $pid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return back()->with('success', 'Permissions updated for role: ' . $role);
    }
}
