<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

it('livewire directory is empty', closure: function () {
    expect(count(File::files('app/Http/Livewire')))
        ->toEqual(0);
});

it('allInOne - generate livewire file', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/all-in-one.php'))
        ->toEqual(1);

    $files = File::files('app/Http/Livewire/Backend');

    expect(count($files))
        ->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension())
        ->toEqual('PostsLivewire');
});

it('generate livewire file', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $files = File::files('app/Http/Livewire/Backend');

    expect(count($files))
        ->toEqual(1);

    expect($files[0]->getFilenameWithoutExtension() == 'PostsLivewire')
        ->toBeTrue();
});

it('allInOne - replaces', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/all-in-one.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/all-in-one.php';
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
        gen()->path()->renderView($config, 'viewAllInOne'),
    ]))
        ->toBeTrue();

    expect(str_contains($content, ':namespace:'))
        ->toBeFalse();
});

it('replaces in livewireModel', closure: function () {
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
        gen()->path()->renderView($config, 'viewModel'),
        gen()->path()->renderView($config, 'viewCollection'),
    ]))
        ->toBeTrue();

    expect(str_contains($content, ':namespace:'))
        ->toBeFalse();
});

it('replaces in livewireCollection', closure: function () {
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
        gen()->path()->renderView($config, 'viewModel'),
        gen()->path()->renderView($config, 'viewCollection'),
    ]))
        ->toBeTrue();

    expect(str_contains($content, ':namespace:'))
        ->toBeFalse();
});

it('allInOne - livewire file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/all-in-one.php');
    $config = include(__DIR__ . '/config/all-in-one.php');
//    dump(File::get(gen()->getPath($config, 'livewire')));
    expect(shell_exec("php -l -f " . gen()->getPath($config, 'livewire')))
        ->toContain('No syntax errors detected');
});

it('livewire file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');
    $config = include(__DIR__ . '/config/default.php');
//    dump(File::get(gen()->getPath($config, 'livewire')));
    expect(shell_exec("php -l -f " . gen()->getPath($config, 'livewire')))
        ->toContain('No syntax errors detected');
});;

it('allInOne - route file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/all-in-one.php');

    expect(shell_exec("php -l -f " . gen()->path()->route()))
        ->toContain('No syntax errors detected');
});

it('livewireModel route file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');
//    dump(File::get(gen()->path()->route()));
    expect(shell_exec("php -l -f " . gen()->path()->route()))
        ->toContain('No syntax errors detected');
});

it('livewireCollection route file check syntax', closure: function () {
    Artisan::call('generate:livewire tests/config/default.php');

    expect(shell_exec("php -l -f " . gen()->path()->route()))
        ->toContain('No syntax errors detected');
});