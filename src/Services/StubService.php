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
        'factory' => '/../Stubs/ModelFactory.php.stub',
        'migration' => '/../Stubs/migration.php.stub',
        'migrationPivot' => '/../Stubs/pivot.php.stub',
        'model' => '/../Stubs/Model.php.stub',
    ];
    private bool $isModel = false;
    private bool $isPivot = false;

    public function isModel (): StubService
    {
        $this->isModel = true;
        return $this;
    }

    public function isPivot (): StubService
    {
        $this->isPivot = true;
        return $this;
    }

    public function model (): string
    {
        return $this->getFileContent($this->stubs['model']);
    }

    private function getFileContent (string $path): string
    {
        return file_get_contents(__DIR__ . $path);
    }

    public function factory (): string
    {
        return $this->getFileContent($this->stubs['factory']);
    }

    public function test (): string
    {
        return $this->getFileContent($this->stubs['test']);
    }

    public function view (array $config): string
    {
        if (gen()->config()->isAllInOne($config))
        {
            return $this->getFileContent($this->stubs['viewAllInOne']);
        }

        return $this->isModel
            ? $this->getFileContent($this->stubs['viewModel'])
            : $this->getFileContent($this->stubs['viewCollection']);
    }

    public function livewire (array $config): string
    {
        return gen()->config()->isAllInOne($config)
            ? $this->getFileContent($this->stubs['livewire'])
            : $this->getFileContent($this->stubs['livewireAllInOne']);
    }

    public function migration (): string
    {
        return $this->isPivot
            ? $this->getFileContent($this->stubs['migrationPivot'])
            : $this->getFileContent($this->stubs['migration']);
    }
}