<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

class BelongsToManyService
{
    public function get (array $config, string $stub): string
    {
        $fields = [];

        foreach ($config['fields'] as $field)
        {
            if (array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany')
            {
                $fields[] = $field;
            }
        }
        $editBelongsToMany = '';
        $belongsToManyMethods = '';

        foreach ($fields as $field)
        {
            $editBelongsToMany .= $this->getEditBelongsToMany($field['relationship']);

            $belongsToManyMethods .= $this->getUpdatedNew($field['relationship']);
            $belongsToManyMethods .= $this->getAddItem($field['relationship']);
            $belongsToManyMethods .= $this->getRemoveItem($field['relationship']);
            $belongsToManyMethods .= $this->getCreateItem($field['relationship']);
        }

        $stub = str_replace(
            ':belongsToManyMethods:',
            $belongsToManyMethods,
            $stub
        );

        return str_replace(
            ':editBelongsToMany:',
            $editBelongsToMany,
            $stub
        );
    }

    private function getEditBelongsToMany (array $relationship): string
    {
        $editBelongsToMany = "\$this->"
            . $relationship['name']
            . " = \$this->editing->"
            . $relationship['name'] . ';' . PHP_EOL;

        $editBelongsToMany .= "\$this->new"
            . $relationship['model']
            . " = '';";
        return $editBelongsToMany;
    }

    private function getUpdatedNew (array $relationship): string
    {
        $stub = file_get_contents(__DIR__ . '/../../Stubs/livewire/updatePropertyBelongsToMany.stub');

        $stub = str_replace(
            ':model:',
            $relationship['model'],
            $stub
        );

        $stub = str_replace(
            ':name:',
            $relationship['name'],
            $stub
        );

        $stub = str_replace(
            ':searchable:',
            $relationship['searchable'],
            $stub
        );

        return str_replace(
            ':modelLower:',
            strtolower($relationship['model']),
            $stub
        );
    }

    private function getAddItem (array $relationship): string
    {
        $stub = file_get_contents(__DIR__ . '/../../Stubs/livewire/addItemBelongsToMany.stub');

        $stub = str_replace(
            ':model:',
            $relationship['model'],
            $stub
        );

        $stub = str_replace(
            ':modelLower:',
            strtolower($relationship['model']),
            $stub
        );

        return str_replace(
            ':table:',
            strtolower($relationship['table']),
            $stub
        );
    }

    private function getRemoveItem (array $relationship): string
    {
        $stub = file_get_contents(__DIR__ . '/../../Stubs/livewire/removeItemBelongsToMany.stub');

        $stub = str_replace(
            ':model:',
            $relationship['model'],
            $stub
        );

        return str_replace(
            ':table:',
            strtolower($relationship['table']),
            $stub
        );
    }

    private function getCreateItem (array $relationship): string
    {
        $stub = file_get_contents(__DIR__ . '/../../Stubs/livewire/createItemBelongsToMany.stub');

        $stub = str_replace(
            ':model:',
            $relationship['model'],
            $stub
        );

        $stub = str_replace(
            ':searchable:',
            $relationship['searchable'],
            $stub
        );

        $stub = str_replace(
            ':modelLower:',
            strtolower($relationship['model']),
            $stub
        );

        return str_replace(
            ':name:',
            strtolower($relationship['name']),
            $stub
        );
    }
}
