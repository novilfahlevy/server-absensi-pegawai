<?php

use App\UserHasMadeBy;
use Illuminate\Database\Seeder;

class UserHasMadeBySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 3; $i <= 50; $i++) {
            UserHasMadeBy::create([
                'admin_id' => 1,
                'user_id' => $i
            ]);
        }
    }
}
