<?php

use Illuminate\Database\Seeder;

use App\Jobdesc;

class JobdescSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jobdesc::create([
            'id' => 1,
            'name' => 'Web Developer'
        ]);

        Jobdesc::create([
            'id' => 2,
            'name' => 'Android Developer'
        ]);

        Jobdesc::create([
            'id' => 3,
            'name' => 'Designer'
        ]);
    }
}
