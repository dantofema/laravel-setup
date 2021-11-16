<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
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

    public function __construct ()
    {
        parent::__construct();
        $this->editSlugService = new EditSlugService();
        $this->requiredService = new RequiredEditService();
        $this->saveService = new CreateService();
        $this->editService = new EditService();
    }

    public function handle (): bool
    {
        $this->init('test');

        return $this->create();
    }

    public function create (): bool
    {
        return File::put(
            Text::config($this->config)->path('test'),
            $this->replace());
    }

    private function replace (): string
    {
        $this->stub = $this->getUse();
        $this->stub = $this->getUri();
        $this->stub = $this->getField();
        $this->stub = $this->requiredService->get($this->config, $this->stub);
        $this->stub = $this->editSlug();
        $this->stub = $this->getDisk();
        $this->stub = $this->saveService->get($this->config, $this->stub);
        $this->stub = $this->editService->get($this->config, $this->stub);
        $this->stub = $this->editService->file($this->config, $this->stub);
        return $this->stub . File::get(__DIR__ . '/../Stubs/tests/extra-methods.stub');
    }

    private function getUse (): string|array
    {
        $replace = 'use ' . Text::config($this->config)->namespace('model') . PHP_EOL;
        $replace .= 'use ' . Text::config($this->config)->namespace('livewire') . PHP_EOL;

        $fields = Field::config($this->config)->getRelationships();

        if ( ! empty($fields))
        {
            $replace .= 'use Illuminate\Http\UploadedFile;' . PHP_EOL;

            foreach ($fields as $field)
            {
                $replace .= 'use ' . $field['relationships']['namespace'] . $field['relationships']['model'] . ';' . PHP_EOL;
            }
        }
        return str_replace(':use:', $replace, $this->stub);
    }

    private function getUri (): string
    {
        $uri = $this->config['backend'] ? 'sistema/' : '';
        return str_replace(
            ':uri:',
            $uri . $this->config['route']['path'],
            $this->stub);
    }

    private function getField (): string
    {
        $field = $this->config['fields'][0];

        return str_replace(
            ':field:',
            $field['name'],
            $this->stub);
    }

    private function editSlug (): string
    {
        return str_replace(
            ':edit-slug:',
            $this->editSlugService->get($this->config),
            $this->stub);
    }

    private function getDisk (): string
    {
        $disk = '';
        foreach ($this->config['fields'] as $field)
        {
            if ( ! empty($field['disk']))
            {
                $disk = "Storage::fake('" . $field['disk'] . "');";
                $disk .= "\$this->newFile = '';";
            }
        }
        return str_replace(':disk:', $disk, $this->stub);
    }

}
