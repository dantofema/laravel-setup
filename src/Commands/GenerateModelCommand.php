<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\Models\RelationshipsService;
use Dantofema\LaravelSetup\Services\Models\SearchService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;

class GenerateModelCommand extends Command
{
    use CommandTrait;

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

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        $this->init(['model']);

        foreach ($this->properties as $property)
        {
            $this->put($property['type'], $this->replace($property));
        }

        return true;
    }

    private function replace (array $property): string
    {
        $property['stub'] = $this->getNamespace($property['stub']);

        $property['stub'] = $this->searchService->get($this->config, $property['stub']);
        $property['stub'] = $this->modelRelationship->get(
            gen()->field()->getRelationships($this->config),
            $property['stub']
        );
        $property['stub'] = $this->getUses($property['stub']);
        $property['stub'] = $this->getPath($property['stub']);
        $property['stub'] = $this->getSrcAttribute($property['stub']);

        return $property['stub'];
    }

    private function getNamespace (string $stub): string
    {
        return str_replace(
            ':namespace:',
            gen()->getNamespace($this->config, 'model'),
            $stub
        );
    }

    private function getUses (string $stub)
    {
        $useNamespace = '';
        $useInClass = '';
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $useNamespace .= "use Wildside\Userstamps\Userstamps;" . PHP_EOL;
            $useInClass .= "use Userstamps;" . PHP_EOL;
        }
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $useNamespace .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            $useInClass .= "use SoftDeletes;\r\n";
        }
        $stub = str_replace(':useNamespace:', $useNamespace, $stub);

        return str_replace(':useInClass:', $useInClass, $stub);
    }

    private function getPath (string $stub): string
    {
        return str_replace(':path:', $this->config['route']['path'], $stub);
    }

    protected function getSrcAttribute (string $stub): string
    {
        return str_replace(
            ':getSrcAttribute:',
            empty(gen()->field()->getFile($this->config))
                ? ''
                : file_get_contents(__DIR__ . '/../Stubs/model/getSrcAttribute.stub'),
            $stub
        );
    }

    private function getFileName (string $stub): string
    {
        return str_replace(
            ':namespace:',
            gen()->getNamespace($this->config, 'model'),
            $stub
        );
    }
}
