<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('generate migration file', closure: function () {
    expect(collect(File::files('database/migrations'))->count())->toEqual(0);

    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(1);

    $files = collect(File::files('database/migrations'));

    expect($files->count())->toEqual(2);

    $file = getMigrationFile();
    expect(Str::contains($file->getFilenameWithoutExtension(), 'posts_table'))->toBeTrue();
});

function getMigrationFile (): \Symfony\Component\Finder\SplFileInfo
{
    $config = include(__DIR__ . '/config/default.php');
    $files = File::files('database/migrations');
    $migration = null;
    foreach ($files as $file)
    {
        if (Str::contains($file, $config['table']))
        {
            $migration = $file;
            break;
        }
    }
    return $migration;
}

it('generate fields', function () {
    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(1);

    $migration = getMigrationFile();

    expect(Str::contains($migration->getContents(), [
        "\$table->string('title')->unique();",
        "\$table->text('image');",
        "\$table->string('slug');",
        "\$table->text('body')->nullable();",
        "\$table->foreignId('author_id')->nullable()->constrained('authors');",
        "\$table->unsignedBigInteger('updated_by')->nullable();",
        "\$table->unsignedBigInteger('created_by')->nullable();",
        "\$table->unsignedBigInteger('deleted_by')->nullable();",
        "\$table->softDeletes();",
    ]))->toBeTrue();
});

it('if migration file exist return exception and exit', function () {
    $this->expectException(Exception::class);

    Artisan::call('generate:migration tests/config/default.php');

    sleep(1);

    $file = getMigrationFile();

    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(0);

    $newFiles = File::files('database/migrations');

    expect(count($newFiles))->toEqual(1);

    $newFile = getMigrationFile();

    expect($newFile->getBaseName())->toEqual($file->getBaseName());
});

it('if config file not found return error and exit', function () {
    $this->expectException(Exception::class);
    expect(Artisan::call('generate:migration config/not-found.php'))->toEqual(0);

    expect(count(File::files('database/migrations')))->toEqual(0);
});

it('migration file check syntax', closure: function () {
    Artisan::call('generate:migration tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

    expect(shell_exec("php -l -f " . Text::config($config)->path('migration')))->toContain('No syntax errors detected');
});