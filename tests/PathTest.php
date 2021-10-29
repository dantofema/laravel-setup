<?php

use Dantofema\LaravelSetup\Services\PathService;

it('get path', closure: function () {
    $config = include(__DIR__ . '/config/default.php');
    $path = new PathService();

    expect($path->livewire($config)->get())
        ->toEqual('app/Http/Livewire/Backend/PostsLivewire.php');

    expect($path->model($config)->get())
        ->toEqual('app/Models/Post.php');

    expect($path->view($config)->get())
        ->toEqual('resources/views/livewire/backend/posts-livewire.blade.php');

    expect($path->migration($config)->get())
        ->toContain('create_posts_table.php');

    expect($path->factory($config)->get())
        ->toEqual('database/factories/PostFactory.php');

    expect($path->test($config)->get())
        ->toEqual('tests/Feature/Backend/PostsLivewireTest.php');
});
