<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFactoryCommand extends Command
{

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
        $config = include $this->argument('path');

        if ($this->option('force'))
        {
            gen()->delete()->factory($config);
        }

        $path = gen()->path()->factory($config);
        $stub = gen()->stub()->factory();
        File::put($path, $this->replace($config, $stub));

        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $stub = str_replace(':vars:', $this->getVarsFromColumns($config), $stub);
        $stub = str_replace(':return:', $this->getReturnFromColumns($config), $stub);
        return gen()->config()->replace($config, 'factory', $stub);
    }

    private function getVarsFromColumns (array $config): string
    {
        $vars = '';

        foreach ($config['fields'] as $field)
        {
            if (array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany')
            {
                continue;
            }
            $vars .= sprintf(
                "$%s = %s;" . PHP_EOL,
                $field['name'],
                $this->faker->get($field)
            );
        }

        return $vars;
    }

    private function getReturnFromColumns (array $config): string
    {
        $response = PHP_EOL . "return [" . PHP_EOL;
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }
            $response .= sprintf(
                "'%s' => $%s," . PHP_EOL,
                $field['name'],
                $field['name'],
            );
        }

        return $response . PHP_EOL . "];" . PHP_EOL;
    }
}
