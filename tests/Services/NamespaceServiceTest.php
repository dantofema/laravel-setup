<?php

it('get namespaces', closure: function () {
    $config = include(__DIR__ . '/../config/default.php');

    $methods = [
        'livewire' => 'App\Http\Livewire\Backend;',
        'model' => 'App\Models;',
        'factory' => 'Database\Factories;',
    ];
    foreach ($methods as $method => $result)
    {
        expect(gen()->namespace()->$method($config))->toEqual($result);
    }

    expect(gen()->namespace()->withFile()->livewire($config))
        ->toEqual('App\Http\Livewire\Backend\PostsLivewire;');
});

