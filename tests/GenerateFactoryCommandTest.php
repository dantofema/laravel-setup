<?php

it('generate factory file return true', closure: function () {
    expect(count(File::files('database/factories')))->toEqual(0);

    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(1);
});

it('generate factory file', closure: function () {
    expect(collect(File::files('database/factories'))->count())->toEqual(0);

    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(1);

    $files = collect(File::files('database/factories'));

    expect($files->count())->toEqual(1);
    expect($files[0]->getFileName())->toEqual('PostFactory.php');
});

it('generate fields', function () {
    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(1);

    $content = File::files('database/factories')[0]->getContents();

    expect(Str::contains($content, "\$title = \$this->faker->sentence(\$maxNbChars = 10);"))->toBeTrue();
    expect(Str::contains($content, "\$this->faker->sentence(\$nbWords = 350, \$variableNbWords = true);"))->toBeTrue
    ();
    expect(Str::contains($content, "\$this->faker->sentence();"))->toBeTrue();
    expect(Str::contains($content, "\$this->faker->name()->unique();"))->toBeTrue();
});

it('generate foreign keys', function () {
    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(1);

    $content = File::files('database/factories')[0]->getContents();

    expect(Str::contains($content, "'user_id' => User::inRandomOrder()->first() ?? User::factory()->create();"))
        ->toBeTrue();
    expect(Str::contains($content, "'author_id' => Author::inRandomOrder()->first() ?? Author::factory()->create();"))
        ->toBeTrue();
    expect(Str::contains($content, "'key_id' => Key::inRandomOrder()->first() ?? Key::factory()->create();"))
        ->toBeTrue
        ();
});

it('if factory file exist return error and exit', function () {
    Artisan::call('generate:factory tests/config/default.php');
    $file = File::files('database/factories')[0];

    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(0);

    $newFiles = File::files('database/factories');

    expect(count($newFiles))->toEqual(1);

    expect($newFiles[0]->getBaseName())->toEqual($file->getBaseName());
});

it('if config file not found return error and exit', function () {
    expect(Artisan::call('generate:factory config/not-found.php'))->toEqual(0);

    expect(count(File::files('database/factories')))->toEqual(0);;
});
