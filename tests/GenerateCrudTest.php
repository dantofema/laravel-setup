<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('generate files', closure: function () {
    Artisan::call('generate:crud tests/config/all-in-one.php --all');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(gen()->getPath($config, 'livewire')))->toBeTrue();
    expect(File::exists(gen()->getPath($config, 'model')))->toBeTrue();
    expect(File::exists(gen()->getPath($config, 'viewAllInOne')))->toBeTrue();
    expect(File::exists(gen()->getPath($config, 'factory')))->toBeTrue();
    expect(File::exists(gen()->getPath($config, 'test')))->toBeTrue();
    expect(File::exists(gen()->getPath($config, 'migration')))->toBeTrue();
});
