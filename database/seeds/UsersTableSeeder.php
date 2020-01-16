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
            'email' => 'localmrm@gmail.com',
            'password' => bcrypt('localmrm'),
        ]);
        $admin->assignRole(Role::find(1));
    }
}
