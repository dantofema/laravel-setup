<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Tests\CreateService;
use Dantofema\LaravelSetup\Services\Tests\EditService;
use Dantofema\LaravelSetup\Services\Tests\EditSlugService;
use Dantofema\LaravelSetup\Services\Tests\RequiredEditService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTestCommand extends Command
{
    use CommandTrait;

    public $signature = 'generate:test {path : path to the config file } {--force}';
    public $description = 'Test file generator';
    protected array $config;
    private EditSlugService $editSlugService;
    private RequiredEditService $requiredService;
    private CreateService $saveService;
    private EditService $editService;

    public function __construct ()
    {
        parent::__construct();
        $this->editSlugService = new EditSlugService();
        $this->requiredService = new RequiredEditService();
        $this->saveService = new CreateService();
        $this->editService = new EditService();
    }

    /**
     * @throws \Exception
     */
    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        $this->init(['test']);

        foreach ($this->properties as $property)
        {
            $this->put($property['type'], $this->replace($property));
        }

        return true;
    }

    private function replace (array $property): string
    {
        $property['stub'] = $this->getUse($property['stub']);
        $property['stub'] = $this->getUri($property['stub']);
        $property['stub'] = $this->getField($property['stub']);
        $property['stub'] = $this->editSlug($property['stub']);
        $property['stub'] = $this->getDisk($property['stub']);
        $property['stub'] = $this->requiredService->get($this->config, $property['stub']);
        $property['stub'] = $this->saveService->get($this->config, $property['stub']);
        $property['stub'] = $this->editService->file($this->config, $property['stub']);
        return $property['stub'] . File::get(__DIR__ . '/../Stubs/tests/extra-methods.stub');
    }

    private function getUse (string $stub): string
    {
        $replace = 'use ' . gen()->getNamespace($this->config, 'livewire', true) . PHP_EOL;
        return str_replace(':use:', $replace, $stub);
    }

    private function getUri (string $stub): string
    {
        $uri = $this->config['backend'] ? 'sistema/' : '';
        return str_replace(
            ':uri:',
            $uri . $this->config['route']['path'],
            $stub
        );
    }

    private function getField (string $stub): string
    {
        $field = $this->config['fields'][0];

        return str_replace(
            ':field:',
            $field['name'],
            $stub
        );
    }

    private function editSlug (string $stub): string
    {
        return str_replace(
            ':edit-slug:',
            $this->editSlugService->get($this->config),
            $stub
        );
    }

    private function getDisk (string $stub): string
    {
        $disk = '';
        foreach ($this->config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                $disk = "Storage::fake(':disk:');";
                $disk .= "\$this->newFile = '';";
            }
        }
        return str_replace(':disk:', $disk, $stub);
    }
}
