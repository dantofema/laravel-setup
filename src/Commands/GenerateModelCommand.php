<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\Models\RelationshipService;
use Dantofema\LaravelSetup\Services\Models\SearchService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;

class GenerateModelCommand extends Command
{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Model.php.stub';
    public $signature = 'generate:model {path : path to the config file } {--force}';
    public $description = 'Model file generator';
    protected array $config;
    protected string $useInClass = '';
    protected string $useNamespace = '';
    private RelationshipService $modelRelationship;
    private SearchService $searchService;

    public function __construct ()
    {
        parent::__construct();
        $this->modelRelationship = new RelationshipService();
        $this->searchService = new SearchService();
    }

    public function handle (): bool
    {
        $this->init('model');
        $this->getUserstamps();
        $this->getSoftDelete();

        $this->put($this->replace());
        return true;
    }

    protected function getUserstamps ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Wildside\Userstamps\Userstamps;" . PHP_EOL;
            $this->useInClass .= "use Userstamps;" . PHP_EOL;
        }
    }

    protected function getSoftDelete ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            $this->useInClass .= "use SoftDeletes;\r\n";
        }
    }

    private function replace (): string
    {
        $this->stub = $this->getNamespace();
        $this->stub = str_replace(':useNamespace:', $this->useNamespace, $this->stub);
        $this->stub = str_replace(':useInClass:', $this->useInClass, $this->stub);
        $this->stub = $this->searchService->get($this->config, $this->stub);
        $this->stub = $this->modelRelationship->get(Field::config($this->config)->getRelationships(), $this->stub);
        $this->stub = $this->getPath();
        return str_replace(':modelName:', Text::config($this->config)->name('model'), $this->stub);
    }

    private function getNamespace (): string
    {
        return str_replace(
            ':namespace:',
            Text::config($this->config)->namespaceFolder('model'),
            $this->stub
        );
    }

    private function getPath (): string
    {
        return str_replace(':path:', $this->config['route']['path'], $this->stub);
    }

}
