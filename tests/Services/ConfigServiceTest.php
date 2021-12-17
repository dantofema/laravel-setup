<?php

it('get string', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');

    $methods = [
        'livewire' => 'PostsLivewire',
        'model' => 'Post',
        'table' => 'posts',
        'factory' => 'PostFactory',
        'seeder' => 'PostSeeder',
        'view' => 'posts.blade',
        'test' => 'PostsLivewireTest',
    ];
    foreach ($methods as $method => $result)
    {
        expect(gen()->config()->$method($config))->toEqual($result);
        expect(gen()->config()->withExtension()->$method($config))
            ->toEqual($result . '.php');
    }

    expect(gen()->config()->isModel()->livewire($config))->toEqual('PostLivewire');
    expect(gen()->config()->isModel()->view($config))->toEqual('post.blade');

    expect(gen()->config()->disk($config))->toEqual('notas');
    expect(gen()->config()->route($config))->toEqual('notas');
    expect(gen()->config()->layout($config))->toEqual('tailwind');
    expect(gen()->config()->livewireSortField($config))->toEqual('created_at');

    expect(gen()->config()->migration($config))->toContain('create_posts_table');
    expect(gen()->config()->withExtension()->migration($config))
        ->toContain('create_posts_table.php');
});

it('get bool', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');

    expect(gen()->config()->isBackend($config))->toBeTrue();
    expect(gen()->config()->isAllInOne($config))->toBeFalse();
    expect(gen()->config()->hasModelUse($config))->toBeTrue();
    expect(gen()->config()->hasViewTitle($config))->toBeFalse();
});
