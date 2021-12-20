<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class ConfigService
{
    protected const TAILWIND = 'tailwind';
    private string $extension = '';
    private bool $isModel = false;

    public function isBackend (array $config): bool
    {
        return isset($config['backend']) and
            $config['backend'] === true;
    }

    public function isAllInOne (array $config): bool
    {
        return isset($config['allInOne']) and
            $config['allInOne'] === true;
    }

    public function replace (array $config, string $type, string $stub): string
    {
        return (new ReplaceService())->fromConfig($config, $type, $stub);
    }

    public function disk (array $config): string
    {
        return $this->route($config);
    }

    public function route (array $config): string
    {
        return $config['route']['path']
            ?? Str::lower($this->table($config));
    }

    public function table (array $config): string
    {
        $table = $config['table']['name']
            ?? Str::plural(Str::lower($this->model($config)));
        return $table . $this->extension;
    }

    public function model (array $config): string
    {
        return ucfirst($config['model']['name']) . $this->extension;
    }

    public function hasModelUse (array $config): bool
    {
        return isset($config['model']['use']);
    }

    public function hasUserstamps (array $config): bool
    {
        return in_array('Userstamps', $config['model']['use']);
    }

    public function livewireSortField (array $config): string
    {
        return $config['livewire']['properties']['sortField'];
    }

    public function layout (array $config): string
    {
        return $config['view']['layout'] ?? self::TAILWIND;
    }

    public function hasViewTitle (array $config): bool
    {
        return isset($config['view']['title']) and
            $config['view']['title'] !== false;
    }

    public function migration (array $config): string
    {
        return now()->format('Y_m_d_His') . '_create_'
            . gen()->config()->table($config) . '_table' . $this->extension;
    }

    public function factory (array $config): string
    {
        return gen()->config()->model($config) . 'Factory' . $this->extension;
    }

    public function seeder (array $config): string
    {
        return gen()->config()->model($config) . 'Seeder' . $this->extension;
    }

    public function view (array $config): string
    {
        $view = $this->isModel
            ? strtolower(gen()->config()->model($config) . '.blade')
            : gen()->config()->table($config) . '.blade';
        return $view . $this->extension;
    }

    public function livewire (array $config): string
    {
        $livewire = $this->isModel
            ? ucfirst(gen()->config()->model($config)) . 'Livewire'
            : ucfirst(gen()->config()->table($config)) . 'Livewire';
        return $livewire . $this->extension;
    }

    public function test (array $config): string
    {
        return ucfirst(gen()->config()->table($config)) . 'LivewireTest' . $this->extension;
    }

    public function withExtension (): ConfigService
    {
        $this->extension = '.php';
        return $this;
    }

    public function isModel (): ConfigService
    {
        $this->isModel = true;
        return $this;
    }

    #[Pure] public function requiredFields (array $config): array
    {
        $requiredFields = [];

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isRequired($field))
            {
                $requiredFields[] = $field;
            }
        }

        return $requiredFields;
    }

}