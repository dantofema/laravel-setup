<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\Views\DeleteModalService;
use Dantofema\LaravelSetup\Services\Views\FormModalService;
use Dantofema\LaravelSetup\Services\Views\TableService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateViewCommand extends Command

{
    use CommandTrait;

    public $signature = 'generate:view {path : path to the config file } {--force}';
    public $description = 'View file generator';
    protected array $config;
    private FormModalService $formModal;
    private DeleteModalService $deleteModal;
    private TableService $tableService;

    public function __construct ()
    {
        parent::__construct();
        $this->formModal = new FormModalService();
        $this->deleteModal = new DeleteModalService();
        $this->tableService = new TableService();
    }

    public function handle (): bool
    {
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
        $this->stub = $this->formModal->get($this->stub);
        $this->stub = $this->deleteModal->get($this->stub);
        $this->stub = $this->tableService->getHeadings($this->config['view']['table']['columns'], $this->stub);
        $this->stub = $this->tableService->getCells($this->config['view']['table']['columns'], $this->stub);
        return $this->stub;
    }

    private function getTitle (): string
    {
        return str_replace(':title:',
            $this->config['view']['title'] ?: '',
            $this->stub);
    }

}
