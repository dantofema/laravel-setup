<?php

use Dantofema\LaravelSetup\Services\RouteService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('in web.php not found post or posts route', closure: function () {
    $content = File::get('routes/web.php');

    expect(Str::contains($content, [
            "Route::get('/notas/{action?}/{modelId?}', PostsLivewire::class)->middleware('auth')->prefix('admin')->name('posts');",
        ]
    ))->toBeFalse();
});

it('add route', closure: function () {
    $route = new RouteService();

    $route->add(include(__DIR__ . '/../config/default.php'), 'livewire');

    $content = File::get('routes/web.php');

    expect(Str::contains($content, [
            "Route::get('/notas/{action?}/{modelId?}', PostsLivewire::class)->middleware('auth')->prefix('admin')->name('posts');",
            "<?php",
            "use App\Http\Livewire\Backend\PostsLivewire;",
        ]
    ))->toBeTrue();
});

it('delete route', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'), 'livewire');
    $route->delete(include(__DIR__ . '/../config/default.php'));

    $content = File::get('routes/web.php');

    expect(Str::contains($content, [
            "Route::get('/notas/{action?}/{modelId?}', PostsLivewire::class)->middleware('auth')->prefix('admin')->name('posts');",
            "use App\Http\Livewire\Backend\PostsLivewire;",
        ]
    ))->toBeFalse();
});

it('add & delete & add route', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'), 'livewire');
    $route->delete(include(__DIR__ . '/../config/default.php'));
    $route->add(include(__DIR__ . '/../config/default.php'), 'livewire');

    $content = File::get('routes/web.php');

    expect(Str::contains($content, [
            "Route::get('/notas/{action?}/{modelId?}', PostsLivewire::class)->middleware('auth')->prefix('admin')->name('posts');",
            "<?php",
            "use App\Http\Livewire\Backend\PostsLivewire;",
        ]
    ))->toBeTrue();
});

it('seeder file check syntax', closure: function () {
    $route = new RouteService();
    $route->add(include(__DIR__ . '/../config/default.php'), 'livewire');

    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');

    $route->delete(include(__DIR__ . '/../config/default.php'));
    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');
});
