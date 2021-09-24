<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFactoryCommand extends Command
{
    use Config;

    protected const STUB_PATH = '/../Stubs/ModelFactory.php.stub';
    protected const DIRECTORY = 'database/factories/';
    public $signature = 'generate:factory {path : path to the config file }';
    public $description = 'Factory file generator';

    public function handle (): bool
    {
        if ( ! $this->init())
        {
            return false;
        };

        $this->create();
        return true;
    }

    public function create ()
    {
        $vars = $this->getVarsFromColumns();
        $definition = $this->getReturnFromColumns();
        $stub = $this->getStub();
        if ( ! $stub)
        {
            $this->error('Error get stub');
            $this->error('Exit');
            return false;
        }
        $use = $this->getUse($vars);
        $content = $this->replace($stub, $use, $vars, $definition);
        $filename = $this->getFileName();
        File::put(self::DIRECTORY . $filename, $content);
    }

    public function getVarsFromColumns (): string
    {
        $vars = '';
        foreach ($this->config['table']['columns'] as $column)
        {
            $row = sprintf("$%s = %s % s,\r\n",
                $column[1],
                $this->getFaker($column),
                in_array('unique', $this->config['table']['columns']) ? '->unique()' : null
            );
            $vars .= $row;
        }
        return $vars;
    }

    private function getFaker (array $column): string
    {
        $columns = $this->config['table']['columns'];

        $faker = match ($column[1])
        {
            'name' => '$this->faker->name()',
            'last_name' => '$this->faker->lastName()',
            'slug' => match (true)
            {
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

    private function inArray (string $needle, array $columns): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $columns));
    }

    private function getReturnFromColumns (): string
    {
        $return = "\r\nreturn [\r\n";
        foreach ($this->config['table']['columns'] as $column)
        {
            $row = sprintf("'%s' => $%s,\r\n",
                $column[1],
                $column[1],
            );
            $return .= $row;
        }
        $return .= $this->getForeignKeys();

        return $return . "\r\n]";
    }

    private function getForeignKeys (): string
    {
        $rows = '';
        foreach ($this->config['table']['foreignKeys'] as $foreignKey)
        {
            $model = $this->getModelNameFromForeignKey($foreignKey[0]);
            $rows .= "'$foreignKey[0]' => " . $model . "::inRandomOrder()->first() ?? " . $model . "::factory()->create();\r\n";
        }
        return $rows;
    }

    private function getModelNameFromForeignKey (string $key): string
    {
        return str_replace(' ', '', (ucwords(str_replace('_', ' ', substr_replace($key, "", -3)))));
    }

    public function getUse (string $vars): string
    {
        $use = 'use ' . $this->config['model'];
        $use .= str_contains($vars, 'Str::') ? "Illuminate\Support\Str;\r\n" : null;
        $use .= str_contains($vars, 'Carbon::') ? "use Carbon\Carbon;\r\n" : null;
        foreach ($this->config['table']['foreignKeys'] as $key)
        {
            $use .= "use App\Models\\" . $this->getModelNameFromForeignKey($key[0]) . ";\r\n";
        }
        return $use;
    }

    private function replace (string $stub, string $use, string $vars, string $return): string
    {
        $stub = str_replace(':use:', $use, $stub);
        $stub = str_replace(':vars:', $vars, $stub);
        $stub = str_replace(':return:', $return, $stub);
        $stub = str_replace(':classFactory:', $this->getModelName() . 'Factory', $stub);
        return str_replace(':modelName:', $this->getModelName(), $stub);
    }

    private function getFileName (): string
    {
        return $this->getModelName() . 'Factory.php';
    }

}
