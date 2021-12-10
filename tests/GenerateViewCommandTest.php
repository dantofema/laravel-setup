<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('view directory is empty', closure: function () {
    expect(count(File::files('resources/views/livewire/backend')))
        ->toEqual(0);
});

it('generate view file', closure: function () {
    expect(Artisan::call('generate:view tests/config/all-in-one.php'))
        ->toEqual(1);

    $files = File::files('resources/views/livewire/backend');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('posts.blade');
});

it('replace title', closure: function () {
    expect(Artisan::call('generate:view tests/config/all-in-one.php'))
        ->toEqual(1);

    $content = File::get('resources/views/livewire/backend/posts.blade.php');

    expect(str_contains($content, ':title:'))->toBeFalse();
});

it('view file check syntax', closure: function () {
    Artisan::call('generate:view tests/config/all-in-one.php');
    $config = include(__DIR__ . '/config/default.php');

    expect(shell_exec("php -l -f " . Text::config($config)->path('view')))->toContain('No syntax errors detected');
});
