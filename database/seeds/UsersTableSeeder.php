<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // set default roles
        $role_data = [
            ['name' => 'Admin', 'guard_name' => 'api'],
            ['name' => 'User', 'guard_name' => 'api']
        ];

        Role::insert($role_data);

        // set admin roles ID = 1
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'nomor_handphone' => '081234567890',
            'alamat' => 'Jl. Semangka',
            'password' => bcrypt('secret'),
        ]);
        $admin->assignRole(Role::find(1));
        $alice = User::create([
            'name' => 'alice',
            'username' => 'alice',
            'email' => 'alice@gmail.com',
            'nomor_handphone' => '082138173918',
            'alamat' => 'Jl. Semangka',
            'password' => bcrypt('12345678')
        ]);
        // set user roles ID = 2
        $user = User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@user.com',
            'nomor_handphone' => '089876543210',
            'alamat' => 'Jl. Nangka',
            'password' => bcrypt('secret'),
        ]);
        $alice->assignRole(Role::find(1));
        $user->assignRole(Role::find(2));
    }
}
