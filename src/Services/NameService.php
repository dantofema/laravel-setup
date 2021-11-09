<?php

namespace Dantofema\LaravelSetup\Services;

class NameService
{
    protected string $name;

    public function livewire (array $config): NameService
    {
        $this->name = ucfirst($config['table']['name']) . 'Livewire';
        return $this;
    }

    public function model (array $config): NameService
    {
        $this->name = ucfirst($config['model']['name']);
        return $this;
    }

    public function table (array $config): NameService
    {
        $this->name = $config['table']['name'];
        return $this;
    }

    public function view (array $config): NameService
    {
        $this->name = $config['table']['name'] . '.blade';
        return $this;
    }

    public function migration (array $config): NameService
    {
        $this->name = now()->format('Y_m_d_His') . '_create_'
            . $config['table']['name'] . '_table';
        return $this;
    }

    public function factory (array $config): NameService
    {
        $this->name = $config['model']['name'] . 'Factory';
        return $this;
    }

    public function seeder (array $config): NameService
    {
        $this->name = $config['model']['name'] . 'Seeder';
        return $this;
    }

    public function test (array $config): NameService
    {
        $this->name = ucfirst($config['table']['name']) . 'LivewireTest';
        return $this;
    }

    public function get (): string
    {
        return $this->name;
    }

    public function file (): string
    {
        return $this->name . '.php';
    }
}