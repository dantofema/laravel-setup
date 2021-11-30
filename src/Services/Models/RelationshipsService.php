<?php

namespace Dantofema\LaravelSetup\Services\Models;

class RelationshipsService
{

    protected const STUB = __DIR__ . '/../../Stubs/model/relationships.stub';

    public function get (array $fields, string $stub): string
    {
        return str_replace(
            ':relationships:',
            $this->getMethod($fields),
            $stub
        );
    }

    private function getMethod (array $fields): string
    {
        $response = '';

        foreach ($fields as $field)
        {
            $relationStub = file_get_contents(self::STUB);
            $relationStub = str_replace(':method:', $field['relationship']['name'], $relationStub);
            $relationStub = str_replace(':type:', ucfirst($field['relationship']['type']), $relationStub);
            $relationStub = str_replace(':relation:', $field['relationship']['type'], $relationStub);
            $response .= str_replace(':related:', $field['relationship']['model'], $relationStub);
        }
        return $response;
    }
}