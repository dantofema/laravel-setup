<?php

namespace Dantofema\LaravelSetup\Services;

class NameService
{

    public function get (array $config, string $type, bool $whitExtension = false): string
    {
        return $whitExtension
            ? $this->$type($config) . '.php'
            : $this->$type($config);
    }

    protected function disk (array $config): string
    {
        return strtolower($config['model']['name']);
    }

    protected function test (array $config): string
    {
        return ucfirst($config['table']['name']) . 'LivewireTest';
    }

    protected function livewire (array $config): string
    {
        return ucfirst($config['table']['name']) . 'Livewire';
    }

    protected function livewireAllInOne (array $config): string
    {
        return ucfirst($config['table']['name']) . 'Livewire';
    }

    protected function model (array $config): string
    {
        return ucfirst($config['model']['name']);
    }

    protected function table (array $config): string
    {
        return $config['table']['name'];
    }

    protected function viewModel (array $config): string
    {
        return strtolower($config['model']['name']) . '.blade';
    }

    protected function viewCollection (array $config): string
    {
        return $this->viewAllInOne($config);
    }

    protected function viewAllInOne (array $config): string
    {
        return $config['table']['name'] . '.blade';
    }

    protected function migration (array $config): string
    {
        return now()->format('Y_m_d_His') . '_create_'
            . $config['table']['name'] . '_table';
    }

    protected function factory (array $config): string
    {
        return $config['model']['name'] . 'Factory';
    }

    protected function seeder (array $config): string
    {
        return $config['model']['name'] . 'Seeder';
    }

}
