<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Str;

class GenerateMigrationCommand extends Command
{
    use Config;

    public const STUB_PATH = '/../Stubs/create_setup_table.php.stub';
    protected const DIRECTORY = 'database/migrations/';
    public $signature = 'generate:migration {path : path to the config file }';
    public $description = 'Migration file generator';
    protected array $config;

    public function handle(): bool
    {
        if (! $this->configFileExists()) {
            return false;
        };

        $this->config = $this->getConfig();

        if ($this->migrationFileExists()) {
            return false;
        }

        $this->create();

        return true;
    }

    public function migrationFileExists(): bool
    {
        return collect(File::files('database/migrations/'))
            ->contains(function ($file) {
                $name = $this->config['table']['name'];
                if (Str::contains($file, '_create_' . $name . '_table.php')) {
                    return true;
                }

                return false;
            });
    }

    public function create(): void
    {
        $rows = $this->getRows();
        $foreignKeys = $this->getForeignKeys();
        $stub = $this->getStub();
        $content = $this->replace($rows, $foreignKeys, $stub, );
        $filename = $this->getFileName();
        File::put('database/migrations/' . $filename, $content);
    }

    public function getRows(): string
    {
        $rows = '';
        foreach ($this->config['table']['columns'] as $column) {
            $row = sprintf(
                "\$table->%s('%s')%s%s;\r\n",
                $column[0],
                $column[1],
                in_array('nullable', $column) ? '->nullable()' : null,
                in_array('unique', $column) ? '->unique()' : null
            );
            $rows .= $row;
        }

        return $rows;
    }

    public function getForeignKeys(): string
    {
        $rows = '';
        foreach ($this->config['table']['foreignKeys'] as $foreignKey) {
            $row = sprintf(
                "\$table->foreignId('%s')%s->constrained('%s')%s;\r\n",
                $foreignKey[0],
                in_array('nullable', $foreignKey) ? '->nullable()' : null,
                $foreignKey[1],
                in_array('unique', $foreignKey) ? '->unique()' : null
            );
            $rows .= $row;
        }

        return $rows;
    }

    private function replace(string $rows, string $foreignKeys, bool|string $stub): string|array
    {
        $stub = str_replace(':fields:', $rows . $foreignKeys, $stub);
        $stub = str_replace(':tableName:', $this->config['table']['name'], $stub);

        return str_replace(':className:', Str::of($this->config['table']['name'])->camel()->ucfirst(), $stub);
    }

    private function getFileName(): string
    {
        return now()->format('Y_m_d_His') . '_create_'
            . $this->config['table']['name'] . '_table.php';
    }
}
