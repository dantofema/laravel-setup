<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('view directory is empty', closure: function () {
    expect(count(File::files('resources/views/livewire/backend')))
        ->toEqual(0);
});

it('all-in-one - generate view file', closure: function () {
    expect(Artisan::call('generate:view tests/config/all-in-one.php'))
        ->toEqual(1);

    $files = File::files('resources/views/livewire/backend');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('posts.blade');
});

it('generate view file', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('resources/views/livewire/backend');

    expect(count($files))->toEqual(2);

    expect(in_array($files[0]->getFilenameWithoutExtension(), ['posts.blade', 'post.blade']))
        ->toBeTrue();

    expect(in_array($files[1]->getFilenameWithoutExtension(), ['posts.blade', 'post.blade']))
        ->toBeTrue();
});

it('all-in-one - replace title', closure: function () {
    expect(Artisan::call('generate:view tests/config/all-in-one.php'))
        ->toEqual(1);

    $content = File::get('resources/views/livewire/backend/posts.blade.php');

    expect(str_contains($content, ':title:'))->toBeFalse();
});

it('replace title', closure: function () {
    expect(Artisan::call('generate:view tests/config/default.php'))
        ->toEqual(1);

    $collectionView = File::get('resources/views/livewire/backend/posts.blade.php');

    expect(str_contains($collectionView, ':title:'))->toBeFalse();

    $modelView = File::get('resources/views/livewire/backend/post.blade.php');

    expect(str_contains($modelView, ':title:'))->toBeFalse();
});

it('all-in-one - view file check syntax', closure: function () {
    Artisan::call('generate:view tests/config/all-in-one.php');
    $config = include(__DIR__ . '/config/all-in-one.php');

    expect(shell_exec("php -l -f " . gen()->getPath($config, 'view')))->toContain('No syntax errors detected');
});

it('view file check syntax', closure: function () {
    Artisan::call('generate:view tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

//    dump(File::get(gen()->getPath($config, 'viewCollection')));

    expect(shell_exec("php -l -f " . gen()->getPath($config, 'viewModel')))
        ->toContain('No syntax errors detected');

    expect(shell_exec("php -l -f " . gen()->getPath($config, 'viewCollection')))
        ->toContain('No syntax errors detected');
});
