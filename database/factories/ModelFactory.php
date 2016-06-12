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

$factory->define(Linku\Models\User::class, function (Faker\Generator $faker) {

    $email = microtime() . $faker->safeEmail;

    return [
        'name' => $faker->name,
        'email' => $email,
        'password' => bcrypt('010790'),
        'remember_token' => bcrypt(microtime() . str_random(10)),
        'api_token' => Linku\Models\User::makeApiToken()
    ];
});

$factory->define(Linku\Models\Folder::class, function (Faker\Generator $faker) {

    global /** @var \Linku\Models\User $guser */
    $guser;

    $parent = $guser->rootFolder() ?: null;

    if($parent) {
        $parent = $guser->folders()->orderBy(DB::raw('RAND()'))->first();
        $parent = $parent->id;
    }

    return [
        'name' => $parent ? $faker->sentence($faker->numberBetween(1, 2)) : 'Root',
        'parent_id' => $parent
    ];
});

$factory->define(
    Linku\Models\Share::class, function (Faker\Generator $faker) {

    $by_user = \Linku\Models\User::whereNotNull('id')
        ->orderBy(DB::raw('RAND()'))->first();

    $folder = $by_user->folders()
        ->whereNotNull('parent_id')
        ->orderBy(DB::raw('RAND()'))->first();

    $user = \Linku\Models\User::find(2);
    $parentFolder = $user->rootFolder();

    return [
        'shareable_type' => \Linku\Models\Folder::class,
        'shareable_id' => $folder->id,
        'by_user_id' => $by_user->id,
        'user_id' => $user->id,
        'folder_parent_id' => $parentFolder->id
    ];

});