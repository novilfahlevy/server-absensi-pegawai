<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\UserHasMadeBy;

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

        $admin = User::create([
            'name' => 'Project Manager',
            'jobdesc_id' => 1,
            'username' => 'pm',
            'email' => 'pm@pm.com',
            'nomor_handphone' => '081212121212',
            'alamat' => 'Jl. Langsat',
            'password' => bcrypt('secret'),
            'profile' => 'default.jpg'
        ]);
        $admin->assignRole(Role::find(3));

        foreach ( Role::all() as $role ) {
            foreach ( factory(User::class, 20)->create() as $user ) {
                if ( $user->id > 20 ) {
                    UserHasMadeBy::create(['admin_id' => 1, 'user_id' => $user->id]);
                }
                $user->assignRole($role);
            }
        }
    }
}
