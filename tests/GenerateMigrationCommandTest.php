<?php

it('generate migration file', closure: function () {
    expect(collect(File::files('database/migrations'))->count())->toEqual(0);

    expect(Artisan::call('generate:migration config/example1.php'))->toEqual(1);

    $files = collect(File::files('database/migrations'));

    expect($files->count())->toEqual(1);

    expect(Str::contains($files[0]->getFilenameWithoutExtension(), 'posts_table'))->toBeTrue();
});

it('generate fields', function () {
    expect(Artisan::call('generate:migration config/example1.php'))->toEqual(1);

    $content = File::files('database/migrations')[0]->getContents();

    expect(Str::contains($content, "\$table->string('title');"))->toBeTrue();
    expect(Str::contains($content, "\$table->text('body');"))->toBeTrue();
    expect(Str::contains($content, "\$table->string('epigraph')->nullable();"))->toBeTrue();
    expect(Str::contains($content, "\$table->string('name')->nullable()->unique();"))->toBeTrue();
});

it('generate foreign keys', function () {
    expect(Artisan::call('generate:migration config/example1.php'))->toEqual(1);

    $content = File::files('database/migrations')[0]->getContents();

    expect(Str::contains($content, "\$table->foreignId('user_id')->constrained('users');"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->foreignId('author_id')->nullable()->constrained('authors');"))
        ->toBeTrue();
    expect(Str::contains($content, "\$table->foreignId('key_id')->nullable()->constrained('keys')->unique();"))
        ->toBeTrue();
});

it('if migration file exist return error and exit', function () {
    Artisan::call('generate:migration config/example1.php');
    $file = File::files('database/migrations')[0];
    sleep(1);

    expect(Artisan::call('generate:migration config/example1.php'))->toEqual(0);

    $newFiles = File::files('database/migrations');

    expect(count($newFiles))->toEqual(1);

    expect($newFiles[0]->getBaseName())
        ->toEqual($file->getBaseName());
});

it('if config file not found return error and exit', function () {
    expect(Artisan::call('generate:migration config/not-found.php'))->toEqual(0);

    expect(count(File::files('database/migrations')))->toEqual(0);;
});
