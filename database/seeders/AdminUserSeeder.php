<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = ModelsUser::find(1);
        // Attribuer le rôle Super Administrateur
        $admin->assignRole('Super Administrateur');
    }
}
