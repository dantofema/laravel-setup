<?php

namespace Dantofema\LaravelSetup\Services\Models;

use Dantofema\LaravelSetup\Facades\Field;

class SearchService
{
    protected const SEARCH_STUB = __DIR__ . '/../../Stubs/model/scopeSearch.stub';

    public function get (array $config, string $stub): string
    {
        $searchStub = file_get_contents(self::SEARCH_STUB);

        $query = "\$query" . PHP_EOL;

        foreach (Field::config($config)->getSearchable() as $searchableField)
        {
            if (array_key_exists('relationship', $searchableField))
            {
                $query = $this->getWhereForRelationship($searchableField, $query);
                continue;
            }

            $query .= "->orWhere('{$searchableField['name']}', 'like', '%' . \$search . '%')" . PHP_EOL;
        }

        $searchStub = str_replace(':query:', $query, $searchStub);

        return str_replace(':search:', $searchStub, $stub);
    }

    private function getWhereForRelationship (array $field, string $query): string
    {
        if ($field['relationship']['type'] === 'belongsToMany')
        {
            $query .= "->orWhereHas('{$field['relationship']['name']}', fn(\$q) => \$q->where('{$field['relationship']['table']}.{$field['relationship']['searchable']}', 'like',
                '%' . \$search . '%'))->with('{$field['relationship']['name']}')" . PHP_EOL;
        }

        if ($field['relationship']['type'] === 'belongsTo')
        {
            $query .= "->orWhere('{$field['name']}', 'like', '%' . \$search . '%')->with('{$field['relationship']['name']}')" .
                PHP_EOL;
        }
        return $query;
    }
}
