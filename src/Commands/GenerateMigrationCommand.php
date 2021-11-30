<?php

namespace Dantofema\LaravelSetup\Commands;

<<<<<<< HEAD
<<<<
<<< HEAD
use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Traits\CommandTrait;
=======
=======
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
use Dantofema\LaravelSetup\Traits\Config;
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{
    use CommandTrait;

<<<<<<< HEAD
<<<<<<< HEAD
    const STUB_PATH = '/../Stubs/migration.php.stub';
    const STUB_PATH_PIVOT = '/../Stubs/pivot.php.stub';

    public $signature = 'generate:migration {path : path to the config file } {--force}';
=======
=======
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    public const STUB_PATH = '/../Stubs/create_setup_table.php.stub';
    protected const DIRECTORY = 'database/migrations/';
    public $signature = 'generate:migration {path : path to the config file }';
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    public $description = 'Migration file generator';

    public function handle(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $this->init('migration');
=======
=======
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
        if (! $this->configFileExists()) {
            return false;
        };
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392

        $rows = $this->getFields();
        $this->stub = str_replace(':fields:', $rows, $this->stub);
        $this->stub = str_replace(':className:', Str::of($this->config['table']['name'])->camel()->ucfirst(),
            $this->stub);

<<<<<<< HEAD
        $this->put($this->stub);

        return true;
    }

    public function getFields (): string
    {
        $rows = '';
        foreach ($this->config['fields'] as $field)
        {
            $relationship = Field::getRelationship($field);
            $rules = Field::getRules($field);

            if (empty($relationship))
            {
                $row = sprintf("\$table->%s('%s')%s%s;\r\n",
                    $field['type'],
                    $field['name'],
                    ! empty($rules['nullable']) ? '->nullable()' : null,
                    ! empty($rules['unique']) ? '->unique()' : null
                );
            } else
            {
                $row = $this->fieldWithRelationship($field);
            }
            $rows .= $row;
        }

        if (array_key_exists('use', $this->config['model']))
        {
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
        }

        return $rows;
    }

    private function fieldWithRelationship ($field): string
    {
        if ($field['relationship']['type'] === 'belongsToMany')
        {
            $this->createPivotMigrationFile($field['relationship']['pivot']['table']);
        }

<<<<<<< HEAD
        if ($field['relationship']['type'] === 'belongsTo')
        {
            return sprintf("\$table->foreignId('%s')%s->constrained('%s');" . PHP_EOL,
                $field['name'],
                ! empty($rules['nullable']) ? '->nullable()' : null,
                $field['relationship']['table'],
=======
=======
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
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
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
            );
        }
<<<<<<< HEAD
<<<<<<< HEAD
        return '';
    }

    private function createPivotMigrationFile ($table): void
    {
        $pivotStub = file_get_contents(__DIR__ . '/../Stubs/pivot.php.stub');
        $explode = explode('_', $table);

        $pivotStub = str_replace(
            ':table:',
            $table,
            $pivotStub
        );

        $pivotStub = str_replace(
            ':className:',
            ucfirst($explode[0]) . ucfirst($explode[1]),
            $pivotStub);

        $fields = "\$table->unsignedInteger('" . $explode[0] . "_id');" . PHP_EOL;
        $fields .= "\$table->unsignedInteger('" . $explode[1] . "_id');" . PHP_EOL;

        $pivotStub = str_replace(
            ':fields:',
            $fields,
            $pivotStub);

        File::put(
            'database/migrations/' . now()->format('Y_m_d_His') . '_create_' . $table . '_pivot_table.php',
            $pivotStub);
=======
=======
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392

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
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    }
}
