<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('view directory is empty', closure: function () {
    expect(count(File::files('resources/views/livewire/backend')))
        ->toEqual(0);
});

it('generate view file', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('resources/views/livewire/backend');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('posts-livewire.blade');
});

it('replace title', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $content = File::get('resources/views/livewire/backend/posts-livewire.blade.php');
    $config = include __DIR__ . '/config/default.php';

    expect(str_contains($content, $config['view']['title']))
        ->toBeTrue();

    expect(str_contains($content, ':title:'))
        ->toBeFalse();
});

