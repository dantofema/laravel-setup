<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('generate files', closure: function () {
    Artisan::call('generate:crud tests/config/all-in-one.php ');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(gen()->path()->livewire($config)))->toBeTrue();
    expect(File::exists(gen()->path()->model($config)))->toBeTrue();
    expect(File::exists(gen()->path()->factory($config)))->toBeTrue();
    expect(File::exists(gen()->path()->test($config)))->toBeTrue();
    expect(File::exists(gen()->path()->migration($config)))->toBeTrue();
});
