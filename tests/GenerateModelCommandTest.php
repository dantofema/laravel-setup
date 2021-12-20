<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('generate model file', closure: function () {
    expect(count(File::files('app/Models')))->toEqual(0);

    expect(Artisan::call('generate:model tests/config/all-in-one.php'))->toEqual(1);

    $files = File::files('app/Models');

    expect(count($files))->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())->toEqual('Post');
});

it('add namespace', function () {
    Artisan::call('generate:model tests/config/all-in-one.php');

    $content = File::files('app/Models')[0]->getContents();

    expect(Str::contains($content, [
        "namespace App\Models;",
        "use Illuminate\Database\Eloquent\SoftDeletes;",
        "use Wildside\Userstamps\Userstamps;",
        "use SoftDeletes;",
        "use Userstamps;",
        "class Post extends Model",
        "\$query->where('title', 'like', '%' . \$search . '%')",
        "->orWhere('subtitle', 'like', '%' . \$search . '%')",
        "->orWhere('created_at', 'like', '%' . \$search . '%')",
        "->orWhereHas('author', fn(\$q) => \$q->where('title', 'like', '%' . \$search . '%'))",
        '<?php',
        'use Illuminate\Database\Eloquent\Relations\HasMany;',
        'public function subcategories (): HasMany',
        'use Illuminate\Database\Eloquent\Relations\BelongsToMany;',
        'public function tags (): BelongsToMany',
    ]))->toBeTrue();
});

it('model file check syntax', closure: function () {
    Artisan::call('generate:model tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

//    dump(File::get(gen()->path()->model($config)));

    expect(shell_exec("php -l -f " . gen()->path()->model($config)))
        ->toContain('No syntax errors detected');
});
