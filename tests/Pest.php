<?php

use Dantofema\LaravelSetup\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn() => clearDirectories())
    ->afterEach(fn() => clearDirectories())
    ->in(__DIR__);

function clearDirectories ()
{
    $directories = ['database/migrations', 'database/factories'];

    foreach ($directories as $directory)
    {
        collect(File::files($directory))
            ->contains(function ($value) {
                File::delete($value);
            });
    }
}