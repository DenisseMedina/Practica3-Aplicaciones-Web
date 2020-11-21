<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Persona;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {
    return [
        'nombre'=> $faker->word(),
        'apellidoPaterno' => $faker->lastname,
        'apellidoMaterno' => $faker->lastname,
        'edad'=>$faker->NumberBetween($min =5, $max=80),
        'sexo'=>$faker->randomElement( ['Masculino', 'Femenino']),
    ];
});
