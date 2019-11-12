<?php

use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Support\Contracts\Status;

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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'contact_no' => $faker->phoneNumber,
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'active', function (Faker\Generator $faker) {
    return [
        'status' => Status::STATUS_ACTIVE,
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'pending', function (Faker\Generator $faker) {
    return [
        'status' => Status::STATUS_PENDING,
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'disabled', function (Faker\Generator $faker) {
    return [
        'status' => Status::STATUS_DISABLED,
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'hidden', function (Faker\Generator $faker) {
    return [
        'status' => Status::STATUS_HIDDEN,
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'family_member', function (Faker\Generator $faker) {
    return [
        'type' => User::USER_TYPE_FAMILY_MEMBER
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->state(User::class, 'ofw', function (Faker\Generator $faker) {
    return [
        'type' => User::USER_TYPE_OFW,
        'owwa_id' => $faker->randomNumber
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->define(Family::class, function (Faker\Generator $faker) {
    return [
        'data' => [],
        'code' => str_random(config('app.invitation_code_length'))
    ];
});

/** @var \Illuminmate\Database\Eloquent\Factory $factory */
$factory->define(FamilyMember::class, function (Faker\Generator $faker) {
    return [];
});
