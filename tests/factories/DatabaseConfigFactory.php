<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Koomai\LaravelConfig\DatabaseConfig;

$factory->define(
    DatabaseConfig::class,
    function (Faker\Generator $faker) {
        return [
            'name' => 'test',
            'value' => ['foo' => 'bar'],
        ];
    }
);
