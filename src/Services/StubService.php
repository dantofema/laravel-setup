<?php

namespace Dantofema\LaravelSetup\Services;

class StubService
{
    private array $stubs = [
        'viewAllInOne' => '/../Stubs/view/jetstream/all-in-one.blade.php.stub',
        'viewCollection' => '/../Stubs/view/collection.blade.php.stub',
        'viewModel' => '/../Stubs/view/model.blade.php.stub',

        'livewireAllInOne' => '/../Stubs/livewire/livewire-all-in-one.php.stub',
        'livewire' => '/../Stubs/livewire/livewire.php.stub',

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