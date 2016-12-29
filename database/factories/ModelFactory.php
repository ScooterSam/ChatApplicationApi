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
     $password = bcrypt('sam123');

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'api_token' => str_random(),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});



$factory->define(App\Models\Chat::class, function(Faker\Generator $faker) {

    return [];

});
$factory->define(App\Models\Participant::class, function(Faker\Generator $faker) {

    $chats = \App\Models\Chat::all()->pluck('id')->toArray();
    $users = \App\User::all()->pluck('id')->toArray();

    return [
        'chat_id' => $faker->randomElement($chats),
        'user_id' => $faker->randomElement($users)
    ];

});
$factory->define(App\Models\Message::class, function(Faker\Generator $faker) {

    $chats = \App\Models\Chat::all()->pluck('id')->toArray();
    $users = \App\User::all()->pluck('id')->toArray();

    return [
        'chat_id' => $faker->randomElement($chats),
        'user_id' => $faker->randomElement($users),
        'message' => $faker->text()
    ];

});