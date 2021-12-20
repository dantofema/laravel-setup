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

        $this->info(gen()->config()->factory($config));

        if ($this->option('force'))
        {
            gen()->delete()->factory($config);
        }

        $path = gen()->path()->factory($config);
        $stub = gen()->stub()->factory();

        File::put($path, $this->replace($config, $stub));

        gen()->seeder()->add($config);

        $this->warn('end');

        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $stub = str_replace(':vars:', $this->getVarsFromColumns($config), $stub);
        $stub = str_replace(':return:', $this->getReturnFromColumns($config), $stub);
        $stub = str_replace(':afterCreating:', $this->getAfterCreating($config), $stub);
        return gen()->config()->replace($config, 'factory', $stub);
    }

    private function getVarsFromColumns (array $config): string
    {
        $vars = '';

        foreach ($config['fields'] as $field)
        {
            if ( ! gen()->field()->isBelongsToMany($field))

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
            if ( ! gen()->field()->isBelongsToMany($field))
            {
                $response .= sprintf(
                    "'%s' => $%s," . PHP_EOL,
                    $field['name'],
                    $field['name'],
                );
            }
        }

        return $response . PHP_EOL . "];" . PHP_EOL;
    }

    private function getAfterCreating (array $config): string
    {
        $afterCreating = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isBelongsToMany($field))
            {
                $afterCreating .= PHP_EOL . '$' . $field['relationship']['name']
                    . ' = ' . $field['relationship']['model']
                    . '::inRandomOrder()->take(3)->get()->count() >= 3' . PHP_EOL . '? '
                    . $field['relationship']['model']
                    . '::inRandomOrder()->take(3)->get()' . PHP_EOL . ': '
                    . $field['relationship']['model']
                    . '::factory()->count(3)->create();' . PHP_EOL;

                $afterCreating .= "\$model->"
                    . $field['relationship']['name'] . "()->attach($"
                    . $field['relationship']['name'] . ");" . PHP_EOL;
            }
        }

        return str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $afterCreating);
    }

}
