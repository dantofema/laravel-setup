<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

class EditBelongsToManyService
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

        $response = '';
        foreach ($fields as $field)
        {
            $response .= "\$this->"
                . $field['relationship']['name']
                . " = \$this->editing->"
                . $field['relationship']['name'] . ';' . PHP_EOL;

            $response .= "\$this->new"
                . $field['relationship']['model']
                . " = '';";
        }

        return str_replace(
            ':editBelongsToMany:',
            $response,
            $stub
        );
    }
}
