<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('generate files', closure: function () {
    Artisan::call('generate:crud tests/config/default.php --all');
    $config = include(__DIR__ . '/config/default.php');

    expect(File::exists(Text::config($config)->path('livewire')))->toBeTrue();
    expect(File::exists(Text::config($config)->path('model')))->toBeTrue();
    expect(File::exists(Text::config($config)->path('view')))->toBeTrue();
    expect(File::exists(Text::config($config)->path('factory')))->toBeTrue();
    expect(File::exists(Text::config($config)->path('test')))->toBeTrue();
    expect(File::exists(Text::config($config)->path('migration')))->toBeTrue();
});