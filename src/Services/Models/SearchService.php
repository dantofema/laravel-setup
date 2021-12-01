<?php

namespace Dantofema\LaravelSetup\Services\Models;

use Dantofema\LaravelSetup\Facades\Field;

class SearchService
{
    protected const SEARCH_STUB = __DIR__ . '/../../Stubs/model/scopeSearch.stub';

    public function get(array $config, string $stub): string
    {
        $searchStub = file_get_contents(self::SEARCH_STUB);

        $query = "\$query" . PHP_EOL;

        foreach (Field::config($config)->getSearchable() as $searchableField) {
            if (array_key_exists('relationship', $searchableField) and $searchableField['relationship']['type'] === 'belongsToMany') {
                $query .= "->orWhereHas('{$searchableField['relationship']['name']}', fn(\$q) => \$q->where('{$searchableField['relationship']['table']}.{$searchableField['relationship']['searchable']}', 'like',
                '%' . \$search . '%'))->with('{$searchableField['relationship']['name']}')" . PHP_EOL;

                continue;
            }

            $query .= "->orWhere('{$searchableField['name']}', 'like', '%' . \$search . '%')" . PHP_EOL;
        }

        $searchStub = str_replace(':query:', $query, $searchStub);

        return str_replace(':search:', $searchStub, $stub);
    }
}
