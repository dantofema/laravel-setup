<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('generate migration file', closure: function () {
    expect(collect(File::files('database/migrations'))->count())->toEqual(0);

    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(1);

    $files = collect(File::files('database/migrations'));

    expect($files->count())->toEqual(1);

    expect(Str::contains($files[0]->getFilenameWithoutExtension(), 'posts_table'))->toBeTrue();
});

it('generate fields', function () {
    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(1);

    $content = File::files('database/migrations')[0]->getContents();

    expect(Str::contains($content, "\$table->string('title')->unique();"))->toBeTrue();
    expect(Str::contains($content, "\$table->text('body');"))->toBeTrue();
    expect(Str::contains($content, "\$table->string('epigraph')->nullable();"))->toBeTrue();
    expect(Str::contains($content, "\$table->string('name')->nullable()->unique();"))->toBeTrue();
});

it('generate foreign keys', function () {
    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(1);

    $content = File::files('database/migrations')[0]->getContents();

    expect(Str::contains($content, "\$table->foreignId('user_id')->constrained('users');"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->foreignId('author_id')->nullable()->constrained('authors');"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->foreignId('key_id')->nullable()->constrained('keys');"))
        ->toBeTrue();
});

it('if migration file exist return exception and exit', function () {
    $this->expectException(Exception::class);

    Artisan::call('generate:migration tests/config/default.php');
    $file = File::files('database/migrations')[0];
    sleep(1);

    expect(Artisan::call('generate:migration tests/config/default.php'))->toEqual(0);

    $newFiles = File::files('database/migrations');

    expect(count($newFiles))->toEqual(1);

    expect($newFiles[0]->getBaseName())
        ->toEqual($file->getBaseName());
});

it('if config file not found return error and exit', function () {
    $this->expectException(Exception::class);
    expect(Artisan::call('generate:migration config/not-found.php'))->toEqual(0);

    expect(count(File::files('database/migrations')))->toEqual(0);
});

it('add soft delete in migration file', function () {
    Artisan::call('generate:migration tests/config/default.php');
    $content = File::files('database/migrations')[0]->getContents();
    expect(Str::contains($content, "\$table->softDeletes();"))->toBeTrue();
});

it('add softDeletes in migration file', function () {
    Artisan::call('generate:migration tests/config/default.php');
    $content = File::files('database/migrations')[0]->getContents();
    expect(Str::contains($content, "\$table->softDeletes();"))->toBeTrue();
});

it('add Userstamps in migration file', function () {
    Artisan::call('generate:migration tests/config/default.php');
    $content = File::files('database/migrations')[0]->getContents();
    expect(Str::contains($content, "\$table->unsignedBigInteger('created_by')->nullable();"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->unsignedBigInteger('updated_by')->nullable();"))
        ->toBeTrue();
});

it('add Userstamps and SoftDeletes in migration file', function () {
    Artisan::call('generate:migration tests/config/default.php');
    $content = File::files('database/migrations')[0]->getContents();
    expect(Str::contains($content, "\$table->unsignedBigInteger('created_by')->nullable();"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->unsignedBigInteger('updated_by')->nullable();"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->unsignedBigInteger('deleted_by')->nullable();"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->softDeletes();"))->toBeTrue();
});

it('migration file check syntax', closure: function () {
    Artisan::call('generate:migration tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

    expect(shell_exec("php -l -f " . Text::config($config)->path('migration')))->toContain('No syntax errors detected');
});