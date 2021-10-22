<?php

use Dantofema\LaravelSetup\Tests\TestCase;
use Illuminate\Support\Facades\File;

uses(TestCase::class)
    ->beforeEach(fn() => clearDirectories())
    ->afterEach(fn() => clearDirectories())
    ->in(__DIR__);

function clearDirectories ()
{
    File::delete('database/seeders/DatabaseSeeder.php');
    File::copy('database/seeders/_DatabaseSeeder.php', 'database/seeders/DatabaseSeeder.php');

    File::delete('routes/web.php');
    File::copy('routes/_web.php', 'routes/web.php');

    $directories = [
        'database/migrations',
        'database/factories',
        'app/Models',
        'app/Http/Livewire/Backend',
        'app/Http/Livewire/Frontend',
        'resources/views/livewire/backend',
        'resources/views/livewire/frontend',
        'tests/Feature/Backend',
    ];

    foreach ($directories as $directory)
    {
        collect(File::files($directory))
            ->contains(function ($value) {
                File::delete($value);
            });
    }
}