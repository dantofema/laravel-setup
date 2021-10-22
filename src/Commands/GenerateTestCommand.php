<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTestCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/test.stub';
    protected const DIRECTORY = 'tests/Feature';
    public $signature = 'generate:test {path : path to the config file } {--force}';
    public $description = 'Test file generator';
    protected array $config;

    public function handle (): bool
    {
        if ( ! $this->init())
        {
            return false;
        };

        return $this->create();
    }

    public function create (): bool
    {
        $path = self::DIRECTORY . $this->config['test']['subdirectory'];

        return File::put(
            $path . $this->getFileName(),
            $this->replace());
    }

    private function getFileName (): string
    {
        return ucfirst($this->config['table']['name']) . 'LivewireTest.php';
    }

    private function replace (): string
    {
        $this->stub = $this->getTable();
        $this->stub = $this->getPath();
        $this->stub = str_replace(
            ':model:',
            $this->getModelName(),
            $this->stub);
        $this->stub = $this->getView();
        $this->stub = $this->getField();
        $this->stub = $this->getRequired();
        $this->stub = $this->getEditSlug();
        return $this->stub;
    }

    private function getTable (): string
    {
        return str_replace(
            ':table:',
            $this->config['table']['name'],
            $this->stub);
    }

    private function getPath (): string
    {
        return str_replace(
            ':path:',
            $this->config['model']['path'],
            $this->stub);
    }

    private function getView (): string
    {
        return str_replace(
            ':view:',
            $this->config['livewire']['view'],
            $this->stub);
    }

    private function getField (): string
    {
        $field = $this->config['table']['columns'][0];

        return str_replace(
            ':field:',
            $field[1],
            $this->stub);
    }

    private function getRequired (): string
    {
        $columns = [];

        foreach ($this->config['table']['columns'] as $column)
        {
            in_array('nullable', $column) ?: array_push($columns, $column);
        }

        $required = '';

        foreach ($columns as $column)
        {
            $method = File::get(__DIR__ . '/../Stubs/tests/required.stub');
            $method = str_replace(
                ':field:',
                $column[1],
                $method);
            $method = str_replace(
                ':view:',
                $this->config['livewire']['view'],
                $method);
            $method = str_replace(
                ':model:',
                $this->getModelName(),
                $method);
            $method = str_replace(
                ':table:',
                $this->config['table']['name'],
                $method);
            $required .= $method;
        }

        return str_replace(
            ':required:',
            $required,
            $this->stub);
    }

    private function getEditSlug (): string
    {
        $columns = $this->config['table']['columns'];

        if ( ! $this->inArray('slug', $columns))
        {
            return str_replace(
                ':edit-slug:',
                '',
                $this->stub);
        }

        $field = 'missing';
        foreach ($columns as $column)
        {
            if (in_array('slug', $column))
            {
                $field = $column['from'];
            }
        }

        $method = File::get(__DIR__ . '/../Stubs/tests/edit-slug.stub');
        $method = str_replace(
            ':field:',
            $field,
            $method);
        $method = str_replace(
            ':view:',
            $this->config['livewire']['view'],
            $method);
        $method = str_replace(
            ':model:',
            $this->getModelName(),
            $method);
        $method = str_replace(
            ':table:',
            $this->config['table']['name'],
            $method);
        return str_replace(
            ':edit-slug:',
            $method,
            $this->stub);
    }

}
