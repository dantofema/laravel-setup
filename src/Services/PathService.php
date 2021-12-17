<?php

namespace Dantofema\LaravelSetup\Services;

class PathService
{
    private const LIVEWIRE = 'app/Http/Livewire/';
    private const MODEL = 'app/Models/';
    private const MIGRATION = 'database/migrations/';
    private const FACTORY = 'database/factories/';
    private const TEST = 'tests/Feature/';
    private const VIEW = 'resources/views/livewire/';
    private const ROUTE = 'routes/';
    private bool $isModel = false;
    private bool $withFile = true;

    public function renderView (array $config): string
    {
        $path = 'livewire.';
        $path .= gen()->config()->isBackend($config) ? 'backend.' : 'frontend.';

        return $path . ($this->isModel
                ? strtolower(gen()->config()->model($config))
                : gen()->config()->table($config));
    }

    public function route (): string
    {
        return self::ROUTE . 'web.php';
    }

    public function livewire (array $config): string
    {
        $path = self::LIVEWIRE . (gen()->config()->isBackend($config) ? 'Backend/' : 'Frontend/');

        if ($this->withFile)
        {
            $path .= $this->isModel
                ? gen()->config()->withExtension()->isModel()->livewire($config)
                : gen()->config()->withExtension()->livewire($config);
        }

        return $path;
    }

    public function view (array $config): string
    {
        $path = self::VIEW . (gen()->config()->isBackend($config) ? 'backend/' : 'frontend/');

        if ($this->withFile)
        {
            $path .= $this->isModel
                ? gen()->config()->withExtension()->isModel()->view($config)
                : gen()->config()->withExtension()->view($config);
        }
        return $path;
    }

    public function isModel (): PathService
    {
        $this->isModel = true;
        return $this;
    }

    public function test (array $config): string
    {
        $path = self::TEST . (gen()->config()->isBackend($config) ? 'Backend/' : 'Frontend/');
        return $this->withFile
            ? $path . gen()->config()->withExtension()->test($config)
            : $path;
    }

    public function model (array $config): string
    {
        return $this->withFile
            ? self::MODEL . gen()->config()->withExtension()->model($config)
            : self::MODEL;
    }

    public function migration (array $config): string
    {
        return $this->withFile
            ? self::MIGRATION . gen()->config()->withExtension()->migration($config)
            : self::MIGRATION;
    }

    public function factory (array $config): string
    {
        return $this->withFile
            ? self::FACTORY . gen()->config()->withExtension()->factory($config)
            : self::FACTORY;
    }

    public function withOutFile (): PathService
    {
        $this->withFile = false;
        return $this;
    }
}
