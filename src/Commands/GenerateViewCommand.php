<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Views\DeleteModalService;
use Dantofema\LaravelSetup\Services\Views\FormModalService;
use Dantofema\LaravelSetup\Services\Views\TableService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;

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

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        $types = $this->config['allInOne']
            ? ['view']
            : ['viewCollection', 'viewModel'];

        $this->init($types);

        foreach ($this->properties as $property)
        {
            $this->put($this->replace($property));
        }

        return true;
    }

    private function replace (array $property): string
    {
        $property['stub'] = $this->getTitle($property['stub']);
        $property['stub'] = $this->formModal->get($this->config, $property['stub']);
        $property['stub'] = $this->deleteModal->get($property['stub']);

        $property['stub'] = $this->tableService->getHeadings(
            gen()->field()->getIndex($this->config),
            $property['stub']
        );

        $property['stub'] = $this->tableService->getCells(
            gen()->field()->getIndex($this->config),
            $property['stub']
        );

        return $property['stub'];
    }

    private function getTitle (string $stub): string
    {
        return str_replace(
            ':title:',
            $this->config['view']['title'] ?: '',
            $stub
        );
    }

}
