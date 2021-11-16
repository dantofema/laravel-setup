<?php

namespace Dantofema\LaravelSetup\Services\Models;

class RelationshipService
{

    protected const STUB = __DIR__ . '/../../Stubs/model/relationshipMethod.stub';

    public function get (array $fields, string $stub): string
    {
        $stub = str_replace(
            ':useRelationships:',
            $this->getUse($fields),
            $stub
        );

        return str_replace(
            ':relationships:',
            $this->getMethod($fields),
            $stub
        );
    }

    private function getUse (array $fields): string
    {
        $import = '';

        foreach ($fields as $field)
        {
            $use = match ($field['relationships']['type'])
            {
                'hasMany' => 'use Illuminate\Database\Eloquent\Relations\HasMany;',
                'belongsToMany' => 'use Illuminate\Database\Eloquent\Relations\BelongsToMany;',
                'belongsTo' => 'use Illuminate\Database\Eloquent\Relations\BelongsTo;',
            };
            $import .= str_contains($import, $use) ? '' : $use . "\r\n";

            $import .= "use App\Models\\" . $field['relationships']['model'] . ";\r\n";
        }

        return $import;
    }

    private function getMethod (array $fields): string
    {
        $response = '';

        foreach ($fields as $field)
        {
            $relationStub = file_get_contents(self::STUB);
            $relationStub = str_replace(':method:', $field['relationships']['name'], $relationStub);
            $relationStub = str_replace(':type:', ucfirst($field['relationships']['type']), $relationStub);
            $relationStub = str_replace(':relation:', $field['relationships']['type'], $relationStub);
            $response .= str_replace(':related:', $field['relationships']['model'], $relationStub);
        }
        return $response;
    }
}