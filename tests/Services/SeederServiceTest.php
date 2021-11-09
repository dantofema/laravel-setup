<?php

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\SeederService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('add seeder', closure: function () {
    $seederService = new SeederService();

    $config = include(__DIR__ . '/../config/default.php');
    $seederService->add($config);

    $content = File::get('database/seeders/DatabaseSeeder.php');

    expect(Str::contains($content, [
        "use " . Text::config($config)->namespace('model') . ";",
        "<?php",
        Text::config($config)->name('model') . "::factory(10)->create();",
        'class DatabaseSeeder extends Seeder',
        'namespace Database\Seeders;',
    ]))->toBeTrue();
});

it('delete seeder', closure: function () {
    $seederService = new SeederService();
    $config = include(__DIR__ . '/../config/default.php');
    $seederService->add($config);
    $seederService->delete($config);

    $content = File::get('database/seeders/DatabaseSeeder.php');

    expect(Str::contains($content, [
        "use " . Text::config($config)->namespace('model') . ";",
        Text::config($config)->name('model') . "::factory(10)->create();",
    ]))->toBeFalse();

    expect(Str::contains($content, [
        "<?php",
        'class DatabaseSeeder extends Seeder',
        'namespace Database\Seeders;',
    ]))->toBeTrue();
});

it('seeder file check syntax', closure: function () {
    $seederService = new SeederService();
    $config = include(__DIR__ . '/../config/default.php');
    $seederService->add($config);

    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');

    $seederService->delete($config);
    expect(shell_exec("php -l -f database/seeders/DatabaseSeeder.php"))->toContain('No syntax errors detected');
});