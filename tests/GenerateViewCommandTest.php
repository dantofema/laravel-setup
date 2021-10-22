<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('view directory is empty', closure: function () {
    expect(count(File::files('resources/views/livewire/backend')))
        ->toEqual(0);
});

it('generate view file', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('resources/views/livewire/backend');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('post-livewire');
});

it('replace title', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $content = File::get('resources/views/livewire/backend/post-livewire.php');
    $config = include __DIR__ . '/config/default.php';

    expect(str_contains($content, $config['view']['title']))
        ->toBeTrue();

    expect(str_contains($content, ':title:'))
        ->toBeFalse();
});

it('update route', closure: function () {
    Artisan::call('generate:view tests/config/default.php');
    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeTrue();
});