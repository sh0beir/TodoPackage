<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use sh0beir\todo\Models\Task;

use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    $author = factory(User::class)->create();
    return [
        //
        "title" => $faker->sentence,
        "description" => $faker->paragraph,
        "status" => $faker->boolean,
        "author_id" => $author->id,
        "author_type" => get_class($author),
    ];
});
