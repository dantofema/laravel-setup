<?php

namespace Dantofema\LaravelSetup\Services;

class StubService
{
    private array $stubs = [
        'view' => '/../Stubs/view/jetstream/basic.blade.php.stub',
        'view.collection' => '',
        'view.model' => '',
        'livewire' => '/../Stubs/livewire/Livewire.php.stub',
        'livewire.collection' => '',
        'livewire.model' => '',
        'factory' => '/../Stubs/ModelFactory.php.stub',
        'migration' => '/../Stubs/migration.php.stub',
        'migration.pivot' => '/../Stubs/pivot.php.stub',
        'model' => '/../Stubs/Model.php.stub',
        'test' => '/../Stubs/test.stub',

    ];

    public function get (string $stub): string
    {
        return __DIR__ . $this->stubs[$stub];
    }
}