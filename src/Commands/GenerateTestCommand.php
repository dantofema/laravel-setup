<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Tests\CreateService;
use Dantofema\LaravelSetup\Services\Tests\EditService;
use Dantofema\LaravelSetup\Services\Tests\EditSlugService;
use Dantofema\LaravelSetup\Services\Tests\RequiredCreateService;
use Dantofema\LaravelSetup\Services\Tests\RequiredEditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTestCommand extends Command
{

    public $signature = 'generate:test {path : path to the config file } {--force}';
    public $description = 'Test file generator';
    protected array $config;
    private EditSlugService $editSlugService;
    private RequiredEditService $requiredEditService;
    private CreateService $createService;
    private EditService $editService;
    private RequiredCreateService $requiredCreateService;

    public function __construct ()
    {
        parent::__construct();
        $this->requiredCreateService = new RequiredCreateService();
        $this->editSlugService = new EditSlugService();
        $this->requiredEditService = new RequiredEditService();
        $this->createService = new CreateService();
        $this->editService = new EditService();
    }

    public function handle (): bool
    {
        $config = include $this->argument('path');
        $this->info(gen()->config()->test($config));

        if ($this->option('force'))
        {
            gen()->delete()->test($config);
        }

        $path = gen()->path()->test($config);
        $stub = gen()->stub()->test();

        File::put($path, $this->replace($config, $stub));

        $this->warn('end');
        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $stub = $this->getUse($config, $stub);
        $stub = $this->getUri($config, $stub);
        $stub = $this->getField($config, $stub);
        $stub = $this->editSlug($config, $stub);
        $stub = $this->getDisk($config, $stub);
        $stub = $this->requiredCreateService->get($config, $stub);
        $stub = $this->createService->get($config, $stub);
        $stub = $this->requiredEditService->get($config, $stub);
        $stub = $this->editService->get($config, $stub);
        $stub = $stub . File::get(__DIR__ . '/../Stubs/tests/extra-methods.stub');
        return gen()->config()->replace($config, 'test', $stub);
    }

    private function getUse (array $config, string $stub): string
    {
        $replace = 'use ' . gen()->namespace()->withFile()->livewire($config) . PHP_EOL;
        return str_replace(':use:', $replace, $stub);
    }

    private function getUri (array $config, string $stub): string
    {
        $uri = $config['backend'] ? 'sistema/' : '';
        return str_replace(
            ':uri:',
            $uri . $config['route']['path'],
            $stub
        );
    }

    private function getField (array $config, string $stub): string
    {
        $field = $config['fields'][0];

        return str_replace(
            ':field:',
            $field['name'],
            $stub
        );
    }

    private function editSlug (array $config, string $stub): string
    {
        return str_replace(
            ':edit-slug:',
            $this->editSlugService->get($config),
            $stub
        );
    }

    private function getDisk (array $config, string $stub): string
    {
        $disk = '';
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                $disk = "Storage::fake(':disk:');";
                $disk .= "\$this->new"
                    . ucfirst($field['name']) . " = '';";
            }
        }
        return str_replace(':disk:', $disk, $stub);
    }
}
