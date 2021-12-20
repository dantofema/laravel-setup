<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Views\FormCrudService;
use Dantofema\LaravelSetup\Services\Views\TableService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateViewCommand extends Command
{

    public $signature = 'generate:view {path : path to the config file } {--force}';
    public $description = 'View file generator';
    private FormCrudService $formModal;

    private TableService $tableService;

    public function __construct ()
    {
        parent::__construct();
        $this->formModal = new FormCrudService();
        $this->tableService = new TableService();
    }

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $config = include $this->argument('path');
        $this->info(gen()->config()->view($config));

        if ($this->option('force'))
        {
            gen()->delete()->view($config);
        }

        $path = gen()->path()->view($config);
        $stub = gen()->stub()->view($config);

        File::put($path, $this->replace($config, $stub));

        if ( ! gen()->config()->isAllInOne($config))
        {
            $path = gen()->path()->isModel()->view($config);
            $stub = gen()->stub()->isModel()->view($config);

            File::put($path, $this->replace($config, $stub));
        }

        $this->warn('end');
        return true;
    }

    /**
     * @throws Exception
     */
    private function replace (array $config, string $stub): string
    {
        $stub = $this->getTitle($config, $stub);
        $stub = $this->formModal->get($config, $stub);

        $stub = $this->tableService->getHeadings(
            gen()->field()->getIndex($config),
            $stub
        );

        $stub = $this->tableService->getCells(
            gen()->field()->getIndex($config),
            $stub
        );
        return gen()->config()->replace($config, 'view', $stub);
    }

    private function getTitle (array $config, string $stub): string
    {
        return str_replace(
            ':title:',
            $config['view']['title'] ?: '',
            $stub
        );
    }

}
