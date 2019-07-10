<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Profession::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(2)
    ];
});
