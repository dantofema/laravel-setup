<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('delete files', closure: function () {
    Artisan::call('generate:crud tests/config/all-in-one.php ');
    Artisan::call('generate:delete tests/config/all-in-one.php all');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(gen()->config()->livewire($config)))->toBeFalse();
    expect(File::exists(gen()->config()->model($config)))->toBeFalse();
    expect(File::exists(gen()->config()->factory($config)))->toBeFalse();
    expect(File::exists(gen()->config()->test($config)))->toBeFalse();
    expect(File::exists(gen()->config()->migration($config)))->toBeFalse();
});

it('delete rows in route.web', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    Artisan::call('generate:crud tests/config/all-in-one.php ');

    Artisan::call('generate:delete tests/config/all-in-one.php all');

    $content = File::get(gen()->path()->route());

    expect(str_contains($content, gen()->config()->livewire($config)))->toBeFalse();
});
