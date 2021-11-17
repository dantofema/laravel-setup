<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class TextService
{
    private NameService $name;
    private PathService $path;
    private array $config;

    #[Pure]
    public function __construct ()
    {
        $this->name = new NameService();
        $this->path = new PathService();
    }

    public function config (array $config): TextService
    {
        $this->config = $config;
        return $this;
    }

    public function path (string $type): string
    {
        return $this->path->$type($this->config)->get();
    }

    public function filename (string $type): string
    {
        return $this->name->$type($this->config)->file();
    }

    public function namespace (string $type): string
    {
        $name = $this->name($type);
        return $this->path->$type($this->config)->namespace($name);
    }

    public function name (string $type): string
    {
        return $this->name->$type($this->config)->get();
    }

    public function namespaceFolder (string $type): string
    {
        $name = $this->name($type);
        $namespace = $this->path->$type($this->config)->namespace($name);
        return substr(str_replace($name, '', $namespace), 0, -2);
    }

    public function renderView (): string
    {
        $name = 'livewire.';
        $name .= $this->config['backend'] ? 'backend.' : 'frontend.';
        return $name . $this->config['table']['name'];
    }
}