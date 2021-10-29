<?php

use Dantofema\LaravelSetup\Facades\Path;
use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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

    expect(str_contains($content, $config['table']['name']))->toBeTrue();

    expect(str_contains($content, ':table:'))->toBeFalse();
});

it('replace path', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');

    expect(str_contains($content, $config['model']['path']))->toBeTrue();

    expect(str_contains($content, ':path:'))->toBeFalse();
});

it('replace view', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');

    expect(str_contains($content, $config['livewire']['view']))->toBeTrue();

    expect(str_contains($content, ':view:'))->toBeFalse();
});

it('replace is required', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');
    $column = $config['table']['columns'][0][1];

    expect(str_contains($content, "->set('editing.$column', '')"))->toBeTrue();
    expect(str_contains($content, ':field:'))->toBeFalse();
});

it('replace edit slug', closure: function () {
    expect(Artisan::call('generate:test tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('tests/Feature/Backend/PostsLivewireTest.php');
    $column = $config['table']['columns'][0][1];

    expect(str_contains($content, "->set('editing.$column', \$newValue)"))->toBeTrue();
    expect(str_contains($content, ':field:'))->toBeFalse();
});

it('replace edit-slug without slug', closure: function () {
    Artisan::call('generate:test tests/config/without-slug.php');

    $config = include __DIR__ . '/config/default.php';
    $content = File::get(Text::config($config)->path('test'));

    expect(str_contains($content, 'slug'))->toBeFalse();
});