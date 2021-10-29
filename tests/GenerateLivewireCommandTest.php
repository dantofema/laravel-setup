<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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

it('replace namespace', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['livewire']['namespace']))
        ->toBeTrue();

    expect(str_contains($content, ':namespace:'))
        ->toBeFalse();
});

it('replace use models', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['livewire']['useModels'][0]))
        ->toBeTrue();
    expect(str_contains($content, $config['livewire']['useModels'][1]))
        ->toBeTrue();
    expect(str_contains($content, $config['livewire']['useModels'][2]))
        ->toBeTrue();

    expect(str_contains($content, ':useModels:'))
        ->toBeFalse();
});

it('replace model name', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['model']['name']))
        ->toBeTrue();

    expect(str_contains($content, ':modelName:'))
        ->toBeFalse();
});

it('replace sort field', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['livewire']['properties']['sortField']))
        ->toBeTrue();

    expect(str_contains($content, ':sortField:'))
        ->toBeFalse();
});

it('replace new image', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, 'public $newImage;'))
        ->toBeTrue();

    expect(str_contains($content, ':newImage:'))
        ->toBeFalse();
});

it('replace new image when without image', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/without-image.php'))
        ->toEqual(1);

    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, 'public $newImage;'))
        ->toBeFalse();
    expect(str_contains($content, ':newImage:'))
        ->toBeFalse();
});

it('replace editing', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['livewire']['properties']['editing']))
        ->toBeTrue();

    expect(str_contains($content, ':editing:'))
        ->toBeFalse();
});

it('replace detach', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    $belongsToMany = $config['model']['relationships']['belongsToMany'][0];
    expect(str_contains($content, "\$this->editing->$belongsToMany[0]()->detach();"))
        ->toBeTrue();

    expect(str_contains($content, ':detach:'))
        ->toBeFalse();
});

it('replace model argument', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    $modelArgument = $config['model']['name'] . ' ' . '$' . strtolower($config['model']['name']);
    expect(str_contains($content, $modelArgument))
        ->toBeTrue();

    expect(str_contains($content, ':modelArgument:'))
        ->toBeFalse();
});

it('replace variable name', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, '$' . strtolower($config['model']['name'])))
        ->toBeTrue();

    expect(str_contains($content, ':varModel:'))
        ->toBeFalse();
});

it('replace new image in edit method', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, '$this->newImage = "";'))
        ->toBeTrue();

    expect(str_contains($content, ':editNewImage:'))
        ->toBeFalse();
});

it('replace new image in create method', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, '$this->newImage = null;'))
        ->toBeTrue();

    expect(str_contains($content, ':createNewImage:'))
        ->toBeFalse();
});

it('replace new image in save method', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    $field = $config['livewire']['properties']['newImage']['field'];
    $disk = $config['livewire']['properties']['newImage']['disk'];
    expect(str_contains($content, "\$this->setNewImage('$field', '$disk');"))
        ->toBeTrue();

    expect(str_contains($content, ':saveNewImage:'))
        ->toBeFalse();
});

it('replace slug in save method', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    $field = $config['livewire']['save']['slug'];
    expect(str_contains($content, "\$this->setSlug('$field');"))
        ->toBeTrue();

    expect(str_contains($content, ':saveNewImage:'))
        ->toBeFalse();
});

it('replace rules', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    foreach ($config['livewire']['rules'] as $rule)
    {
        expect(str_contains($content, $rule))
            ->toBeTrue();
    }

    expect(str_contains($content, ':rules:'))
        ->toBeFalse();
});

it('replace view', closure: function () {
    expect(Artisan::call('generate:livewire tests/config/default.php'))
        ->toEqual(1);

    $config = include __DIR__ . '/config/default.php';
    $content = File::get('app/Http/Livewire/Backend/PostsLivewire.php');

    expect(str_contains($content, $config['livewire']['view']))
        ->toBeTrue();

    expect(str_contains($content, ':view:'))
        ->toBeFalse();
});