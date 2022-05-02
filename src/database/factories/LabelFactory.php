<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use sh0beir\todo\Models\Label;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Label::class, function (Faker $faker) {
    return [
        //
        "label" => $faker->unique()->word() . $faker->unique()->word(),
    ];
});
