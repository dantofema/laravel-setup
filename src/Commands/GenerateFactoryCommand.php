<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Seeder;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\FakerService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFactoryCommand extends Command
{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/ModelFactory.php.stub';

    public $signature = 'generate:factory {path : path to the config file } {--force}';
    public $description = 'Factory file generator';
    private FakerService $faker;

    public function __construct ()
    {
        parent::__construct();
        $this->faker = new FakerService();
    }

    public function handle (): bool
    {
        $this->init('factory');

        $this->create();
        return true;
    }

    public function create ()
    {
        $vars = $this->getVarsFromColumns();
        $definition = $this->getReturnFromColumns();

        $content = $this->replace($vars, $definition);

        File::put(Text::config($this->config)->path('factory'), $content);
        Seeder::add($this->config);
    }

    public function getVarsFromColumns (): string
    {
        $vars = '';
        foreach ($this->config['fields'] as $field)
        {
            $vars .= sprintf(
                "$%s = %s;" . PHP_EOL,
                $field['name'],
                $this->faker->get($field)
            );
        }
        return $vars;
    }

    private function getReturnFromColumns (): string
    {
        $return = PHP_EOL . "return [" . PHP_EOL;
        foreach ($this->config['fields'] as $field)
        {
            $row = sprintf("'%s' => $%s," . PHP_EOL,
                $field['name'],
                $field['name'],
            );
            $return .= $row;
        }

        return $return . PHP_EOL . "];" . PHP_EOL;
    }

    private function replace (string $vars, string $return): string
    {
        $this->stub = str_replace(':vars:', $vars, $this->stub);
        return str_replace(':return:', $return, $this->stub);
    }

}
