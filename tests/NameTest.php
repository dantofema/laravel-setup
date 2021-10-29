<?php

use Dantofema\LaravelSetup\Services\NameService;

it('get Text', closure: function () {
    $config = include('tests/config/default.php');
    $name = new NameService();

    expect($name->livewire($config)->get())->toEqual('PostsLivewire');
    expect($name->livewire($config)->file())->toEqual('PostsLivewire.php');

    expect($name->model($config)->get())->toEqual('Post');
    expect($name->model($config)->file())->toEqual('Post.php');

    expect($name->table($config)->get())->toEqual('posts');

    expect($name->view($config)->get())->toEqual('posts-livewire.blade');
    expect($name->view($config)->file())->toEqual('posts-livewire.blade.php');

    expect($name->migration($config)->get())->toContain('create_posts_table');
    expect($name->migration($config)->file())->toContain('create_posts_table.php');

    expect($name->factory($config)->get())->toEqual('PostFactory');
    expect($name->factory($config)->file())->toEqual('PostFactory.php');

    expect($name->test($config)->get())->toEqual('PostsLivewireTest');
    expect($name->test($config)->file())->toEqual('PostsLivewireTest.php');
});
