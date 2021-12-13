<?php

use Dantofema\LaravelSetup\Services\PathService;

it('get path', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');
    $pathService = new PathService();

    expect($pathService->get($config, 'livewire'))
        ->toEqual('app/Http/Livewire/Backend/PostsLivewire.php');

    expect($pathService->get($config, 'livewireAllInOne'))
        ->toEqual('app/Http/Livewire/Backend/PostsLivewire.php');

    expect($pathService->get($config, 'model'))
        ->toEqual('app/Models/Post.php');

    expect($pathService->get($config, 'viewAllInOne'))
        ->toEqual('resources/views/livewire/backend/posts.blade.php');

    expect($pathService->get($config, 'viewModel'))
        ->toEqual('resources/views/livewire/backend/post.blade.php');

    expect($pathService->get($config, 'viewCollection'))
        ->toEqual('resources/views/livewire/backend/posts.blade.php');

    expect($pathService->get($config, 'migration'))
        ->toContain('create_posts_table.php');

    expect($pathService->get($config, 'factory'))
        ->toEqual('database/factories/PostFactory.php');

    expect($pathService->get($config, 'test'))
        ->toEqual('tests/Feature/Backend/PostsLivewireTest.php');
});

it('get namespace with name', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');
    $pathService = new PathService();

    expect($pathService->namespace($config, 'livewire'))
        ->toEqual('App\Http\Livewire\Backend\PostsLivewire;');

    expect($pathService->namespace($config, 'model'))
        ->toEqual('App\Models\Post;');

    expect($pathService->namespace($config, 'factory'))
        ->toEqual('Database\Factories\PostFactory;');
});

it('get namespace without name', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');
    $pathService = new PathService();

    expect($pathService->namespace($config, 'livewire', false))
        ->toEqual('App\Http\Livewire\Backend;');

    expect($pathService->namespace($config, 'model', false))
        ->toEqual('App\Models;');

    expect($pathService->namespace($config, 'factory', false))
        ->toEqual('Database\Factories;');
});
