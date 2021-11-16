<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{
    use CommandTrait;

    const STUB_PATH = '/../Stubs/create_setup_table.php.stub';

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
        $rows = $this->getFields();
        $content = $this->replace($rows);
        File::put(Text::config($this->config)->path('migration'), $content);
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
                $row = sprintf("\$table->foreignId('%s')%s->constrained('%s');\r\n",
                    $field['name'],
                    ! empty($rules['nullable']) ? '->nullable()' : null,
                    $relationship['relationships']['table'],
                );
            }
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

    private function replace (string $rows): string|array
    {
        $this->stub = str_replace(':fields:', $rows, $this->stub);
        return str_replace(':className:', Str::of($this->config['table']['name'])->camel()->ucfirst(), $this->stub);
    }
}
