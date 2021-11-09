<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\Tests\EditSlugService;
use Dantofema\LaravelSetup\Services\Tests\RequiredService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Dantofema\LaravelSetup\Traits\TestTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTestCommand extends Command

{
    use CommandTrait;
    use TestTrait;

    protected const STUB_PATH = '/../Stubs/test.stub';

    public $signature = 'generate:test {path : path to the config file } {--force}';
    public $description = 'Test file generator';
    protected array $config;
    private EditSlugService $editSlugService;
    private RequiredService $requiredService;

    public function __construct ()
    {
        parent::__construct();
        $this->editSlugService = new EditSlugService();
        $this->requiredService = new RequiredService();
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
        $this->stub = $this->getTable($this->config, $this->stub);
        $this->stub = $this->getUri();
        $this->stub = $this->getModel($this->config, $this->stub);
        $this->stub = $this->getLivewire($this->config, $this->stub);
        $this->stub = $this->getField();
        $this->stub = $this->requiredService->get($this->config, $this->stub);
        $this->stub = $this->editSlug();
        $this->stub = $this->actingAs($this->config, $this->stub);
        return $this->stub;
    }

    private function getUse (): string|array
    {
        $replace = 'use ' . Text::config($this->config)->namespace('model') . "\r\n";
        $replace .= 'use ' . Text::config($this->config)->namespace('livewire') . "\r\n";
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
        $field = $this->config['table']['columns'][0];

        return str_replace(
            ':field:',
            $field[1],
            $this->stub);
    }

    private function editSlug (): string
    {
        return str_replace(
            ':edit-slug:',
            $this->editSlugService->get($this->config),
            $this->stub);
    }

}
