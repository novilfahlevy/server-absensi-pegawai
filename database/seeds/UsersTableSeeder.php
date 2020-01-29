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
            ['name' => 'User', 'guard_name' => 'api'],
            ['name' => 'Project Manager', 'guard_name' => 'api']
        ];

        Role::insert($role_data);

        foreach ( Role::all() as $role ) {
            foreach ( factory(User::class, 20)->create() as $user ) {
                $user->assignRole($role);
            }
        }

        // $admin = User::create([
        //     'name' => 'Admin',
        //     'jobdesc_id' => 1,
        //     'username' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'nomor_handphone' => '081234567890',
        //     'alamat' => 'Jl. Semangka',
        //     'password' => bcrypt('secret'),
        //     'profile' => 'default.jpg'
        // ]);
        // $admin->assignRole(Role::find(1));

        // $user = User::create([
        //     'name' => 'User',
        //     'jobdesc_id' => 2,
        //     'username' => 'user',
        //     'email' => 'user@user.com',
        //     'nomor_handphone' => '089876543210',
        //     'alamat' => 'Jl. Nangka',
        //     'password' => bcrypt('secret'),
        //     'profile' => 'default.jpg'
        // ]);
        // $user->assignRole(Role::find(2));

        // $bambang = User::create([
        //     'name' => 'Bambang Wica',
        //     'jobdesc_id' => 2,
        //     'username' => 'bambang',
        //     'email' => 'bambang@bambang.com',
        //     'nomor_handphone' => '081364736471',
        //     'alamat' => 'Jl. Santuy',
        //     'password' => bcrypt('secret'),
        //     'profile' => 'default.jpg'
        // ]);
        // $bambang->assignRole(Role::find(2));

        // $kinay = User::create([
        //     'name' => 'Kinay',
        //     'jobdesc_id' => 3,
        //     'username' => 'kinay',
        //     'email' => 'kinay@kinay.com',
        //     'nomor_handphone' => '086756457806',
        //     'alamat' => 'Jl. Merdeka',
        //     'password' => bcrypt('secret'),
        //     'profile' => 'default.jpg'
        // ]);
        // $kinay->assignRole(Role::find(2));

        // $project_manager = User::create([
        //     'name' => 'Project Manager',
        //     'jobdesc_id' => 1,
        //     'username' => 'pm',
        //     'email' => 'pm@pm.com',
        //     'nomor_handphone' => '0869696969696',
        //     'alamat' => 'Jl. PM',
        //     'password' => bcrypt('secret'),
        //     'profile' => 'default.jpg'
        // ]);
        // $project_manager->assignRole(Role::find(3));
    }
}
