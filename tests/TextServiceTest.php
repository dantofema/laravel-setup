<?php

use Dantofema\LaravelSetup\Facades\Text;

it('get name', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    expect(Text::config($config)->name('livewire'))->toEqual('PostsLivewire');
    expect(Text::config($config)->name('model'))->toEqual('Post');
    expect(Text::config($config)->name('view'))->toEqual('posts-livewire.blade');
    expect(Text::config($config)->name('migration'))->toContain('create_posts_table');
    expect(Text::config($config)->name('factory'))->toEqual('PostFactory');
    expect(Text::config($config)->name('test'))->toEqual('PostsLivewireTest');
    expect(Text::config($config)->name('table'))->toEqual('posts');
});

it('get filename', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    expect(Text::config($config)->filename('livewire'))->toEqual('PostsLivewire.php');
    expect(Text::config($config)->filename('model'))->toEqual('Post.php');
    expect(Text::config($config)->filename('view'))->toEqual('posts-livewire.blade.php');
    expect(Text::config($config)->filename('factory'))->toEqual('PostFactory.php');
    expect(Text::config($config)->filename('migration'))->toContain('create_posts_table');
    expect(Text::config($config)->filename('test'))->toEqual('PostsLivewireTest.php');
});

it('get path', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    expect(Text::config($config)->path('livewire'))
        ->toEqual('app/Http/Livewire/Backend/PostsLivewire.php');
    expect(Text::config($config)->path('model'))
        ->toEqual('app/Models/Post.php');
    expect(Text::config($config)->path('view'))
        ->toEqual('resources/views/livewire/backend/posts-livewire.blade.php');
    expect(Text::config($config)->path('factory'))
        ->toEqual('database/factories/PostFactory.php');
    expect(Text::config($config)->path('migration'))
        ->toContain('create_posts_table');
    expect(Text::config($config)->path('test'))
        ->toEqual('tests/Feature/Backend/PostsLivewireTest.php');
});

it('get namespace', closure: function () {
    $config = include(__DIR__ . '/config/default.php');

    expect(Text::config($config)->namespace('livewire'))
        ->toEqual('App\Http\Livewire\Backend\PostsLivewire;');
    expect(Text::config($config)->namespace('model'))
        ->toEqual('App\Models\Post;');
    expect(Text::config($config)->namespace('factory'))
        ->toEqual('Database\Factories\PostFactory;');
    expect(Text::config($config)->namespace('test'))
        ->toEqual('Tests\Feature\Backend\PostsLivewireTest;');
});
