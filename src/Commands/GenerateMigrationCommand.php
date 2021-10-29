<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Path;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{
    use CommandTrait;

    const STUB_PATH = '/../Stubs/create_setup_table.php.stub';
    protected const DIRECTORY = 'database/migrations/';
    public $signature = 'generate:migration {path : path to the config file } {--force}';
    public $description = 'Migration file generator';

    public function handle (): bool
    {
        $this->init('migration');

        $this->create();

        return true;
    }

    public function create (): void
    {
        $rows = $this->getRows();
        $foreignKeys = $this->getForeignKeys();
        $content = $this->replace($rows, $foreignKeys);
        File::put(Text::config($this->config)->path('migration'), $content);
    }

    public function getRows (): string
    {
        $rows = '';
        foreach ($this->config['table']['columns'] as $column)
        {
            $row = sprintf("\$table->%s('%s')%s%s;\r\n",
                $column[0],
                $column[1],
                in_array('nullable', $column) ? '->nullable()' : null,
                in_array('unique', $column) ? '->unique()' : null
            );
            $rows .= $row;
        }

        if (in_array('SoftDeletes', $this->config['model']['use']))
        {
            $rows .= "\$table->softDeletes();\r\n";
        }

        if (in_array('Userstamps', $this->config['model']['use']))
        {
            $rows .= "\$table->unsignedBigInteger('created_by')->nullable();\r\n";
            $rows .= "\$table->unsignedBigInteger('updated_by')->nullable();\r\n";
            if (in_array('SoftDeletes', $this->config['model']['use']))
            {
                $rows .= "\$table->unsignedBigInteger('deleted_by')->nullable();\r\n";
            }
        }
        return $rows;
    }

    public function getForeignKeys (): string
    {
        $rows = '';
        foreach ($this->config['table']['foreignKeys'] as $foreignKey)
        {
            $row = sprintf("\$table->foreignId('%s')%s->constrained('%s')%s;\r\n",
                $foreignKey[0],
                in_array('nullable', $foreignKey) ? '->nullable()' : null,
                $foreignKey[1],
                in_array('unique', $foreignKey) ? '->unique()' : null
            );
            $rows .= $row;
        }
        return $rows;
    }

    private function replace (string $rows, string $foreignKeys): string|array
    {
        $this->stub = str_replace(':fields:', $rows . $foreignKeys, $this->stub);
        $this->stub = str_replace(':tableName:', $this->config['table']['name'], $this->stub);
        return str_replace(':className:', Str::of($this->config['table']['name'])->camel()->ucfirst(), $this->stub);
    }

}
