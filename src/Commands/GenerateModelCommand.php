<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\Models\RelationshipService;
use Dantofema\LaravelSetup\Services\Models\SearchService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelCommand extends Command
{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Model.php.stub';
    public $signature = 'generate:model {path : path to the config file } {--force}';
    public $description = 'Model file generator';
    protected array $config;
    protected string $use = '';
    protected string $useNamespace = '';
    private RelationshipService $modelRelationship;
    private SearchService $searchService;

    public function __construct ()
    {
        parent::__construct();
        $this->modelRelationship = new RelationshipService();
        $this->searchService = new SearchService($this);
    }

    public function handle (): bool
    {
        $this->init('model');
        return $this->create();
    }

    public function create (): bool
    {
        $this->getUserstamps();
        $this->getSoftDelete();

        File::put(Text::config($this->config)->path('model'), $this->replace());
        return true;
    }

    protected function getUserstamps ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Wildside\Userstamps\Userstamps;\r\n";
            $this->use .= "use Userstamps;\r\n";
        }
    }

    protected function getSoftDelete ()
    {
        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $this->useNamespace .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n";
            $this->use .= "use SoftDeletes;\r\n";
        }
    }

    private function replace (): string
    {
        $this->stub = str_replace(':namespace:', $this->config['model']['namespace'], $this->stub);
        $this->stub = str_replace(':useNamespace:', $this->useNamespace, $this->stub);
        $this->stub = str_replace(':use:', $this->use, $this->stub);
        $this->stub = $this->searchService->get($this->config, $this->stub);
        $this->stub = $this->modelRelationship->get($this->config['model']['relationships'], $this->stub);
        $this->stub = $this->getPath();
        return str_replace(':modelName:', Text::config($this->config)->name('model'), $this->stub);
    }

    private function getPath (): string
    {
        return str_replace(':path:', $this->config['route']['path'], $this->stub);
    }

    /**
     * @return array
     */
    public function getConfig (): array
    {
        return $this->config;
    }

}
