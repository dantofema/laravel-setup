<?php

namespace Dantofema\LaravelSetup\Commands;

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
        if ( ! $this->init())
        {
            return false;
        }

        $this->route();

        return $this->create();
    }

    public function route ()
    {
        $path = $this->config['model']['path'];
        $name = $this->config['table']['name'];
        $livewire = ucfirst($name) . 'Livewire::class';

        $route = "\r\nRoute::";
        $route .= "get('/$path', $livewire)";
        $route .= $this->config['backend'] ? "->middleware('auth')->prefix('sistema')" : "";
        $route .= "->name('$name');\r\n";

        $haystack = File::get('routes/web.php');

        File::put('routes/web.php', $haystack . $route);
    }

    public function create (): bool
    {
        $path = self::DIRECTORY . ($this->config['backend'] ? '/backend/' : '/frontend/');

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
