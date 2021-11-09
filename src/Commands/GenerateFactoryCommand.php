<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Path;
use Dantofema\LaravelSetup\Facades\Seeder;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFactoryCommand extends Command
{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/ModelFactory.php.stub';

    public $signature = 'generate:factory {path : path to the config file } {--force}';
    public $description = 'Factory file generator';

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
        $use = $this->getUse($vars);
        $content = $this->replace($use, $vars, $definition);

        File::put(Text::config($this->config)->path('factory'), $content);
        Seeder::add($this->config);
    }

    public function getVarsFromColumns (): string
    {
        $vars = '';
        foreach ($this->config['table']['columns'] as $column)
        {
            $row = sprintf("$%s = %s\r\n",
                $column[1],
                $this->getFaker($column)
            );
            $vars .= $row;
        }
        return $vars;
    }

    private function getFaker (array $column): string
    {
        $columns = $this->config['table']['columns'];

        $preFaker = '$this->faker->';
        $preFaker .= in_array('unique', $column) ? 'unique()->' : null;

        $faker = match ($column[1])
        {
            'name' => $preFaker . 'name()',
            'last_name' => $preFaker . 'lastName()',
            'slug' => match (true)
            {
                $this->inArray('title', $columns) => 'Str::slug($title)',
                $this->inArray('description', $columns) => 'Str::slug($description)',
                $this->inArray('last_name', $columns) => 'Str::slug($last_name)',
                default => 'Str::slug($name)',
            },
            'email' => $preFaker . 'safeEmail()',
            'email_verified_at' => $preFaker . 'now()',
            'password' => $preFaker . 'bcrypt("password")',
            'remember_token' => $preFaker . 'Str::random(10)',
            'link' => $preFaker . 'url',
            'image' => $preFaker . 'word()." . jpg"',
            'phone' => $preFaker . 'isbn10',
            'title' => $preFaker . 'sentence($maxNbChars = 10)',
            'subtitle' => $preFaker . 'sentence($maxNbChars = 20)',
            'body' => $preFaker . 'sentence($nbWords = 350, $variableNbWords = true)',
            'lead' => $preFaker . 'sentence($nbWords = 60, $variableNbWords = true)',
            'publication_time' => $preFaker . 'dateTimeBetween(" - 90 days", " + 7 days", null)->format("d - m - Y H:i:s")',
            'epigraph' => $preFaker . 'sentence()',
            'facebook' => $preFaker . 'url()',
            'birthday' => $preFaker . 'date()',
            'date_from' => $preFaker . 'dateTimeBetween("now", "now", null)->format("d - m - Y H:i:s")',
            'date_to' => $preFaker . 'dateTimeInInterval("now", " + 5 days", null)->format("d - m - Y H:i:s")',
            default => 'word()'
        };

        return $faker . ";\r\n";
    }

//    private function inArray (string $needle, array $columns): bool
//    {
//        return in_array($needle, call_user_func_array('array_merge', $columns));
//    }

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

        return $return . "\r\n];";
    }

    private function getForeignKeys (): string
    {
        $rows = '';
        foreach ($this->config['table']['foreignKeys'] as $foreignKey)
        {
            $model = $this->getModelNameFromForeignKey($foreignKey[0]);
            $rows .= "'$foreignKey[0]' => " . $model . "::inRandomOrder()->first() ?? " . $model . "::factory()->create(),\r\n";
        }
        return $rows;
    }

    private function getModelNameFromForeignKey (string $key): string
    {
        return str_replace(' ', '', (ucwords(str_replace('_', ' ', substr_replace($key, "", -3)))));
    }

    public function getUse (string $vars): string
    {
        $use = 'use ' . Text::config($this->config)->namespace('model') . ";\r\n";
        $use .= str_contains($vars, 'Str::') ? "use Illuminate\Support\Str;\r\n" : null;
        $use .= str_contains($vars, 'Carbon::') ? "use Carbon\Carbon;\r\n" : null;
        foreach ($this->config['table']['foreignKeys'] as $key)
        {
            $use .= "use App\Models\\" . $this->getModelNameFromForeignKey($key[0]) . ";\r\n";
        }
        return $use;
    }

    private function replace (string $use, string $vars, string $return): string
    {
        $this->stub = str_replace(':use:', $use, $this->stub);
        $this->stub = str_replace(':vars:', $vars, $this->stub);
        $this->stub = str_replace(':return:', $return, $this->stub);
        $this->stub = str_replace(':classFactory:', Text::config($this->config)->name('model') . 'Factory', $this->stub);
        return str_replace(':modelName:', Text::config($this->config)->name('model'), $this->stub);
    }

}
