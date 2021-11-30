<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\FakerService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;

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
        <<<<
        <<< HEAD
        $this->init('factory');
=======
        if (! $this->configFileExists()) {
            return false;
        };
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392

        $this->stub = str_replace(':vars:', $this->getVarsFromColumns(), $this->stub);
        $this->stub = str_replace(':return:', $this->getReturnFromColumns(), $this->stub);

<<<<<<< HEAD
        $this->put($this->stub);

        Generate::addSeeder($this->config);

        return true;
=======
        if ($this->fileExists()) {
            return false;
        };

        $this->create();

        return true;
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
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    }

    public function getVarsFromColumns(): string
    {
        $vars = '';
<<<<<<< HEAD
        foreach ($this->config['fields'] as $field)
        {
            $vars .= sprintf(
                "$%s = %s;" . PHP_EOL,
                $field['name'],
                $this->faker->get($field)
=======
        foreach ($this->config['table']['columns'] as $column) {
            $row = sprintf(
                "$%s = %s % s,\r\n",
                $column[1],
                $this->getFaker($column),
                in_array('unique', $this->config['table']['columns']) ? '->unique()' : null
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
            );
        }
<<<<<<< HEAD

        return $vars;
=======

        return $vars;
    }

    private function getFaker(array $column): string
    {
        $columns = $this->config['table']['columns'];

        $faker = match ($column[1]) {
            'name' => '$this->faker->name()',
            'last_name' => '$this->faker->lastName()',
            'slug' => match (true) {
                $this->inArray('title', $columns) => 'Str::slug($title)',
                $this->inArray('description', $columns) => 'Str::slug($description)',
                $this->inArray('last_name', $columns) => 'Str::slug($last_name)',
                default => 'Str::slug($name)',
            },
            'email' => '$this->faker->safeEmail()',
            'email_verified_at' => 'now()',
            'password' => 'bcrypt("password")',
            'remember_token' => 'Str::random(10)',
            'link' => '$this->faker->url',
            'image' => '$this->faker->word()." . jpg"',
            'phone' => '$this->faker->isbn10',
            'title' => '$this->faker->sentence($maxNbChars = 10)',
            'subtitle' => '$this->faker->sentence($maxNbChars = 20)',
            'body' => '$this->faker->sentence($nbWords = 350, $variableNbWords = true)',
            'lead' => '$this->faker->sentence($nbWords = 60, $variableNbWords = true)',
            'publication_time' => '$this->faker->dateTimeBetween(" - 90 days", " + 7 days", null)->format("d - m - Y H:i:s")',
            'epigraph' => '$this->faker->sentence()',
            'facebook' => '$this->faker->url',
            'birthday' => '$this->faker->date()',
            'date_from' => '$this->faker->dateTimeBetween("now", "now", null)->format("d - m - Y H:i:s")',
            'date_to' => '$this->faker->dateTimeInInterval("now", " + 5 days", null)->format("d - m - Y H:i:s")',
            default => '$this->faker->word()'
        };

        $faker .= in_array('unique', $column) ? '->unique()' : null;

        return $faker . ";\r\n";
    }

    private function inArray(string $needle, array $columns): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $columns));
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    }

    private function getReturnFromColumns(): string
    {
<<<<<<< HEAD
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
=======
        $return = "\r\nreturn [\r\n";
        foreach ($this->config['table']['columns'] as $column) {
            $row = sprintf(
                "'%s' => $%s,\r\n",
                $column[1],
                $column[1],
            );
            $return .= $row;
        }
        $return .= $this->getForeignKeys();

        return $return . "\r\n]";
    }

    private function getForeignKeys(): string
    {
        $rows = '';
        foreach ($this->config['table']['foreignKeys'] as $foreignKey) {
            $model = $this->getModelNameFromForeignKey($foreignKey[0]);
            $rows .= "'$foreignKey[0]' => " . $model . "::inRandomOrder()->first() ?? " . $model . "::factory()->create();\r\n";
        }

        return $rows;
    }

    private function getModelNameFromForeignKey(string $key): string
    {
        return str_replace(' ', '', (ucwords(str_replace('_', ' ', substr_replace($key, "", -3)))));
    }

    public function getUse(string $vars): string
    {
        $use = 'use ' . $this->config['model'];
        $use .= str_contains($vars, 'Str::') ? "Illuminate\Support\Str;\r\n" : null;
        $use .= str_contains($vars, 'Carbon::') ? "use Carbon\Carbon;\r\n" : null;
        foreach ($this->config['table']['foreignKeys'] as $key) {
            $use .= "use App\Models\\" . $this->getModelNameFromForeignKey($key[0]) . ";\r\n";
        }

        return $use;
    }

    private function replace(string $stub, string $use, string $vars, string $return): string
    {
        $stub = str_replace(':use:', $use, $stub);
        $stub = str_replace(':vars:', $vars, $stub);
        $stub = str_replace(':return:', $return, $stub);
        $stub = str_replace(':classFactory:', $this->getModelName() . 'Factory', $stub);

        return str_replace(':modelName:', $this->getModelName(), $stub);
    }

    private function getFileName(): string
    {
        return $this->getModelName() . 'Factory.php';
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    }
}
