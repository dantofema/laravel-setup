<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
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

    protected const STUB_PATH = '/../Stubs/test.stub';

    public $signature = 'generate:test {path : path to the config file } {--force}';
    public $description = 'Test file generator';
    protected array $config;
    private EditSlugService $editSlugService;
    private RequiredEditService $requiredService;
    private CreateService $saveService;
    private EditService $editService;

    public function __construct()
    {
        parent::__construct();
        $this->editSlugService = new EditSlugService();
        $this->requiredService = new RequiredEditService();
        $this->saveService = new CreateService();
        $this->editService = new EditService();
    }

    public function handle(): bool
    {
        $this->init('test');

        $this->getUse();
        $this->getUri();
        $this->getField();
        $this->editSlug();
        $this->getDisk();
        $this->stub = $this->requiredService->get($this->config, $this->stub);
        $this->stub = $this->saveService->get($this->config, $this->stub);
        $this->stub = $this->editService->file($this->config, $this->stub);
        $this->stub = $this->stub . File::get(__DIR__ . '/../Stubs/tests/extra-methods.stub');
        $this->put($this->stub);

        return true;
    }

    private function getUse(): void
    {
        $replace = 'use ' . Text::config($this->config)->namespace('livewire') . PHP_EOL;
        $this->stub = str_replace(':use:', $replace, $this->stub);
    }

    private function getUri(): void
    {
        $uri = $this->config['backend'] ? 'sistema/' : '';
        $this->stub = str_replace(
            ':uri:',
            $uri . $this->config['route']['path'],
            $this->stub
        );
    }

    private function getField(): void
    {
        $field = $this->config['fields'][0];

        $this->stub = str_replace(
            ':field:',
            $field['name'],
            $this->stub
        );
    }

    private function editSlug(): void
    {
        $this->stub = str_replace(
            ':edit-slug:',
            $this->editSlugService->get($this->config),
            $this->stub
        );
    }

    private function getDisk(): void
    {
        $disk = '';
        foreach ($this->config['fields'] as $field) {
            if ($field['form']['input'] === 'file') {
                $disk = "Storage::fake(':disk:');";
                $disk .= "\$this->newFile = '';";
            }
        }
        $this->stub = str_replace(':disk:', $disk, $this->stub);
    }
}
