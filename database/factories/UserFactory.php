<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Jobdesc;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'jobdesc_id' => rand(1, Jobdesc::all()->count()),
        'username' => $faker->userName,
        'email' => $faker->email,
        'nomor_handphone' => $faker->phoneNumber,
        'alamat' => $faker->streetAddress,
        'password' => bcrypt('secret'),
        'profile' => 'default.jpg'
    ];
});
