<?php

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('livewire directory is empty', closure: function () {
    expect(count(File::files('app/Http/Livewire')))
        ->toEqual(0);
});

it('generate livewire file', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('app/Http/Livewire/Backend');

    expect(count($files))
        ->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())
        ->toEqual('PostsLivewire');
});

it('replaces', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(Str::contains($content, [
        $config['model']['name'],
        $config['livewire']['properties']['sortField'],
        'public $newFile;',
        '$this->newFile = "";',
        '$this->newFile = null;',
        "\$this->setSlug('title');",
        "namespace App\Http\Livewire\Backend",
        "'author_id' =>",
        Text::config($config)->renderView(),
    ]))
        ->toBeTrue();

    expect(str_contains($content, ':namespace:'))
        ->toBeFalse();
});

it('livewire file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');
//    dd(File::get(Text::config($config)->path('livewire')));
    expect(shell_exec("php -l -f " . Text::config($config)->path('livewire')))
        ->toContain('No syntax errors detected');
});

it('route file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');

    expect(shell_exec("php -l -f " . Text::config($config)->path('route')))
        ->toContain('No syntax errors detected');
});