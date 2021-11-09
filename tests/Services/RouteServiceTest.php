<?php

use Dantofema\LaravelSetup\Services\RouteService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('PostsLivewire not found', closure: function () {
    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeFalse();
});

it('add PostsLivewire route', closure: function () {
    $route = new RouteService();

    $route->add(include(__DIR__ . '/../config/default.php'));

    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeTrue();

    expect(Str::contains($content,
        "<?php"))
        ->toBeTrue();
});

it('delete PostsLivewire route', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'));
    $route->delete(include(__DIR__ . '/../config/default.php'));

    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeFalse();

    expect(Str::contains($content,
        "<?php"))
        ->toBeTrue();
});

it('delete PostsLivewire route bucle', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'));
    $route->delete(include(__DIR__ . '/../config/default.php'));
    $route->add(include(__DIR__ . '/../config/default.php'));

    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeTrue();

    expect(Str::contains($content,
        "<?php"))
        ->toBeTrue();
});

it('seeder file check syntax', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'));

    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');

    $route->delete(include(__DIR__ . '/../config/default.php'));
    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');
});

