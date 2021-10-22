<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Model.php.stub';
    protected const DIRECTORY = 'app/Models/';
    public $signature = 'generate:model {path : path to the config file } {--force}';
    public $description = 'Model file generator';
    protected array $config;
    protected string $use = '';
    protected string $useNamespace = '';

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
        $this->getUserstamps();
        $this->getSoftDelete();
        File::put(self::DIRECTORY . $this->getFileName(), $this->replace());
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

    private function getFileName (): string
    {
        return $this->getModelName() . '.php';
    }

    private function replace (): string
    {
        $this->stub = str_replace(':namespace:', $this->config['model']['namespace'], $this->stub);
        $this->stub = str_replace(':useNamespace:', $this->useNamespace, $this->stub);
        $this->stub = str_replace(':use:', $this->use, $this->stub);
        $this->stub = str_replace(':search:', $this->getSearch(), $this->stub);
        $this->stub = str_replace(':relationships:', $this->getRelationships(), $this->stub);
        $this->stub = $this->getPath();
        return str_replace(':modelName:', $this->getModelName(), $this->stub);
    }

    protected function getSearch (): string
    {
        $items = $this->config['model']['search'];

        $searchStub = file_get_contents(__DIR__ . '/../Stubs/scopeSearch.stub');

        $query = '';
        foreach ($items as $key => $item)
        {
            $item = explode('.', $item);

            if ($key === array_key_first($items))
            {
                $query .= count($item) == 1
                    ? "\$query->where('$item[0]', 'like', '%' . \$search . '%')\r\n"
                    : "\$query->whereHas('$item[0]', fn(\$q) => \$q->where('$item[1]', 'like', '%' . \$search . '%'))\r\n";
                continue;
            }
            $query .= count($item) == 1
                ? "->orWhere('$item[0]', 'like', '%' . \$search . '%')\r\n"
                : "->orWhereHas('$item[0]', fn(\$q) => \$q->where('$item[1]', 'like', '%' . \$search . '%'))\r\n";
        }
        return str_replace(':query:', $query, $searchStub);
    }

    protected function getRelationships (): string
    {
        $response = '';

        foreach ($this->config['model']['relationships'] as $relationType => $relations)
        {
            foreach ($relations as $relation)
            {
                $relationStub = file_get_contents(__DIR__ . '/../Stubs/relationshipMethod.stub');
                $relationStub = str_replace(':type:', ucfirst($relationType), $relationStub);
                $relationStub = str_replace(':relation:', $relationType, $relationStub);
                $relationStub = str_replace(':method:', $relation[0], $relationStub);
                $response .= str_replace(':related:', $relation[1], $relationStub);
            }
        }
        return $response;
    }

    private function getPath (): string
    {
        return str_replace(':path:', $this->config['model']['path'], $this->stub);
    }

}
