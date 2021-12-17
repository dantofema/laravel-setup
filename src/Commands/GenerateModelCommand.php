<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Models\RelationshipsService;
use Dantofema\LaravelSetup\Services\Models\SearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelCommand extends Command
{

    public $signature = 'generate:model {path : path to the config file } {--force}';
    public $description = 'Model file generator';
    protected array $config;
    protected string $useInClass = '';
    protected string $useNamespace = '';
    private RelationshipsService $modelRelationship;
    private SearchService $searchService;

    public function __construct ()
    {
        parent::__construct();
        $this->modelRelationship = new RelationshipsService();
        $this->searchService = new SearchService();
    }

    public function handle (): bool
    {
        $config = include $this->argument('path');

        if ($this->option('force'))
        {
            gen()->delete()->model($config);
        }

        $path = gen()->path()->model($config);
        $stub = gen()->stub()->model();
        File::put($path, $this->replace($config, $stub));

        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $stub = $this->getNamespace($config, $stub);

        $stub = $this->searchService->get($config, $stub);
        $stub = $this->modelRelationship->get(
            gen()->field()->getRelationships($config),
            $stub
        );
        $stub = $this->getUses($config, $stub);
        $stub = $this->getPath($config, $stub);
        $stub = $this->getSrcAttribute($config, $stub);
        return gen()->config()->replace($config, 'model', $stub);
    }

    private function getNamespace (array $config, string $stub): string
    {
        return str_replace(
            ':namespace:',
            gen()->namespace()->model($config),
            $stub
        );
    }

    private function getUses (array $config, string $stub): string
    {
        $useNamespace = '';
        $useInClass = '';
        if (in_array('SoftDeletes', $config['model']['use']))
        {
            $useNamespace .= "use Wildside\Userstamps\Userstamps;" . PHP_EOL;
            $useInClass .= "use Userstamps;" . PHP_EOL;
        }
        if (in_array('SoftDeletes', $config['model']['use']))
        {
            $useNamespace .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            $useInClass .= "use SoftDeletes;\r\n";
        }
        $stub = str_replace(':useNamespace:', $useNamespace, $stub);

        return str_replace(':useInClass:', $useInClass, $stub);
    }

    private function getPath (array $config, string $stub): string
    {
        return str_replace(':path:', $config['route']['path'], $stub);
    }

    protected function getSrcAttribute (array $config, string $stub): string
    {
        return str_replace(
            ':getSrcAttribute:',
            empty(gen()->field()->getFile($config))
                ? ''
                : file_get_contents(__DIR__ . '/../Stubs/model/getSrcAttribute.stub'),
            $stub
        );
    }

}
