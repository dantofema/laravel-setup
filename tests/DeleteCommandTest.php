<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('delete files', closure: function () {
    Artisan::call('generate:crud tests/config/default.php --all');
    Artisan::call('generate:delete tests/config/default.php --all');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(Text::config($config)->path('factory')))->toBeFalse();
});

it('delete rows in route.web', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    Artisan::call('generate:crud tests/config/default.php --all');

    Artisan::call('generate:delete tests/config/default.php --all');

    $content = File::get(Text::path('route'));

    expect(str_contains($content, Text::config($config)->name('livewire')))->toBeFalse();
});