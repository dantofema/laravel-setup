<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateViewCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/view.stub';
    protected const DIRECTORY = 'resources/views/livewire';
    public $signature = 'generate:view {path : path to the config file } {--force}';
    public $description = 'View file generator';
    protected array $config;

    public function handle (): bool
    {
        File::ensureDirectoryExists(self::DIRECTORY . '/backend/');
        File::ensureDirectoryExists(self::DIRECTORY . '/frontend/');

        $this->init('view');

        return $this->create();
    }

    public function create (): bool
    {
        return File::put(
            Text::config($this->config)->path('view'),
            $this->replace());
    }

    private function replace (): string
    {
        $this->stub = $this->getTitle();
        return $this->stub;
    }

    private function getTitle (): string
    {
        return str_replace(':title:',
            $this->config['view']['title'],
            $this->stub);
    }

}
