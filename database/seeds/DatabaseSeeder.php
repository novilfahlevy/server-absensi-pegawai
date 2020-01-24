<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(WaktuKerjaSeeder::class);
        $this->call(AbsensiSeeder::class);
        $this->call(LemburSeeder::class);
    }
}
