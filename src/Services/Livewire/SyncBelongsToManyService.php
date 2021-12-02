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
            $syncStub = file_get_contents(__DIR__ . '/../../Stubs/livewire/syncBelongsToMany.stub');

            $syncStub = str_replace(
                ':relationshipName:',
                $field['relationship']['name'],
                $syncStub
            );

            $response .= str_replace(
                ':relationshipModel:',
                $field['relationship']['model'],
                $syncStub
            );
        }

        return str_replace(
            ':syncBelongsToMany:',
            $response,
            $stub
        );
    }
}
