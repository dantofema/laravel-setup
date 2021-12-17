<?php

namespace Dantofema\LaravelSetup\Services;

class NamespaceService
{
    private bool $withFile = false;

    public function livewire (array $config): string
    {
        return $this->get($config, 'livewire');
    }

    private function get (array $config, string $type): string
    {
        $path = gen()->path()->withOutFile()->$type($config);

        $path .= $this->withFile
            ? gen()->config()->$type($config)
            : '';

        $folders = array_filter(
            explode('/', $path)
        );

        $namespace = '';

        foreach ($folders as $key => $folder)
        {
            $namespace .= ucfirst($folder);

            if ($key !== array_key_last($folders))
            {
                $namespace .= "\\";
            }
        }

        return $namespace . ';';
    }

    public function test (array $config): string
    {
        return $this->get($config, 'test');
    }

    public function model (array $config): string
    {
        return $this->get($config, 'model');
    }

    public function factory (array $config): string
    {
        return $this->get($config, 'factory');
    }

    public function withFile (): NamespaceService
    {
        $this->withFile = true;
        return $this;
    }
}