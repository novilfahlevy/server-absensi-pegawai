<?php

use App\ProjectManager;
use Illuminate\Database\Seeder;

class ProjectManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 49; $i++) {
            ProjectManager::create([
                'pm_id' => 2,
                'user_id' => rand(4, 49)
            ]);
        }
    }
}
