<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Generate;
use Dantofema\LaravelSetup\Services\FakerService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;

class GenerateFactoryCommand extends Command
{
    use CommandTrait;

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

        $this->stub = str_replace(':vars:', $this->getVarsFromColumns(), $this->stub);
        $this->stub = str_replace(':return:', $this->getReturnFromColumns(), $this->stub);

        $this->put($this->stub);

        Generate::addSeeder($this->config);

        return true;
    }

    private function getVarsFromColumns (): string
    {
        $vars = '';

        foreach ($this->config['fields'] as $field)
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

    private function getReturnFromColumns (): string
    {
        $response = PHP_EOL . "return [" . PHP_EOL;
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany')
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
