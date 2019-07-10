<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\UserProfile::class, function (Faker $faker) {
    return [
        'bio' => $faker->paragraph,
        'twitter' => 'http://twitter.com/' . $faker->word,
    ];
});
