<?php

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
    expect(Str::contains($content, "->orWhereHas('tags', fn(\$q) => \$q->where('name', 'like', '%' . \$search . '%'))"))
        ->toBeTrue();
    expect(Str::contains($content, "->orWhereHas('category', fn(\$q) => \$q->where('name', 'like', '%' . \$search . '%'))")
    )
        ->toBeTrue();
});

it('add relationships methods', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();

    $needle = <<<'EOC'
public function subcategories (): HasMany
{
    return $this->hasMany(Subcategory::class);
}

public function authors (): HasMany
{
    return $this->hasMany(Author::class);
}

public function tags (): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}

public function category (): BelongsTo
{
    return $this->belongsTo(Category::class);
}
EOC;

    expect(Str::contains($content, $needle))->toBeTrue();
});

it('replace path', function () {
    Artisan::call('generate:model tests/config/default.php');

    $content = File::files('app/Models')[0]->getContents();
    $config = include __DIR__ . '/config/default.php';

    $path = $config['model']['path'];

    expect(Str::contains($content, "return '$path/' . \$this->id;"))->toBeTrue();

    expect(str_contains($content, ':path:'))->toBeFalse();
});

