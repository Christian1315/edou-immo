<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

use Spatie\Permission\Models\Role;

class Setting extends Component
{
    use WithFileUploads;

    public $users = [];

    public $agencies = [];

    public $allRoles = [];

    function mount()
    {
        // AGENCIES
        $this->refreshAgencies();

        // USERS
        $this->refreshUsers();

        // ROLES
        $this->refreshRoles();
    }

    // AGENCIES
    function refreshAgencies()
    {
        $agencies = Agency::all();
        $this->agencies = $agencies;
    }

    // USERS
    function refreshUsers()
    {
        $title = 'Suppression de l\'utilisateur!';
        $text = "Voulez-vous vraiment supprimer cet utilisateur?";
        confirmDelete($title, $text);

        $users = User::where("visible",1)->get();
        $this->users = $users;
    }

    // ROLES
    function refreshRoles()
    {
        $roles = Role::all();
        $this->allRoles = $roles;
    }
    
    public function render()
    {
        return view('livewire.setting');
    }
}
