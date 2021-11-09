<?php

namespace Dantofema\LaravelSetup\Services\Models;

class RelationshipService
{

    protected const STUB = __DIR__ . '/../../Stubs/model/relationshipMethod.stub';

    public function get (array $relationships, string $stub): string
    {
        $stub = str_replace(
            ':useRelationships:',
            $this->getUse($relationships),
            $stub
        );

        return str_replace(
            ':relationships:',
            $this->getMethod($relationships),
            $stub
        );
    }

    private function getUse (array $relationships): string
    {
        $import = '';

        foreach ($relationships as $relationType => $relations)
        {
            $use = match ($relationType)
            {
                'hasMany' => 'use Illuminate\Database\Eloquent\Relations\HasMany;',
                'belongsToMany' => 'use Illuminate\Database\Eloquent\Relations\BelongsToMany;',
                'belongsTo' => 'use Illuminate\Database\Eloquent\Relations\BelongsTo;',
            };
            $import .= str_contains($import, $use) ? '' : $use . "\r\n";
        }

        return $import;
    }

    private function getMethod (array $relationships): string
    {
        $response = '';

        foreach ($relationships as $relationType => $relations)
        {
            foreach ($relations as $relation)
            {
                $relationStub = file_get_contents(self::STUB);
                $relationStub = str_replace(':type:', ucfirst($relationType), $relationStub);
                $relationStub = str_replace(':relation:', $relationType, $relationStub);
                $relationStub = str_replace(':method:', $relation[0], $relationStub);
                $response .= str_replace(':related:', $relation[1], $relationStub);
            }
        }
        return $response;
    }
}