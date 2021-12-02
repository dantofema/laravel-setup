<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

class EditBelongsToManyService
{
    public function get(array $config, string $stub): string
    {
        $fields = [];

        foreach ($config['fields'] as $field) {
            if (array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany') {
                $fields[] = $field;
            }
        }

        $response = '';
        foreach ($fields as $field) {
            $editStub = "\$this->:relationshipName: = "
                . "\$this->editing->:relationshipName:;";
//                . "->pluck(':searchable:')->implode(',');";

            $editStub = str_replace(
                ':relationshipName:',
                $field['relationship']['name'],
                $editStub
            );

            $response .= str_replace(
                ':searchable:',
                $field['relationship']['searchable'],
                $editStub
            );
        }

        return str_replace(
            ':editBelongsToMany:',
            $response,
            $stub
        );
    }
}
