<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name'=> 'Tino Raholison',
            'email'=>'tinoraholison@gmail.com',
        ]);
        User::factory()->create([
            'name'=> 'Utilisateur',
            'email'=>'logementatoutprix@gmail.com',
        ]);
        $role = Role::create(['name'=>'Admin']);
        $user->assignRole($role);
    }
}
