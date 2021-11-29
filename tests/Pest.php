<?php

use App\Models\User;
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
    File::copy('config/_filesystems.php', 'config/filesystems.php');

    $directories = [
        'database/migrations',
        'database/factories',
        'app/Models',
        'app/Http/Livewire/Backend',
        'app/Http/Livewire/Frontend',
        'resources/views/livewire/backend',
        'resources/views/livewire/frontend',
        'tests/Feature/Backend',
        'app/Http/Livewire/Traits',
    ];

    foreach ($directories as $directory)
    {
        collect(File::files($directory))
            ->contains(function ($value) {
                File::delete($value);
            });
    }
}

function actingAs (User $user, string $driver = null): Tests\TestCase
{
    return test()->actingAs($user, $driver);
}