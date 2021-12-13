<?php

use Dantofema\LaravelSetup\Services\NameService;

it('get Text', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');
    $name = new NameService();

    expect($name->get($config, 'livewire'))->toEqual('PostsLivewire');
    expect($name->get($config, 'livewire', true))->toEqual('PostsLivewire.php');

    expect($name->get($config, 'model'))->toEqual('Post');
    expect($name->get($config, 'model', true))->toEqual('Post.php');

    expect($name->get($config, 'seeder'))->toEqual('PostSeeder');
    expect($name->get($config, 'seeder', true))->toEqual('PostSeeder.php');

    expect($name->get($config, 'viewAllInOne'))->toEqual('posts.blade');
    expect($name->get($config, 'viewAllInOne', true))->toEqual('posts.blade.php');
    expect($name->get($config, 'viewCollection'))->toEqual('posts.blade');
    expect($name->get($config, 'viewCollection', true))->toEqual('posts.blade.php');
    expect($name->get($config, 'viewModel'))->toEqual('post.blade');
    expect($name->get($config, 'viewModel', true))->toEqual('post.blade.php');

    expect($name->get($config, 'migration'))->toContain('create_posts_table');
    expect($name->get($config, 'migration', true))->toContain('create_posts_table.php');

    expect($name->get($config, 'factory'))->toEqual('PostFactory');
    expect($name->get($config, 'factory', true))->toEqual('PostFactory.php');

    expect($name->get($config, 'test'))->toEqual('PostsLivewireTest');
    expect($name->get($config, 'test', true))->toEqual('PostsLivewireTest.php');
});
