<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('backend directory is empty', closure: function () {
    expect(count(File::files('tests/Feature/Backend')))
        ->toEqual(0);
});

it('generate test file', closure: function () {
    expect(Artisan::call('generate:test tests/config/all-in-one.php'))
        ->toEqual(1);

    $files = File::files('tests/Feature/Backend');

    expect(count($files))
        ->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())
        ->toEqual('PostsLivewireTest');
});

it('replace table', closure: function () {
    expect(Artisan::call('generate:test tests/config/all-in-one.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');

    expect(Str::contains($content, [
        $config['table']['name'],
        $config['route']['path'],
        gen()->config()->livewire($config) . '::class',
        "->set('editing.{$config['fields'][0]['name']}', '')",
        'use ' . gen()->namespace()->model($config),
    ]))->toBeTrue();
});

it('test file check syntax', closure: function () {
    Artisan::call('generate:test tests/config/all-in-one.php');
    $config = include(__DIR__ . '/config/default.php');

    //    dump(File::get(gen()->path()->test($config));
    expect(shell_exec("php -l -f " . gen()->path()->test($config)))
        ->toContain('No syntax errors detected');
});
