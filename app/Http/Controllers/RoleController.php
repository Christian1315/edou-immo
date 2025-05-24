<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    function index()
    {
        return view("admin.roles");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        $role->syncPermissions($request->permissions);

        alert()->success("Succès", "Rôle ajouté avec succès!");
        return redirect()->back();
    }

    public function retrieve($id)
    {
        $role = Role::with(['permissions', 'users'])->find($id);

        $data = [
            "role" => $role,
            "all_permissions" => Permission::all(),
        ];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array'
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        alert()->success("Succès", "Rôle mis à jour avec succès!");
        return redirect()->back();
    }

    public function affectToUser(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $user = User::findOrFail($request->user);

        if ($user->hasRole($role->name)) {
            alert()->error("Erreure", "Rôle déjà affecté à cet utilisateur!");
            return redirect()->back();
        }

        $user->assignRole($role->name);
        alert()->success("Succès", "Rôle affecté avec succès!");
        return redirect()->back();
    }

    public function removeFromUser(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $user = User::findOrFail($request->user);

        if (!$user->hasRole($role->name)) {
            alert()->error("Erreure", "Cet utilisateur ne dispose pas de ce rôle!");
            return redirect()->back();
        }

        $user->removeRole($role);
        alert()->success("Succès", "Rôle retiré avec succès!");
        return redirect()->back();
    }

    public function _destroy($id)
    {
        $role = Role::findOrFail(deCrypId($id));

        if ($role->users()->count() > 0) {
            alert()->error("Erreur", "Ce rôle est déjà attribué à des utilisateurs");
            return redirect()->back();
        }

        $role->delete();

        alert()->success("Succès", "Rôle supprimé avec succès!");
        return redirect()->back();
    }
}
