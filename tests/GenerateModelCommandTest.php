<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('generate model file', closure: function () {
    expect(count(File::files('app/Models')))->toEqual(0);

    expect(Artisan::call('generate:model tests/config/default.php'))->toEqual(1);

    $files = File::files('app/Models');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('Post');
});

it('add namespace', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, "namespace App\Models;"))->toBeTrue();
});

it('add use namespace', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, "use Illuminate\Database\Eloquent\SoftDeletes;"))->toBeTrue();
    expect(Str::contains($content, "use Wildside\Userstamps\Userstamps;"))->toBeTrue();
});

it('add use', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, "use SoftDeletes;"))->toBeTrue();
    expect(Str::contains($content, "use Userstamps;"))->toBeTrue();
});

it('add model name', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, "class Post extends Model"))->toBeTrue();
});

it('add search method', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();
    expect(Str::contains($content, "\$query->where('title', 'like', '%' . \$search . '%')"))->toBeTrue();
    expect(Str::contains($content, "->orWhere('subtitle', 'like', '%' . \$search . '%')"))->toBeTrue();
    expect(Str::contains($content, "->orWhere('created_at', 'like', '%' . \$search . '%')"))->toBeTrue();
    expect(Str::contains($content, "->orWhereHas('author', fn(\$q) => \$q->where('title', 'like', '%' . \$search . '%'))")
    )
        ->toBeTrue();
});

it('add relationships methods', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, [
        '<?php',
        'use Illuminate\Database\Eloquent\Relations\HasMany;',
        'public function subcategories (): HasMany',
        'use Illuminate\Database\Eloquent\Relations\BelongsToMany;',
        'public function tags (): BelongsToMany',
    ]))->toBeTrue();
});

it('replace path', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();
    $config = include __DIR__ . '/config/default.php';

    $path = $config['route']['path'];

    expect(Str::contains($content, "return '$path/' . \$this->id;"))->toBeTrue();

    expect(str_contains($content, ':path:'))->toBeFalse();
});

it('model file check syntax', closure: function () {
    Artisan::call('generate:model tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

    expect(shell_exec("php -l -f " . Text::config($config)->path('model')))->toContain('No syntax errors detected');
});

