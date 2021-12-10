<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class PathService
{
    private const LIVEWIRE = 'app/Http/Livewire/';
    private const MODEL = 'app/Models/';
    private const MIGRATION = 'database/migrations/';
    private const FACTORY = 'database/factories/';
    private const TEST = 'tests/Feature/';
    private const VIEW = 'resources/views/livewire/';
    private const ROUTE = 'routes/';
    private NameService $nameService;

    #[Pure] public function __construct ()
    {
        $this->nameService = new NameService();
    }

    public function get (array $config, string $type): string
    {
        return $this->$type($config) . $this->nameService->get($config, $type, true);
    }

    public function renderView (array $config): string
    {
        $name = 'livewire.';
        $name .= $config['backend'] ? 'backend.' : 'frontend.';

        return $name . $config['table']['name'];
    }

    public function namespace (array $config, string $type, bool $withName = true): string
    {
        $path = $this->$type($config);
        $folders = array_filter(explode('/', $path));
        $namespace = '';

        foreach ($folders as $key => $folder)
        {
            $namespace .= ucfirst($folder);

            if ($key !== array_key_last($folders))
            {
                $namespace .= "\\";
            }
        }

        return $withName
            ? $namespace . "\\" . $this->nameService->get($config, $type) . ';'
            : $namespace . ';';
    }

    public function route (): string
    {
        return self::ROUTE . 'web.php';
    }

    #[Pure] protected function livewireCollection (array $config): string
    {
        return $this->livewire($config);
    }

    protected function livewire (array $config): string
    {
        return self::LIVEWIRE . ($config['backend'] ? 'Backend/' : 'Frontend/');
    }

    #[Pure] protected function livewireModel (array $config): string
    {
        return $this->livewire($config);
    }

    protected function model (): string
    {
        return self::MODEL;
    }

    protected function migration (): string
    {
        return self::MIGRATION;
    }

    protected function view (array $config): string
    {
        return self::VIEW . ($config['backend'] ? 'backend/' : 'frontend/');
    }

    protected function test (array $config): string
    {
        return self::TEST . ($config['backend'] ? 'Backend/' : 'Frontend/');
    }

    protected function factory (): string
    {
        return self::FACTORY;
    }
}
