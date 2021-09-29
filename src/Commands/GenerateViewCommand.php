<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateViewCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/view.stub';
    protected const DIRECTORY = 'resources/views/livewire';
    public $signature = 'generate:view {path : path to the config file }';
    public $description = 'View file generator';
    protected array $config;

    public function handle (): bool
    {
        if ( ! $this->init())
        {
            return false;
        };

        return $this->create();
    }

    public function create (): bool
    {
        $path = self::DIRECTORY . $this->config['view']['subdirectory'];

        return File::put(
            $path . $this->getFileName(),
            $this->replace());
    }

    private function getFileName (): string
    {
        return strtolower($this->config['model']['name'] . '-livewire.php');
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
