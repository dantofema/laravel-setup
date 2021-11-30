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
    private string $file;
    private string $path;
    private array $config;
    private NameService $name;

    #[Pure]
    public function __construct()
    {
        $this->name = new NameService();
    }

    public function livewire(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->livewire($config)->file();
        $this->path = self::LIVEWIRE . ($config['backend'] ? 'Backend/' : 'Frontend/');

        return $this;
    }

    public function model(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->model($config)->file();
        $this->path = self::MODEL;

        return $this;
    }

    public function migration(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->migration($config)->file();
        $this->path = self::MIGRATION;

        return $this;
    }

    public function view(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->view($config)->file();
        $this->path = self::VIEW . ($config['backend'] ? 'backend/' : 'frontend/');

        return $this;
    }

    public function test(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->test($config)->file();
        $this->path = self::TEST . ($config['backend'] ? 'Backend/' : 'Frontend/');

        return $this;
    }

    public function factory(array $config): PathService
    {
        $this->config = $config;
        $this->file = $this->name->factory($config)->file();
        $this->path = self::FACTORY;

        return $this;
    }

    public function route(): PathService
    {
        $this->file = 'web.php';
        $this->path = self::ROUTE;

        return $this;
    }

    public function get(): string
    {
        return $this->path . $this->file;
    }

    public function namespace(string $name): string
    {
        $folders = explode('/', $this->path);
        $namespace = '';

        foreach ($folders as $folder) {
            $namespace .= $folder != '' ? ucfirst($folder) . '\\' : '';
        }

        return $namespace . $name . ';';
    }
}
