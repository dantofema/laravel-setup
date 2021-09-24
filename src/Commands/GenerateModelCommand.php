<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModelCommand extends Command
{
    use Config;

    protected const STUB_PATH = '/../Stubs/Model.php.stub';
    protected const DIRECTORY = 'database/factories/';
    public $signature = 'generate:model {path : path to the config file }';
    public $description = 'Model file generator';
    protected array $config;

    public function handle(): bool
    {
        if ($this->configFileExists()) {
            return false;
        };
        $this->config = $this->getConfig();

        if (File::exists(self::DIRECTORY . $this->getFileName())) {
            $this->error('The migration file "' . $this->getFileName() . '" already exists ');
            $this->error('Exit');

            return false;
        }

        $this->create();

        return true;
    }

    private function getFileName(): string
    {
        return $this->getModelName() . 'Factory.php';
    }

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
}
