<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

<<<<<<< HEAD
    expect(Str::contains($content, [
        "\$title = \$this->faker->unique()->sentence(\$maxNbChars = 10);",
        "\$this->faker->sentence(\$nbWords = 350, \$variableNbWords = true);",
        "\$this->faker->sentence();",
        "\$this->faker->unique()->name();",
        "'user_id' => User::inRandomOrder()->first() ?? User::factory()->create(),",
        "'author_id' => Author::inRandomOrder()->first() ?? Author::factory()->create(),",
        "'key_id' => Key::inRandomOrder()->first() ?? Key::factory()->create(),",
    ]))->toBeTrue();
});

it('if factory file exist return exception and exit', function () {
    $this->expectException(Exception::class);
=======
    expect(Str::contains($content, "\$title = \$this->faker->sentence(\$maxNbChars = 10);"))->toBeTrue();
    expect(Str::contains($content, "\$this->faker->sentence(\$nbWords = 350, \$variableNbWords = true);"))->toBeTrue();
    expect(Str::contains($content, "\$this->faker->sentence();"))->toBeTrue();
    expect(Str::contains($content, "\$this->faker->name()->unique();"))->toBeTrue();
});

it('generate foreign keys', function () {
    expect(Artisan::call('generate:factory config/example1.php'))->toEqual(1);

    $content = File::files('database/factories')[0]->getContents();

    expect(Str::contains($content, "'user_id' => User::inRandomOrder()->first() ?? User::factory()->create();"))
        ->toBeTrue();
    expect(Str::contains($content, "'author_id' => Author::inRandomOrder()->first() ?? Author::factory()->create();"))
        ->toBeTrue();
    expect(Str::contains($content, "'key_id' => Key::inRandomOrder()->first() ?? Key::factory()->create();"))
        ->toBeTrue();
});
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392

    Artisan::call('generate:factory tests/config/default.php');
    $file = File::files('database/factories')[0];

    expect(Artisan::call('generate:factory tests/config/default.php'))->toEqual(0);

    $newFiles = File::files('database/factories');

    expect(count($newFiles))->toEqual(1);

    expect($newFiles[0]->getBaseName())->toEqual($file->getBaseName());
});

it('if config file not found return exception and exit', function () {
    $this->expectException(Exception::class);

    expect(Artisan::call('generate:factory config/not-found.php'))->toEqual(0);

    expect(count(File::files('database/factories')))->toEqual(0);
<<<<<<< HEAD
});

it('generate factory with --force return true', closure: function () {
    expect(count(File::files('database/factories')))->toEqual(0);

    expect(Artisan::call('generate:factory tests/config/default.php --force'))
        ->toEqual(1);
});

it('if factory file exist when call with --force return true', closure: function () {
    expect(count(File::files('database/factories')))->toEqual(0);

    Artisan::call('generate:factory tests/config/default.php');

    expect(Artisan::call('generate:factory tests/config/default.php --force'))
        ->toEqual(1);
});

it('update DatabaseSeeder', closure: function () {
    $content = File::get('database/seeders/DatabaseSeeder.php');

    expect(Str::contains($content, "Post::factory(10)->create();"))->toBeFalse();
    expect(Str::contains($content, "use App\Models\Post;"))->toBeFalse();

    Artisan::call('generate:factory tests/config/default.php');

    $content = File::get('database/seeders/DatabaseSeeder.php');

    expect(Str::contains($content, "Post::factory(10)->create();"))->toBeTrue();
    expect(Str::contains($content, "use App\Models\Post;"))->toBeTrue();
=======
    ;
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
});

it('factory file check syntax', closure: function () {
    Artisan::call('generate:factory tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

//    dd(File::get(Text::config($config)->path('factory')));
    expect(shell_exec("php -l -f " . Text::config($config)->path('factory')))->toContain('No syntax errors detected');
});