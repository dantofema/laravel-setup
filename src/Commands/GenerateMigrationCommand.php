<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{
    use CommandTrait;

    protected const DIRECTORY = 'database/migrations/';
    public $signature = 'generate:migration {path : path to the config file } {--force}';
    public $description = 'Migration file generator';

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        $this->init(['migration']);

        foreach ($this->properties as $property)
        {
            $this->put($property['type'], $this->replace($property));
        }

        return true;
    }

    private function replace (array $property): string
    {
        $rows = $this->getFields();

        $property['stub'] = str_replace(':fields:', $rows, $property['stub']);

        return str_replace(
            ':className:',
            Str::of($this->config['table']['name'])->camel()->ucfirst(),
            $property['stub']
        );
    }

    public function getFields (): string
    {
        $rows = '';
        foreach ($this->config['fields'] as $field)
        {
            $relationship = gen()->field()->getRelationship($field);
            $rules = gen()->field()->getRules($field);

            if (empty($relationship))
            {
                $row = sprintf(
                    "\$table->%s('%s')%s%s;\r\n",
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

        if ($field['relationship']['type'] === 'belongsTo')
        {
            return sprintf(
                "\$table->foreignId('%s')%s->constrained('%s');" . PHP_EOL,
                $field['name'],
                $field['rules']['nullable'] ? '->nullable()' : null,
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

        File::put(
            'database/migrations/' . now()->format('Y_m_d_His') . '_create_' . $table . '_pivot_table.php',
            $pivotStub
        );
    }
}
