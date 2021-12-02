<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

class SyncBelongsToManyService
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
            $response .= str_replace(
                ':name:',
                $field['relationship']['name'],
                file_get_contents(__DIR__ . '/../../Stubs/livewire/syncBelongsToMany.stub')
            );
        }

        return str_replace(
            ':syncBelongsToMany:',
            $response,
            $stub
        );
    }
}
