<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{

    protected const DIRECTORY = 'database/migrations/';
    public $signature = 'generate:migration {path : path to the config file } {--force}';
    public $description = 'Migration file generator';

    public function handle (): bool
    {
        $config = include $this->argument('path');
        $this->info(gen()->config()->migration($config));

        if ($this->option('force'))
        {
            gen()->delete()->migration($config);
        }

        $path = gen()->path()->migration($config);
        $stub = gen()->stub()->migration();

        File::put($path, $this->replace($config, $stub));

        $this->warn('end');

        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $rows = $this->getFields($config);

        $stub = str_replace(':fields:', $rows, $stub);

        $stub = str_replace(
            ':className:',
            Str::of($config['table']['name'])->camel()->ucfirst(),
            $stub
        );
        return gen()->config()->replace($config, 'migration', $stub);
    }

    public function getFields ($config): string
    {
        $rows = '';
        foreach ($config['fields'] as $field)
        {
            $relationship = gen()->field()->getRelationship($field);
            $rules = gen()->field()->getRules($field);

            if (empty($relationship))
            {
                $row = sprintf(
                    "\$table->%s('%s')%s%s;" . PHP_EOL,
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

        if (array_key_exists('use', $config['model']))
        {
            if (in_array('SoftDeletes', $config['model']['use']))
            {
                $rows .= "\$table->softDeletes();" . PHP_EOL;
            }

            if (in_array('Userstamps', $config['model']['use']))
            {
                $rows .= "\$table->unsignedBigInteger('created_by')->nullable();\r\n";
                $rows .= "\$table->unsignedBigInteger('updated_by')->nullable();\r\n";
                if (in_array('SoftDeletes', $config['model']['use']))
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
            return '';
        }

        if ($field['relationship']['type'] === 'belongsTo')
        {
            return sprintf(
                "\$table->foreignId('%s')%s->constrained('%s');" . PHP_EOL,
                $field['name'],
                (isset($field['rules']['nullable']) and $field['rules']['nullable'])
                    ? '->nullable()'
                    : null,
                $field['relationship']['table']
            );
        }

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
            $pivotStub
        );

        $fields = "\$table->unsignedInteger('" . $explode[0] . "_id');" . PHP_EOL;
        $fields .= "\$table->unsignedInteger('" . $explode[1] . "_id');" . PHP_EOL;

        $pivotStub = str_replace(
            ':fields:',
            $fields,
            $pivotStub
        );

        $pivotStub = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $pivotStub);

        File::put(
            'database/migrations/' . now()->format('Y_m_d_His') . '_create_' . $table . '_pivot_table.php',
            $pivotStub
        );
    }
}
