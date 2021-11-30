<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\Models\RelationshipsService;
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
    private RelationshipsService $modelRelationship;
    private SearchService $searchService;

    public function __construct ()
    {
        parent::__construct();
        $this->modelRelationship = new RelationshipsService();
        $this->searchService = new SearchService();
    }

    public function handle(): bool
    {
<<<<<<< HEAD
        $this->init('model');
        $this->getUserstamps();
        $this->getSoftDelete();
        $this->getNamespace();
        $this->stub = str_replace(':useNamespace:', $this->useNamespace, $this->stub);
        $this->stub = str_replace(':useInClass:', $this->useInClass, $this->stub);
        $this->stub = $this->searchService->get($this->config, $this->stub);
        $this->stub = $this->modelRelationship->get(Field::config($this->config)->getRelationships(), $this->stub);
        $this->getPath();
        $this->getSrcAttribute();
        $this->put($this->stub);
        return true;
    }

    protected function getUserstamps ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Wildside\Userstamps\Userstamps;" . PHP_EOL;
            $this->useInClass .= "use Userstamps;" . PHP_EOL;
=======
        if ($this->configFileExists()) {
            return false;
        };
        $this->config = $this->getConfig();

        if (File::exists(self::DIRECTORY . $this->getFileName())) {
            $this->error('The migration file "' . $this->getFileName() . '" already exists ');
            $this->error('Exit');

            return false;
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
        }
    }

<<<<<<< HEAD
    protected function getSoftDelete ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            $this->useInClass .= "use SoftDeletes;\r\n";
        }
    }

    private function getNamespace (): void
=======
        $this->create();

        return true;
    }

    private function getFileName(): string
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    {
        $this->stub = str_replace(
            ':namespace:',
            Text::config($this->config)->namespaceFolder('model'),
            $this->stub
        );
    }

<<<<<<< HEAD
    private function getPath (): void
    {
        $this->stub = str_replace(':path:', $this->config['route']['path'], $this->stub);
    }

    protected function getSrcAttribute ()
    {
        $this->stub = str_replace(
            ':getSrcAttribute:',
            empty(Field::config($this->config)->getFile())
                ? ''
                : file_get_contents(__DIR__ . '/../Stubs/model/getSrcAttribute.stub'),
            $this->stub
        );
    }

=======
    public function create()
    {
        $vars = $this->getVarsFromColumns();
        $definition = $this->getReturnFromColumns();
        $stub = $this->getStub();
        if (! $stub) {
            $this->error('Error get stub');
            $this->error('Exit');

            return false;
        }
        $use = $this->getUse($vars);
        $content = $this->replace($stub, $use, $vars, $definition);
        $filename = $this->getFileName();
        File::put(self::DIRECTORY . $filename, $content);
    }

//    private function replace (): string
//    {
////        $stub = str_replace(':classFactory:', $this->getModelName() . 'Factory', $stub);
////        return str_replace(':modelName:', $this->getModelName(), $stub);
//    }
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
}
