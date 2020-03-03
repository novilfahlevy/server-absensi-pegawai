<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\UserHasMadeBy;
use Faker\Factory as Faker;

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

        $admin = User::create([
            'name' => 'Admin',
            'jobdesc_id' => 1,
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'nomor_handphone' => '081234567890',
            'alamat' => 'Jl. Semangka',
            'password' => bcrypt('secret'),
            'profile' => 'default.jpg'
        ]);
        $admin->assignRole(Role::find(1));

        $normal_user = User::create([
            'name' => 'Normal User',
            'jobdesc_id' => 1,
            'username' => 'user',
            'email' => 'user@user.com',
            'nomor_handphone' => '089283748128',
            'alamat' => 'Jl. Marmut',
            'password' => bcrypt('secret'),
            'profile' => 'default.jpg'
        ]);
        $normal_user->assignRole(Role::find(2));

        $project_manager = User::create([
            'name' => 'Project Manager',
            'jobdesc_id' => 1,
            'username' => 'pm',
            'email' => 'pm@pm.com',
            'nomor_handphone' => '081212121212',
            'alamat' => 'Jl. Langsat',
            'password' => bcrypt('secret'),
            'profile' => 'default.jpg'
        ]);
        $project_manager->assignRole(Role::find(3));

        // $faker = Faker::create('id_ID');

        // for ($i = 1; $i <= 50; $i++) {
        //     $user = User::create([
        //         'name' => $faker->name,
        //         'jobdesc_id' => rand(1, 3),
        //         'username' => $faker->userName,
        //         'email' => $faker->freeEmail,
        //         'nomor_handphone' => $faker->phoneNumber,
        //         'alamat' => $faker->address,
        //         'password' => $faker->password,
        //         'profile' => 'default.jpg'
        //     ]);
        //     $user->assignRole(Role::find(2));
        // }
    }
}
