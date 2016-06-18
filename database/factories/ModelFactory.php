<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Story::class, function (Faker\Generator $faker) {
    return [
        'title'     => $faker->sentence,
		'excerpt'   => $faker->paragraph(2),
        'body'      => $faker->paragraph(20),
        'image_url' => 'https://unsplash.it/1024/1024?image=' . $faker->numberBetween(1, 1000),
        'user_id'   => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});

$factory->define(App\Like::class, function (Faker\Generator $faker) {
    return [
        'user_id'  => function () {
            return factory(App\User::class)->create()->id;
        },
        'story_id' => function () {
            return factory(App\Story::class)->create()->id;
        },
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
	return [
		'name' => $faker->word,
	];
});
