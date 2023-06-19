<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'SUPER_ADMIN',
            'guard_name' => 'web',
        ]);
        
        Role::create([
            'name' => 'ADMIN',
            'guard_name' => 'web',
        ]);

        User::create([
            'name' => 'Expeditious Admin',
            'email' => 'support@expeditious.pk',
            'password' => bcrypt('credibleAdmin'),
        ]);
    }
}
