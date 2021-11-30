<?php

use Dantofema\LaravelSetup\Services\Tests\EditSlugService;
use Illuminate\Support\Str;

it('edit slug', closure: function () {
    $editSlugService = new EditSlugService();
    $content = $editSlugService->get(include(__DIR__ . '/../../config/default.php'));

    expect(Str::contains($content, [
        ':edit-slug:',
        ':field:',
        ':view:',
        ':model:',
        ':table:',
        'missing',
    ]))
        ->toBeFalse();
});


