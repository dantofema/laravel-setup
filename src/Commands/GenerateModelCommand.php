<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Model.php.stub';
    protected const DIRECTORY = 'app/Models/';
    public $signature = 'generate:model {path : path to the config file }';
    public $description = 'Model file generator';
    protected array $config;

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
        $vars = $this->getVarsFromColumns();
        $definition = $this->getReturnFromColumns();

        $use = $this->getUse($vars);

        File::put(self::DIRECTORY . $this->getFileName(), $this->replace($stub, $use, $vars, $definition));
        return true;
    }

    private function getFileName (): string
    {
        return $this->getModelName() . 'Factory.php';
    }

//    private function replace (): string
//    {
////        $stub = str_replace(':classFactory:', $this->getModelName() . 'Factory', $stub);
////        return str_replace(':modelName:', $this->getModelName(), $stub);
//    }

}
