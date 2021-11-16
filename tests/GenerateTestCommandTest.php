<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('backend directory is empty', closure: function () {
    expect(count(File::files('tests/Feature/Backend')))
        ->toEqual(0);
});

it('generate livewire file', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('tests/Feature/Backend');

    expect(count($files))
        ->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())
        ->toEqual('PostsLivewireTest');
});

it('replace table', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');

    expect(Str::contains($content, [
        $config['table']['name'],
        $config['route']['path'],
        Text::config($config)->name('livewire') . '::class',
        "->set('editing.{$config['fields'][0]['name']}', '')",
        'use ' . Text::config($config)->namespace('model'),
    ]))->toBeTrue();
});

it('test file check syntax', closure: function () {
    Artisan::call('generate:test tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');
//    dd(File::get(Text::config($config)->path('test')));
    expect(shell_exec("php -l -f " . Text::config($config)->path('test')))->toContain('No syntax errors detected');
});

