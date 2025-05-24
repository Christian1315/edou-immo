<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use WithFileUploads;

    public $users = [];
    public $roles = [];
    public $allPermissions = [];
    // USERS
    function refreshUsers()
    {
        $users = User::all();
        $this->users = $users;
    }

    // ROLES
    function refreshRoles()
    {
        $this->roles = Role::with(['permissions', 'users'])->get();

        $this->allPermissions = Permission::all();
        // dd($this->permissions);
    }

    function mount()
    {
        $title = 'Suppression d\'un rôle!';
        $text = "Êtes vous sûr de supprimer ce rôle?";
        confirmDelete($title, $text);
        
        // USERS
        $this->refreshUsers();

        // ROLES
        $this->refreshRoles("");
    }

    public function render()
    {
        return view('livewire.roles');
    }
}
