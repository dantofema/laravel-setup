<?php

namespace Dantofema\LaravelSetup\Services;

class StubService
{
    private array $stubs = [
        'view' => '/../Stubs/view/jetstream/basic.blade.php.stub',
        'viewCollection' => '/../Stubs/view/jetstream/basic.blade.php.stub',
        'viewModel' => '/../Stubs/view/jetstream/basic.blade.php.stub',

        'livewire' => '/../Stubs/livewire/Livewire.php.stub',
        'livewireCollection' => '/../Stubs/livewire/Livewire.php.stub',
        'livewireModel' => '/../Stubs/livewire/Livewire.php.stub',

        'test' => '/../Stubs/test.stub',
        'testCollection' => '/../Stubs/test.stub',
        'testModel' => '/../Stubs/test.stub',

        'factory' => '/../Stubs/ModelFactory.php.stub',
        'migration' => '/../Stubs/migration.php.stub',
        'migrationPivot' => '/../Stubs/pivot.php.stub',
        'model' => '/../Stubs/Model.php.stub',
    ];

    public function get (string $type): string
    {
        return __DIR__ . $this->stubs[$type];
    }
}