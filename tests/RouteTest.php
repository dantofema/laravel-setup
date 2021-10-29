<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('add route', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');
    $content = File::get('routes/web.php');

    expect(Str::contains($content,
        "Route::get('/notas', PostsLivewire::class)->middleware('auth')->prefix('sistema')->name('posts');"))
        ->toBeTrue();

    expect(Str::contains($content,
        "<?php"))
        ->toBeTrue();
});