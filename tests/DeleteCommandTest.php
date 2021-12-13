<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('delete files', closure: function () {
    Artisan::call('generate:crud tests/config/all-in-one.php --all');
    Artisan::call('generate:delete tests/config/all-in-one.php all');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(gen()->getPath($config, 'livewire')))->toBeFalse();
    expect(File::exists(gen()->getPath($config, 'model')))->toBeFalse();
    expect(File::exists(gen()->getPath($config, 'viewAllInOne')))->toBeFalse();
    expect(File::exists(gen()->getPath($config, 'factory')))->toBeFalse();
    expect(File::exists(gen()->getPath($config, 'test')))->toBeFalse();
    expect(File::exists(gen()->getPath($config, 'migration')))->toBeFalse();
});

it('delete rows in route.web', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    Artisan::call('generate:crud tests/config/all-in-one.php --all');

    Artisan::call('generate:delete tests/config/all-in-one.php all');

    $content = File::get(gen()->getRoute());

    expect(str_contains($content, gen()->getName($config, 'livewire')))->toBeFalse();
});
