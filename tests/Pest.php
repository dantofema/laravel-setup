<?php

use Dantofema\LaravelSetup\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn() => clearDirectories())
    ->afterEach(fn() => clearDirectories())
    ->in(__DIR__);

function clearDirectories ()
{
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