<?php

namespace Dantofema\LaravelSetup\Services;

class StubService
{
    private array $stubs = [
        'view' => '/../Stubs/view/jetstream/basic.blade.php.stub',
        'viewCollection' => '/../Stubs/livewire/collection.php.stub',
        'viewModel' => '/../Stubs/livewire/model.php.stub',
        'livewire' => '/../Stubs/livewire/Livewire.php.stub',
        'livewireCollection' => '',
        'livewireModel' => '',
        'factory' => '/../Stubs/ModelFactory.php.stub',
        'migration' => '/../Stubs/migration.php.stub',
        'migrationPivot' => '/../Stubs/pivot.php.stub',
        'model' => '/../Stubs/Model.php.stub',
        'test' => '/../Stubs/test.stub',
        'testCollection' => '',
        'testModel' => '',
    ];

    public function get (string $stub): string
    {
        return __DIR__ . $this->stubs[$stub];
    }
}