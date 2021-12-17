<?php

it('get path', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');

    $methods = [
        'model' => 'app/Models/Post.php',
        'factory' => 'database/factories/PostFactory.php',
        'test' => 'tests/Feature/Backend/PostsLivewireTest.php',
    ];
    foreach ($methods as $method => $result)
    {
        expect(gen()->path()->$method($config))->toEqual($result);
    }

    expect(gen()->path()->livewire($config))
        ->toEqual('app/Http/Livewire/Backend/PostsLivewire.php');
    expect(gen()->path()->isModel()->livewire($config))
        ->toEqual('app/Http/Livewire/Backend/PostLivewire.php');

    expect(gen()->path()->view($config))
        ->toEqual('resources/views/livewire/backend/posts.blade.php');
    expect(gen()->path()->isModel()->view($config))
        ->toEqual('resources/views/livewire/backend/post.blade.php');

    expect(gen()->path()->migration($config))
        ->toContain('create_posts_table.php');
});



